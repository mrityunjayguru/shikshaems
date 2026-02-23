<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RouteVehicleHistory;
use App\Models\TransportationPayment;
use App\Models\Students;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackingPageController extends Controller
{
    /**
     * Get tracking page data for student's specific stop
     * Works for both Student and Parent apps
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyStopTracking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'vehicle_number' => 'nullable|string' // Optional filter by bus number
        ]);

        if ($validator->fails()) {
            return ResponseService::validationError($validator->errors()->first());
        }

        $studentId = $request->student_id;
        $vehicleNumber = $request->vehicle_number;

        // Get student with user relationship
        $student = Students::with('user')->find($studentId);
        
        if (!$student) {
            return ResponseService::errorResponse('Student not found');
        }

        // Get student's active transportation subscription (optimized query)
        $transportationQuery = TransportationPayment::where('user_id', $student->user_id)
            ->where('expiry_date', '>', now())
            ->select('id', 'user_id', 'pickup_point_id', 'route_vehicle_id', 'shift_id', 'expiry_date');

        // Filter by vehicle number if provided
        if ($vehicleNumber) {
            $transportationQuery->whereHas('routeVehicle.vehicle', function($q) use ($vehicleNumber) {
                $q->where('vehicle_number', $vehicleNumber);
            });
        }

        $transportation = $transportationQuery->first();

        if (!$transportation) {
            $errorMsg = 'No active transportation subscription found for this student';
            if ($vehicleNumber) {
                $errorMsg .= " with vehicle number {$vehicleNumber}";
            }
            return ResponseService::errorResponse($errorMsg);
        }

        $pickupPointId = $transportation->pickup_point_id;
        $routeVehicleId = $transportation->route_vehicle_id;

        // Load only required relationships separately
        $transportation->load([
            'pickupPoint:id,name,latitude,longitude,pickup_time,dropoff_time',
            'routeVehicle:id,route_id,vehicle_id',
            'routeVehicle.vehicle:id,vehicle_number,name,capacity',
            'routeVehicle.route:id,name'
        ]);

        // Find active trip for this route vehicle today (optimized)
        $trip = RouteVehicleHistory::select('id', 'route_id', 'vehicle_id', 'driver_id', 'helper_id', 'shift_id', 'status', 'type', 'date', 'tracking')
            ->with([
                'vehicle:id,vehicle_number,name,capacity',
                'driver:id,first_name,last_name,mobile,image',
                'helper:id,first_name,last_name,mobile,image',
                'route:id,name',
                'shift:id,name'
            ])
            ->where('route_id', $transportation->routeVehicle->route_id)
            ->where('vehicle_id', $transportation->routeVehicle->vehicle_id)
            ->where('tracking', 1)
            ->where('status', 'inprogress')
            ->whereDate('date', today())
            ->first();

        if (!$trip) {
            return ResponseService::errorResponse('No active trip found for this vehicle today');
        }

        // Get student's pickup point
        $myStop = $transportation->pickupPoint;
        
        if (!$myStop) {
            return ResponseService::errorResponse('Pickup point not found');
        }

        // Get stop time from route_pickup_points pivot table (optimized)
        $routeStop = \DB::table('route_pickup_points')
            ->where('route_id', $trip->route_id)
            ->where('pickup_point_id', $pickupPointId)
            ->select('pickup_time', 'drop_time', 'order')
            ->first();

        if (!$routeStop) {
            return ResponseService::errorResponse('This stop is not part of the trip route');
        }

        // Get scheduled time based on trip type
        $scheduledTime = null;
        if ($trip->type === 'pickup') {
            $scheduledTime = $routeStop->pickup_time ?? $myStop->pickup_time;
        } else {
            $scheduledTime = $routeStop->drop_time ?? $myStop->dropoff_time;
        }

        // Get real-time tracking data from cache
        $cacheFile = storage_path("app/websocket/trip_{$trip->id}.json");
        $liveData = null;
        $myStopETA = null;
        $myStopDistance = null;
        $myStopStatus = 'pending';

        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            
            // Check if data is fresh (less than 60 seconds old)
            if (isset($cacheData['timestamp']) && (time() - $cacheData['timestamp']) < 60) {
                $liveData = $cacheData['data'];
                
                // Find student's stop in stops_status
                if (isset($liveData['stops_status'])) {
                    foreach ($liveData['stops_status'] as $stop) {
                        if ($stop['id'] == $pickupPointId) {
                            $myStopStatus = $stop['status'];
                            $myStopDistance = $stop['distance_km'];
                            
                            // Calculate ETA for this specific stop
                            if ($myStopStatus === 'approaching' || $myStopStatus === 'pending') {
                                $currentSpeed = $liveData['current_location']['speed'] ?? 30;
                                $avgSpeed = $currentSpeed > 0 ? $currentSpeed : 30;
                                $myStopETA = round(($myStopDistance / $avgSpeed) * 60); // minutes
                            } elseif ($myStopStatus === 'current') {
                                $myStopETA = 0; // Bus is at this stop
                            }
                            break;
                        }
                    }
                }
            }
        }

        // Prepare response
        $response = [
            'trip_id' => $trip->id,
            'trip_type' => $trip->type,
            'trip_status' => $trip->status,
            
            // Student Info
            'student' => [
                'id' => $student->id,
                'name' => $student->full_name,
                'class' => $student->class_section->full_name ?? 'N/A',
                'roll_number' => $student->roll_number ?? null,
            ],
            
            // Vehicle Info
            'vehicle' => [
                'number' => $trip->vehicle->vehicle_number ?? 'N/A',
                'name' => $trip->vehicle->name ?? null,
                'capacity' => $trip->vehicle->capacity ?? null,
            ],
            
            // Driver Info
            'driver' => [
                'name' => $trip->driver->full_name ?? 'N/A',
                'phone' => $trip->driver->mobile ?? null,
                'image' => $trip->driver->image ?? null,
            ],
            
            // Helper Info
            'helper' => $trip->helper ? [
                'name' => $trip->helper->full_name ?? 'N/A',
                'phone' => $trip->helper->mobile ?? null,
                'image' => $trip->helper->image ?? null,
            ] : null,
            
            // My Stop Info
            'my_stop' => [
                'id' => $myStop->id,
                'name' => $myStop->name,
                'latitude' => $myStop->latitude,
                'longitude' => $myStop->longitude,
                'scheduled_time' => $scheduledTime,
                'status' => $myStopStatus, // completed, current, approaching, pending
                'distance_km' => $myStopDistance,
                'eta_minutes' => $myStopETA,
            ],
            
            // Live Tracking
            'live_tracking' => $liveData ? [
                'current_location' => $liveData['current_location'],
                'last_update' => $liveData['timestamp'],
            ] : null,
            
            // Socket Info
            'socket' => [
                'channel' => "trip.{$trip->id}",
                'event' => 'location.update',
                'pusher_key' => config('broadcasting.connections.pusher.key'),
                'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
        ];

        return ResponseService::successResponse('Tracking page data', $response);
    }
}

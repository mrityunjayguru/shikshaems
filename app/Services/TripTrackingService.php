<?php

namespace App\Services;

use App\Models\RouteVehicleHistory;
use App\Models\RoutePickupPoint;
use App\Models\BusLocation;
use App\Models\TripStopArrival;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TripTrackingService
{
    private $earthRadius = 6371; // km
    private $stopProximityThreshold = 0.1; // 100 meters
    private $notificationThreshold = 10; // 10 minutes

    /**
     * Process GPS data for active trip
     */
    public function processGPSData($tripId, $latitude, $longitude, $speed, $deviceTime, $attributes = [])
    {
        try {
            // Get trip cache
            $tripCache = Cache::get("trip_{$tripId}");

            if (!$tripCache) {
                // Initialize trip cache if not exists
                $tripCache = $this->initializeTripCache($tripId);
                if (!$tripCache) {
                    Log::warning("Trip cache initialization failed for trip: {$tripId}");
                    return null;
                }
            }

            // Update current location
            $tripCache['current_location'] = [
                'lat' => $latitude,
                'lng' => $longitude,
                'speed' => $speed,
                'timestamp' => $deviceTime,
                'ignition' => $attributes['ignition'] ?? null,
                'battery' => $attributes['power'] ?? null,
                'satellites' => $attributes['sat'] ?? null
            ];

            // Calculate stop progress
            $stopData = $this->calculateStopProgress($tripCache, $latitude, $longitude, $speed);

            // Merge stop data into cache
            $tripCache = array_merge($tripCache, $stopData);
            $tripCache['last_update'] = now()->toDateTimeString();

            // Update cache (1 hour expiry)
            Cache::put("trip_{$tripId}", $tripCache, 3600);

            // Store in database
            $this->storeBusLocation($tripId, $latitude, $longitude, $speed, $deviceTime, $stopData);

            // Broadcast to WebSocket clients
            $this->broadcastLocationUpdate($tripId, $tripCache);

            // Send notifications if needed
            $this->handleNotifications($tripId, $stopData, $tripCache);

            return $tripCache;

        } catch (\Throwable $e) {
            Log::error('Trip Tracking Error:', [
                'trip_id' => $tripId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Initialize trip cache from database
     */
    private function initializeTripCache($tripId)
    {
        $trip = RouteVehicleHistory::with(['route.pickupPoints.students.user', 'route.pickupPoints.students.guardian'])
            ->find($tripId);

        if (!$trip || !$trip->route) {
            return null;
        }

        $stops = $trip->route->pickupPoints()
            ->orderBy('order')
            ->get()
            ->map(function ($stop) {
                return [
                    'id' => $stop->id,
                    'name' => $stop->name,
                    'latitude' => $stop->latitude,
                    'longitude' => $stop->longitude,
                    'order' => $stop->pivot->order ?? 1,
                    'students' => $stop->students->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'user_id' => $student->user_id,
                            'name' => $student->user->full_name ?? 'Unknown',
                            'guardian_id' => $student->guardian_id,
                            'boarded' => false
                        ];
                    })->toArray()
                ];
            })->toArray();

        $cache = [
            'trip_id' => $tripId,
            'vehicle_id' => $trip->vehicle_id,
            'route_id' => $trip->route_id,
            'trip_type' => $trip->trip_type,
            'current_location' => null,
            'current_stop_index' => 0,
            'stops' => $stops,
            'completed_stops' => [],
            'stops_status' => [],
            'last_update' => now()->toDateTimeString()
        ];

        Cache::put("trip_{$tripId}", $cache, 3600);
        return $cache;
    }

    /**
     * Calculate stop progress and ETA
     */
    private function calculateStopProgress($tripCache, $currentLat, $currentLng, $currentSpeed)
    {
        $stops = $tripCache['stops'];
        $currentStopIndex = $tripCache['current_stop_index'];
        $completedStops = $tripCache['completed_stops'] ?? [];

        $result = [
            'current_stop_id' => null,
            'current_stop_name' => null,
            'next_stop_id' => null,
            'next_stop_name' => null,
            'distance_to_next_stop' => null,
            'eta_to_next_stop' => null,
            'stops_status' => []
        ];

        // Check if current stop is reached
        if ($currentStopIndex < count($stops)) {
            $currentStop = $stops[$currentStopIndex];
            $distanceToCurrentStop = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $currentStop['latitude'],
                $currentStop['longitude']
            );

            // If within threshold (100m), mark as reached
            if ($distanceToCurrentStop <= $this->stopProximityThreshold) {
                if (!in_array($currentStop['id'], $completedStops)) {
                    $completedStops[] = $currentStop['id'];
                    $this->recordStopArrival($tripCache['trip_id'], $currentStop['id']);
                    $this->notifyStopArrival($tripCache['trip_id'], $currentStop);
                    $currentStopIndex++;
                }
            }
        }

        // Get next stop details
        if ($currentStopIndex < count($stops)) {
            $nextStop = $stops[$currentStopIndex];
            $distanceToNextStop = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $nextStop['latitude'],
                $nextStop['longitude']
            );

            $result['next_stop_id'] = $nextStop['id'];
            $result['next_stop_name'] = $nextStop['name'];
            $result['distance_to_next_stop'] = round($distanceToNextStop, 2);

            // Calculate ETA based on current speed or average speed
            $avgSpeed = $currentSpeed > 0 ? $currentSpeed : 30; // Default 30 km/h
            $result['eta_to_next_stop'] = round(($distanceToNextStop / $avgSpeed) * 60); // minutes

            // Previous stop (current)
            if ($currentStopIndex > 0) {
                $result['current_stop_id'] = $stops[$currentStopIndex - 1]['id'];
                $result['current_stop_name'] = $stops[$currentStopIndex - 1]['name'];
            }
        }

        // Build stops status
        foreach ($stops as $index => $stop) {
            $distanceFromBus = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $stop['latitude'],
                $stop['longitude']
            );

            $status = 'pending';
            if (in_array($stop['id'], $completedStops)) {
                $status = 'completed';
            } elseif ($index == $currentStopIndex) {
                $status = 'approaching';
            }

            $result['stops_status'][] = [
                'stop_id' => $stop['id'],
                'stop_name' => $stop['name'],
                'order' => $stop['order'],
                'status' => $status,
                'distance_km' => round($distanceFromBus, 2),
                'students' => $stop['students'],
                'eta_minutes' => $index == $currentStopIndex ? $result['eta_to_next_stop'] : null
            ];
        }

        $result['current_stop_index'] = $currentStopIndex;
        $result['completed_stops'] = $completedStops;
        $result['total_stops'] = count($stops);
        $result['remaining_stops'] = count($stops) - $currentStopIndex;

        return $result;
    }

    /**
     * Calculate distance using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $this->earthRadius * $c; // km
    }

    /**
     * Store bus location in database
     */
    private function storeBusLocation($tripId, $lat, $lng, $speed, $deviceTime, $stopData)
    {
        BusLocation::create([
            'trip_id' => $tripId,
            'device_time' => $deviceTime,
            'latitude' => $lat,
            'longitude' => $lng,
            'speed' => $speed,
            'current_stop_id' => $stopData['current_stop_id'] ?? null,
            'next_stop_id' => $stopData['next_stop_id'] ?? null,
            'eta_minutes' => $stopData['eta_to_next_stop'] ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Record stop arrival
     */
    private function recordStopArrival($tripId, $stopId)
    {
        TripStopArrival::create([
            'trip_id' => $tripId,
            'stop_id' => $stopId,
            'arrival_time' => now(),
            'students_boarded' => 0
        ]);

        Log::info("Stop arrival recorded: Trip={$tripId}, Stop={$stopId}");
    }

    /**
     * Broadcast location update via Redis/WebSocket
     */
    private function broadcastLocationUpdate($tripId, $tripCache)
    {
        $payload = json_encode([
            'type' => 'location_update',
            'trip_id' => $tripId,
            'location' => $tripCache['current_location'],
            'current_stop' => $tripCache['current_stop_name'] ?? null,
            'next_stop' => $tripCache['next_stop_name'] ?? null,
            'distance_to_next' => $tripCache['distance_to_next_stop'] ?? null,
            'eta_minutes' => $tripCache['eta_to_next_stop'] ?? null,
            'stops_status' => $tripCache['stops_status'] ?? [],
            'remaining_stops' => $tripCache['remaining_stops'] ?? 0,
            'timestamp' => now()->toIso8601String()
        ]);

        // Publish to Redis channel
        try {
            Redis::publish("trip.{$tripId}", $payload);
        } catch (\Exception $e) {
            Log::warning("Redis publish failed: " . $e->getMessage());
        }
    }

    /**
     * Handle notifications
     */
    private function handleNotifications($tripId, $stopData, $tripCache)
    {
        if (!isset($stopData['next_stop_id']) || !isset($stopData['eta_to_next_stop'])) {
            return;
        }

        $eta = $stopData['eta_to_next_stop'];
        $nextStopId = $stopData['next_stop_id'];

        // Check if notification already sent
        $cacheKey = "notification_sent_{$tripId}_{$nextStopId}_{$eta}";
        if (Cache::has($cacheKey)) {
            return;
        }

        // Send notification when 10 minutes away
        if ($eta <= $this->notificationThreshold && $eta > 8) {
            $this->sendApproachingNotification($tripId, $stopData, $tripCache);
            Cache::put($cacheKey, true, 600); // 10 minutes
        }
    }

    /**
     * Send approaching notification
     */
    private function sendApproachingNotification($tripId, $stopData, $tripCache)
    {
        $stops = $tripCache['stops'];
        $nextStopIndex = $tripCache['current_stop_index'];

        if ($nextStopIndex >= count($stops)) {
            return;
        }

        $nextStop = $stops[$nextStopIndex];
        $eta = $stopData['eta_to_next_stop'];

        foreach ($nextStop['students'] as $student) {
            // Notify student
            send_notification(
                [$student['user_id']],
                "Bus Approaching 🚌",
                "Your bus will arrive at {$nextStop['name']} in approximately {$eta} minutes",
                'bus_approaching',
                [
                    'trip_id' => $tripId,
                    'stop_id' => $nextStop['id'],
                    'stop_name' => $nextStop['name'],
                    'eta' => $eta
                ]
            );

            // Notify parent
            if ($student['guardian_id']) {
                send_notification(
                    [$student['guardian_id']],
                    "Bus Approaching 🚌",
                    "Bus will arrive at {$nextStop['name']} in {$eta} minutes to pick up {$student['name']}",
                    'bus_approaching',
                    [
                        'trip_id' => $tripId,
                        'stop_id' => $nextStop['id'],
                        'student_id' => $student['id'],
                        'eta' => $eta
                    ]
                );
            }
        }

        Log::info("Approaching notifications sent for stop: {$nextStop['name']}");
    }

    /**
     * Notify stop arrival
     */
    private function notifyStopArrival($tripId, $stop)
    {
        foreach ($stop['students'] as $student) {
            // Notify student
            send_notification(
                [$student['user_id']],
                "Bus Arrived ✅",
                "Your bus has arrived at {$stop['name']}",
                'bus_arrived',
                [
                    'trip_id' => $tripId,
                    'stop_id' => $stop['id'],
                    'stop_name' => $stop['name']
                ]
            );

            // Notify parent
            if ($student['guardian_id']) {
                send_notification(
                    [$student['guardian_id']],
                    "Bus Arrived ✅",
                    "Bus has arrived at {$stop['name']}",
                    'bus_arrived',
                    [
                        'trip_id' => $tripId,
                        'stop_id' => $stop['id'],
                        'student_id' => $student['id']
                    ]
                );
            }
        }

        Log::info("Arrival notifications sent for stop: {$stop['name']}");
    }
}

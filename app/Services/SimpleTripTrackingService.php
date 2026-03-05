<?php

namespace App\Services;

use App\Models\RouteVehicleHistory;
use App\Models\RoutePickupPoint;
use App\Events\TripLocationUpdate;

class SimpleTripTrackingService
{
    private $stopProximityThreshold = 0.1; // 100 meters in km
    private static $connections = [];

    /**
     * Register WebSocket connection
     */
    public static function registerConnection($tripId, $connection)
    {
        if (!isset(self::$connections[$tripId])) {
            self::$connections[$tripId] = [];
        }
        self::$connections[$tripId][] = $connection;
    }

    /**
     * Broadcast to WebSocket connections
     */
    private function broadcastToWebSocket($tripId, $payload)
    {
        try {
            // Store in cache file for API endpoint to read
            $cacheFile = storage_path("app/websocket/trip_{$tripId}.json");
            $dir = dirname($cacheFile);
            
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            file_put_contents($cacheFile, json_encode([
                'trip_id' => $tripId,
                'data' => $payload,
                'timestamp' => time()
            ]));
            
            // Direct Pusher broadcast (bypass Laravel queue)
            $this->broadcastToPusher($tripId, $payload);
            
        } catch (\Exception $e) {
            \Log::error("WebSocket broadcast error: " . $e->getMessage());
        }
    }

    /**
     * Direct Pusher broadcast without queue
     */
    private function broadcastToPusher($tripId, $payload)
    {
        try {
            $pusher = new \Pusher\Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    'useTLS' => true
                ]
            );

            $pusher->trigger(
                "trip.{$tripId}",
                'location.update',
                $payload
            );

            \Log::info("📡 Pusher broadcast successful for trip {$tripId}");
            
        } catch (\Exception $e) {
            \Log::error("Pusher broadcast error: " . $e->getMessage());
        }
    }

    /**
     * Process GPS data and broadcast to socket
     */
    public function processGPSData($tripId, $latitude, $longitude, $speed, $deviceTime, $attributes = [])
    {
        try {
            \Log::info("🔄 Processing GPS data for trip {$tripId}");
            \Log::info("📍 Position: Lat {$latitude}, Lng {$longitude}, Speed {$speed} km/h");
            
            // Use current default connection (which is already switched to school DB)
            $trip = RouteVehicleHistory::with(['route.pickupPoints','vehicle'])
                ->find($tripId);

            if (!$trip || !$trip->route) {
                \Log::warning("⚠️ Trip {$tripId} not found or has no route");
                return;
            }

            // Get stops (pickupPoints already returns PickupPoint models via belongsToMany)
            $stops = $trip->route->pickupPoints;

            if ($stops->isEmpty()) {
                \Log::warning("⚠️ Trip {$tripId} has no stops");
                return;
            }
            
            \Log::info("📊 Trip {$tripId} has " . $stops->count() . " stops");

            // Calculate stop progress
            $result = $this->calculateStopProgress($stops, $latitude, $longitude, $speed);

            // Check for proximity notifications (50m = 0.05 km)
            $this->checkProximityNotifications($tripId, $trip, $stops, $latitude, $longitude);

            // Build payload
            $payload = [
                'trip_id' => $tripId,
                'vehicle_number' => $trip->vehicle->vehicle_number ?? 'Unknown',
                'current_location' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'speed' => $speed,
                    'device_time' => $deviceTime,
                    'ignition' => $attributes['ignition'] ?? null,
                    'battery' => $attributes['power'] ?? null,
                ],
                'current_stop' => $result['current_stop'],
                'next_stop' => $result['next_stop'],
                'distance_to_next' => $result['distance_to_next'],
                'eta_minutes' => $result['eta_minutes'],
                'stops_status' => $result['stops_status'],
                'timestamp' => now()->toDateTimeString()
            ];

            // Broadcast via Socket (using existing WebSocket server)
            $this->broadcastToWebSocket($tripId, $payload);
            
            // Broadcast via Pusher
            $this->broadcastToPusher($tripId, $payload);

            \Log::info("📡 ========== LIVE TRACKING BROADCAST ==========");
            \Log::info("🚌 Trip ID: {$tripId}");
            \Log::info("📍 Current Location: Lat {$latitude}, Lng {$longitude}, Speed {$speed} km/h");
            
            if ($payload['current_stop']) {
                \Log::info("🚏 Current Stop: {$payload['current_stop']['name']} ({$payload['current_stop']['distance_km']} km away)");
            } else {
                \Log::info("🚏 Current Stop: None (bus is moving)");
            }
            
            if ($payload['next_stop']) {
                \Log::info("➡️  Next Stop: {$payload['next_stop']['name']}");
                \Log::info("📏 Distance to Next: {$payload['distance_to_next']} km");
                \Log::info("⏱️  ETA: {$payload['eta_minutes']} minutes");
            } else {
                \Log::info("➡️  Next Stop: None (last stop reached)");
            }
            
            \Log::info("📊 Total Stops: " . count($payload['stops_status']));
            foreach ($payload['stops_status'] as $stop) {
                \Log::info("   - {$stop['name']}: {$stop['status']} ({$stop['distance_km']} km)");
            }
            \Log::info("================================================");

        } catch (\Exception $e) {
            \Log::error("Error processing GPS data: " . $e->getMessage());
        }
    }

    /**
     * Check proximity and send notifications when vehicle is within 50m
     */
    private function checkProximityNotifications($tripId, $trip, $stops, $currentLat, $currentLng)
    {
        $proximityThreshold = 0.05; // 50 meters in km
        
        foreach ($stops as $stop) {
            $distance = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $stop->latitude,
                $stop->longitude
            );

            // Check if vehicle is within 50m of this stop
            if ($distance <= $proximityThreshold) {
                // Check if notification already sent for this stop today
                $cacheKey = "proximity_notification_{$tripId}_{$stop->id}_" . date('Y-m-d');
                
                if (!\Cache::has($cacheKey)) {
                    // Send notification to students at this pickup point
                    $this->sendProximityNotification($trip, $stop, $distance);
                    
                    // Cache for 24 hours to avoid duplicate notifications
                    \Cache::put($cacheKey, true, now()->endOfDay());
                    
                    \Log::info("🔔 Proximity notification sent for stop: {$stop->name} (Distance: " . round($distance * 1000, 2) . "m)");
                }
            }
        }
    }

    /**
     * Send proximity notification to students/parents
     */
    private function sendProximityNotification($trip, $stop, $distance)
    {
        try {
            // Get students assigned to this pickup point
            $students = \App\Models\TransportationPayment::where('pickup_point_id', $stop->id)
                ->where('status', 'paid')
                ->where('expiry_date', '>', now())
                ->with('user')
                ->get();

            if ($students->isEmpty()) {
                \Log::info("⚠️ No students found at stop: {$stop->name}");
                return;
            }

            $distanceInMeters = round($distance * 1000, 0);
            $vehicleNumber = $trip->vehicle->vehicle_number ?? 'School Bus';
            
            $title = 'Bus Approaching';
            $body = "Your bus ({$vehicleNumber}) is {$distanceInMeters}m away from {$stop->name}";
            $type = 'bus_proximity';

            $totalNotificationsSent = 0;
            $notificationDetails = [];

            foreach ($students as $transportPayment) {
                $student = $transportPayment->user;
                
                if (!$student) {
                    continue;
                }

                // Get parent/guardian IDs
                $guardianIds = \App\Models\Students::where('user_id', $student->id)
                    ->pluck('guardian_id')
                    ->toArray();

                // Send to student
                $userIds = [$student->id];
                
                // Add guardians
                if (!empty($guardianIds)) {
                    $userIds = array_merge($userIds, $guardianIds);
                }

                // Send notification
                $notificationData = [
                    'trip_id' => $trip->id,
                    'stop_id' => $stop->id,
                    'stop_name' => $stop->name,
                    'distance_meters' => $distanceInMeters,
                    'vehicle_number' => $vehicleNumber
                ];

                try {
                    send_notification($userIds, $title, $body, $type, $notificationData);
                    
                    $totalNotificationsSent += count($userIds);
                    $notificationDetails[] = [
                        'student_id' => $student->id,
                        'student_name' => $student->first_name . ' ' . $student->last_name,
                        'recipients' => count($userIds),
                        'guardian_ids' => $guardianIds
                    ];
                    
                } catch (\Exception $e) {
                    \Log::error("❌ Failed to send notification to student {$student->id}: " . $e->getMessage());
                }
            }

            // Success log with details
            if ($totalNotificationsSent > 0) {
                \Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                \Log::info("✅ NOTIFICATION SUCCESSFULLY SENT!");
                \Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                \Log::info("🚌 Trip ID: {$trip->id}");
                \Log::info("🚏 Stop: {$stop->name}");
                \Log::info("📏 Distance: {$distanceInMeters}m");
                \Log::info("🚗 Vehicle: {$vehicleNumber}");
                \Log::info("📱 Total Recipients: {$totalNotificationsSent}");
                \Log::info("👥 Students Notified: " . count($notificationDetails));
                \Log::info("");
                \Log::info("📋 Notification Details:");
                foreach ($notificationDetails as $detail) {
                    \Log::info("   • {$detail['student_name']} (ID: {$detail['student_id']})");
                    \Log::info("     Recipients: {$detail['recipients']} (Student + " . count($detail['guardian_ids']) . " Guardian(s))");
                    if (!empty($detail['guardian_ids'])) {
                        \Log::info("     Guardian IDs: " . implode(', ', $detail['guardian_ids']));
                    }
                }
                \Log::info("");
                \Log::info("💬 Message: \"{$body}\"");
                \Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            }

        } catch (\Exception $e) {
            \Log::error("❌ Error sending proximity notification: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Calculate stop progress, distances, and ETA
     */
    private function calculateStopProgress($stops, $currentLat, $currentLng, $currentSpeed)
    {
        $result = [
            'current_stop' => null,
            'next_stop' => null,
            'distance_to_next' => null,
            'eta_minutes' => null,
            'stops_status' => []
        ];

        $nearestStop = null;
        $nearestDistance = PHP_FLOAT_MAX;
        $nearestIndex = -1;

        // Find nearest stop
        foreach ($stops as $index => $stop) {
            $distance = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $stop->latitude,
                $stop->longitude
            );

            if ($distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestStop = $stop;
                $nearestIndex = $index;
            }
        }

        // Determine current and next stop
        if ($nearestDistance <= $this->stopProximityThreshold) {
            // Bus is at this stop
            $result['current_stop'] = [
                'id' => $nearestStop->id,
                'name' => $nearestStop->name,
                'distance_km' => round($nearestDistance, 2)
            ];

            // Next stop
            if ($nearestIndex + 1 < count($stops)) {
                $nextStop = $stops[$nearestIndex + 1];
                
                $distanceToNext = $this->calculateDistance(
                    $currentLat,
                    $currentLng,
                    $nextStop->latitude,
                    $nextStop->longitude
                );

                $avgSpeed = $currentSpeed > 0 ? $currentSpeed : 30; // Default 30 km/h
                $eta = round(($distanceToNext / $avgSpeed) * 60); // minutes

                $result['next_stop'] = [
                    'id' => $nextStop->id,
                    'name' => $nextStop->name,
                    'latitude' => $nextStop->latitude,
                    'longitude' => $nextStop->longitude
                ];
                $result['distance_to_next'] = round($distanceToNext, 2);
                $result['eta_minutes'] = $eta;
            }
        } else {
            // Bus is between stops - heading to nearest
            $result['next_stop'] = [
                'id' => $nearestStop->id,
                'name' => $nearestStop->name,
                'latitude' => $nearestStop->latitude,
                'longitude' => $nearestStop->longitude
            ];
            $result['distance_to_next'] = round($nearestDistance, 2);

            $avgSpeed = $currentSpeed > 0 ? $currentSpeed : 30;
            $result['eta_minutes'] = round(($nearestDistance / $avgSpeed) * 60);
        }

        // Build stops status
        foreach ($stops as $index => $stop) {
            $distanceFromBus = $this->calculateDistance(
                $currentLat,
                $currentLng,
                $stop->latitude,
                $stop->longitude
            );

            $status = 'pending';
            if ($index < $nearestIndex) {
                $status = 'completed';
            } elseif ($index == $nearestIndex && $nearestDistance <= $this->stopProximityThreshold) {
                $status = 'current';
            } elseif ($index == $nearestIndex) {
                $status = 'approaching';
            }

            $result['stops_status'][] = [
                'id' => $stop->id,
                'name' => $stop->name,
                'latitude' => $stop->latitude,
                'longitude' => $stop->longitude,
                'status' => $status,
                'distance_km' => round($distanceFromBus, 2),
                'order' => $stop->pivot->order ?? $index
            ];
        }

        return $result;
    }

    /**
     * Calculate distance using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // km
    }
}

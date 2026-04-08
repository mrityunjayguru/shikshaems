<?php

/**
 * Test script to debug Traccar HTTP Listener broadcasting
 * Run with: php test-traccar-broadcast.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\School;
use App\Models\GPS;
use App\Models\RouteVehicleHistory;
use App\Services\SimpleTripTrackingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "🔍 Testing Traccar Broadcasting...\n\n";

// Step 1: Check schools with traccar_phone
echo "Step 1: Checking schools with Traccar phone...\n";
$schools = School::whereNotNull('traccar_phone')
    ->where('status', 1)
    ->get();

echo "Found " . $schools->count() . " schools with Traccar phone\n\n";

foreach ($schools as $school) {
    echo "🏫 School: {$school->name} (ID: {$school->id})\n";
    echo "   Database: {$school->database_name}\n";
    echo "   Traccar Phone: {$school->traccar_phone}\n";
    echo "   Session ID: " . ($school->traccar_session_id ?? 'Not set') . "\n\n";
    
    // Switch to school database
    DB::setDefaultConnection('school');
    Config::set('database.connections.school.database', $school->database_name);
    DB::purge('school');
    DB::connection('school')->reconnect();
    DB::setDefaultConnection('school');
    
    // Step 2: Check for active trips
    echo "Step 2: Checking for active trips...\n";
    
    // Check with different status values
    $statusVariations = ['inprogress', 'in_progress', 'started', 'in-progress'];
    
    foreach ($statusVariations as $status) {
        $trips = RouteVehicleHistory::where('status', $status)
            ->whereDate('date', today())
            ->get();
        
        if ($trips->count() > 0) {
            echo "   ✅ Found {$trips->count()} trip(s) with status: '{$status}'\n";
            
            foreach ($trips as $trip) {
                echo "      - Trip ID: {$trip->id}\n";
                echo "        Route ID: {$trip->route_id}\n";
                echo "        Vehicle ID: {$trip->vehicle_id}\n";
                echo "        Status: {$trip->status}\n";
                echo "        Tracking: " . ($trip->tracking ?? 'NULL') . "\n";
                echo "        Date: {$trip->date}\n\n";
            }
        }
    }
    
    // Check all trips today regardless of status
    $allTrips = RouteVehicleHistory::whereDate('date', today())->get();
    echo "   📊 Total trips today: {$allTrips->count()}\n";
    
    if ($allTrips->count() > 0) {
        echo "   All trip statuses:\n";
        foreach ($allTrips as $trip) {
            echo "      - Trip {$trip->id}: status='{$trip->status}', tracking=" . ($trip->tracking ?? 'NULL') . "\n";
        }
    }
    
    echo "\n";
    
    // Step 3: Check GPS devices
    echo "Step 3: Checking GPS devices...\n";
    $gpsDevices = GPS::all();
    echo "   Found {$gpsDevices->count()} GPS device(s)\n";
    
    foreach ($gpsDevices as $gps) {
        echo "      - GPS ID: {$gps->id}\n";
        echo "        IMEI: {$gps->imei_no}\n";
        echo "        Assigned to Vehicle: {$gps->assigned_to}\n\n";
    }
    
    // Step 4: Test broadcasting with sample data
    if ($allTrips->count() > 0) {
        $testTrip = $allTrips->first();
        echo "Step 4: Testing broadcast with Trip ID: {$testTrip->id}...\n";
        
        try {
            $trackingService = app(SimpleTripTrackingService::class);
            
            // Sample GPS data
            $latitude = 28.4595;
            $longitude = 77.0266;
            $speed = 30;
            $deviceTime = now()->format('Y-m-d H:i:s');
            
            echo "   📍 Test Position: Lat {$latitude}, Lng {$longitude}, Speed {$speed} km/h\n";
            
            $trackingService->processGPSData(
                $testTrip->id,
                $latitude,
                $longitude,
                $speed,
                $deviceTime,
                []
            );
            
            echo "   ✅ Broadcast test completed! Check logs for details.\n\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Broadcast test failed: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo str_repeat("=", 80) . "\n\n";
}

// Switch back to main database
DB::setDefaultConnection('mysql');

echo "✅ Test completed!\n";
echo "\nTo fix broadcasting issues:\n";
echo "1. Make sure trips have correct status ('inprogress', 'in_progress', or 'started')\n";
echo "2. Ensure GPS devices have correct IMEI numbers\n";
echo "3. Check that vehicle_id matches between GPS and RouteVehicleHistory\n";
echo "4. Verify Traccar session is valid\n";

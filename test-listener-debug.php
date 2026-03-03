<?php

/**
 * Debug script to test if TraccarHttpListener can find active trips
 * Run: php test-listener-debug.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\School;
use App\Models\RouteVehicleHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "🔍 Testing TraccarHttpListener Active Trip Detection\n";
echo "====================================================\n\n";

// Get schools
$schools = School::whereNotNull('traccar_phone')
    ->where('status', 1)
    ->get();

echo "📊 Found " . $schools->count() . " schools with Traccar configured\n\n";

foreach ($schools as $school) {
    echo "🏫 School: {$school->name} (ID: {$school->id})\n";
    echo "   Database: {$school->database_name}\n";
    echo "   Traccar Phone: {$school->traccar_phone}\n";
    
    try {
        // Switch to school database
        DB::setDefaultConnection('school');
        Config::set('database.connections.school.database', $school->database_name);
        DB::purge('school');
        DB::connection('school')->reconnect();
        DB::setDefaultConnection('school');
        
        // Check for active trips
        $activeTrips = RouteVehicleHistory::where('tracking', 1)
            ->where('status', 'inprogress')
            ->whereDate('date', today())
            ->with(['vehicle', 'route'])
            ->get();
        
        if ($activeTrips->isEmpty()) {
            echo "   ⚠️  No active trips found\n";
            
            // Check all trips today
            $allTripsToday = RouteVehicleHistory::whereDate('date', today())->get();
            echo "   📅 Total trips today: " . $allTripsToday->count() . "\n";
            
            if ($allTripsToday->count() > 0) {
                echo "   📋 Trip statuses:\n";
                foreach ($allTripsToday as $trip) {
                    echo "      - Trip {$trip->id}: tracking={$trip->tracking}, status={$trip->status}\n";
                }
            }
        } else {
            echo "   ✅ Found " . $activeTrips->count() . " active trip(s):\n";
            foreach ($activeTrips as $trip) {
                echo "      - Trip ID: {$trip->id}\n";
                echo "        Vehicle: " . ($trip->vehicle->vehicle_number ?? 'N/A') . "\n";
                echo "        Route: " . ($trip->route->title ?? 'N/A') . "\n";
                echo "        Tracking: {$trip->tracking}\n";
                echo "        Status: {$trip->status}\n";
                echo "        Date: {$trip->date}\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Reset to main database
DB::setDefaultConnection('mysql');

echo "====================================================\n";
echo "✅ Debug complete\n";

<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\GPS;
use App\Models\Vehicle;
use App\Models\RouteVehicleHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "🔍 Checking Vehicle-GPS Mapping\n\n";

// Get school
$school = School::find(7);
echo "🏫 School: {$school->name}\n";
echo "📱 Traccar Phone: {$school->traccar_phone}\n\n";

// Switch to school database
Config::set('database.connections.school.database', $school->database_name);
DB::purge('school');
DB::reconnect('school');
DB::setDefaultConnection('school');

// Get active trips
$trips = RouteVehicleHistory::whereDate('date', today())
    ->where(function($query) {
        $query->where('status', 'inprogress')
              ->orWhere('status', 'in_progress')
              ->orWhere('status', 'started');
    })
    ->with('vehicle')
    ->get();

echo "📊 Active Trips Today: " . $trips->count() . "\n\n";

foreach ($trips as $trip) {
    echo "🚌 Trip ID: {$trip->id}\n";
    echo "   Vehicle ID: {$trip->vehicle_id}\n";
    echo "   Vehicle Name: " . ($trip->vehicle->name ?? 'N/A') . "\n";
    echo "   Vehicle Number: " . ($trip->vehicle->vehicle_number ?? 'N/A') . "\n";
    echo "   Status: {$trip->status}\n";
    
    // Check GPS assignment
    $gps = GPS::where('assigned_to', $trip->vehicle_id)->first();
    
    if ($gps) {
        echo "   ✅ GPS Found:\n";
        echo "      - GPS ID: {$gps->id}\n";
        echo "      - IMEI: {$gps->imei_no}\n";
        echo "      - Assigned To: {$gps->assigned_to}\n";
    } else {
        echo "   ❌ No GPS assigned to this vehicle\n";
        
        // Check all GPS devices
        echo "   📡 Available GPS Devices:\n";
        $allGps = GPS::all();
        foreach ($allGps as $g) {
            echo "      - GPS ID: {$g->id}, IMEI: {$g->imei_no}, Assigned To: {$g->assigned_to}\n";
        }
    }
    echo "\n";
}

// Check all vehicles
echo "🚗 All Vehicles:\n";
$vehicles = Vehicle::all();
foreach ($vehicles as $vehicle) {
    $gps = GPS::where('assigned_to', $vehicle->id)->first();
    $hasGps = $gps ? "✅ GPS: {$gps->imei_no}" : "❌ No GPS";
    echo "   - Vehicle ID: {$vehicle->id}, Name: {$vehicle->name}, {$hasGps}\n";
}

echo "\n✅ Check complete!\n";

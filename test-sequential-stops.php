<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\RouteVehicleHistory;
use App\Services\SimpleTripTrackingService;

echo "🧪 Testing Sequential Stop Order Logic\n";
echo "=====================================\n\n";

// Get school
$school = School::find(7);
if (!$school) {
    echo "❌ School not found\n";
    exit(1);
}

echo "🏫 School: {$school->name}\n";
echo "📊 Database: {$school->database_name}\n\n";

// Switch to school database
DB::setDefaultConnection('school');
Config::set('database.connections.school.database', $school->database_name);
DB::purge('school');
DB::reconnect('school');

// Get active trip
$trip = RouteVehicleHistory::with(['route.pickupPoints', 'vehicle'])
    ->whereDate('date', today())
    ->where('status', 'inprogress')
    ->first();

if (!$trip) {
    echo "❌ No active trip found\n";
    exit(1);
}

echo "🚌 Trip ID: {$trip->id}\n";
echo "🚗 Vehicle: {$trip->vehicle->vehicle_number}\n";
echo "📍 Route: {$trip->route->name}\n\n";

// Get stops
$stops = $trip->route->pickupPoints;
echo "📊 Total Stops: " . $stops->count() . "\n\n";

// Sort by order
$sortedStops = $stops->sortBy(function($stop) {
    return $stop->pivot->order ?? 0;
})->values();

echo "📋 Stops in Sequential Order:\n";
foreach ($sortedStops as $index => $stop) {
    $order = $stop->pivot->order ?? $index + 1;
    echo "   {$order}. {$stop->name} (Lat: {$stop->latitude}, Lng: {$stop->longitude})\n";
}
echo "\n";

// Test scenarios
$testScenarios = [
    [
        'name' => 'Trip Start - Before First Stop',
        'lat' => 28.18,
        'lng' => 76.61,
        'speed' => 30
    ],
    [
        'name' => 'At First Stop',
        'lat' => $sortedStops[0]->latitude,
        'lng' => $sortedStops[0]->longitude,
        'speed' => 0
    ],
    [
        'name' => 'Between First and Second Stop',
        'lat' => ($sortedStops[0]->latitude + $sortedStops[1]->latitude) / 2,
        'lng' => ($sortedStops[0]->longitude + $sortedStops[1]->longitude) / 2,
        'speed' => 25
    ]
];

$service = new SimpleTripTrackingService();

foreach ($testScenarios as $scenario) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🧪 Test: {$scenario['name']}\n";
    echo "📍 Position: Lat {$scenario['lat']}, Lng {$scenario['lng']}\n";
    echo "🚗 Speed: {$scenario['speed']} km/h\n\n";
    
    // Call the service
    $service->processGPSData(
        $trip->id,
        $scenario['lat'],
        $scenario['lng'],
        $scenario['speed'],
        now()->format('Y-m-d H:i:s'),
        []
    );
    
    echo "\n";
}

echo "✅ Test completed!\n";
echo "\nCheck the logs above to verify:\n";
echo "1. When trip starts, next stop should be Stop #1\n";
echo "2. When at Stop #1, next stop should be Stop #2\n";
echo "3. Stops should follow sequential order, not nearest distance\n";

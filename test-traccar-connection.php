<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing Traccar Connection\n";
echo "================================\n\n";

// Test 1: Check .env configuration
echo "1️⃣ Checking .env configuration...\n";
$traccarUrl = env('TRACCAR_SOCKET_URL');
$traccarDbHost = env('TRACCAR_DB_HOST');
$traccarDbName = env('TRACCAR_DB_NAME');
$traccarDbUser = env('TRACCAR_DB_USER');

echo "TRACCAR_SOCKET_URL: " . ($traccarUrl ?: '❌ NOT SET') . "\n";
echo "TRACCAR_DB_HOST: " . ($traccarDbHost ?: '❌ NOT SET') . "\n";
echo "TRACCAR_DB_NAME: " . ($traccarDbName ?: '❌ NOT SET') . "\n";
echo "TRACCAR_DB_USER: " . ($traccarDbUser ?: '❌ NOT SET') . "\n\n";

// Test 2: Check if Traccar database is accessible
echo "2️⃣ Testing Traccar database connection...\n";
if ($traccarDbHost && $traccarDbName) {
    try {
        $traccarConfig = [
            'driver' => 'mysql',
            'host' => $traccarDbHost,
            'port' => env('TRACCAR_DB_PORT', '3306'),
            'database' => $traccarDbName,
            'username' => $traccarDbUser,
            'password' => env('TRACCAR_DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        \Illuminate\Support\Facades\Config::set('database.connections.traccar_test', $traccarConfig);
        
        $result = \Illuminate\Support\Facades\DB::connection('traccar_test')
            ->select('SELECT COUNT(*) as count FROM tc_devices');
        
        echo "✅ Connection successful!\n";
        echo "   Devices in Traccar: " . $result[0]->count . "\n\n";
        
        // Show some devices
        $devices = \Illuminate\Support\Facades\DB::connection('traccar_test')
            ->select('SELECT id, name, uniqueid, status FROM tc_devices LIMIT 5');
        
        echo "   Sample devices:\n";
        foreach ($devices as $device) {
            echo "   - {$device->name} (IMEI: {$device->uniqueid}, Status: {$device->status})\n";
        }
        echo "\n";
        
    } catch (\Exception $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "⚠️ Traccar database not configured in .env\n\n";
}

// Test 3: Check for active trips
echo "3️⃣ Checking for active trips...\n";
try {
    $school = \App\Models\School::find(7);
    if ($school) {
        \Illuminate\Support\Facades\DB::setDefaultConnection('school');
        \Illuminate\Support\Facades\Config::set('database.connections.school.database', $school->database_name);
        \Illuminate\Support\Facades\DB::purge('school');
        \Illuminate\Support\Facades\DB::connection('school')->reconnect();
        
        $activeTrips = \App\Models\RouteVehicleHistory::where('tracking', 1)
            ->where('status', 'inprogress')
            ->whereDate('date', today())
            ->with(['vehicle'])
            ->get();
        
        if ($activeTrips->isEmpty()) {
            echo "⚠️ No active trips found for school {$school->name}\n\n";
        } else {
            echo "✅ Found " . $activeTrips->count() . " active trip(s):\n";
            foreach ($activeTrips as $trip) {
                echo "   - Trip ID: {$trip->id}, Vehicle: {$trip->vehicle->vehicle_number ?? 'N/A'}\n";
            }
            echo "\n";
        }
        
        \Illuminate\Support\Facades\DB::setDefaultConnection('mysql');
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Check GPS devices
echo "4️⃣ Checking GPS devices...\n";
try {
    $gpsDevices = \App\Models\GPS::where('assigned_to', 4)->get();
    
    if ($gpsDevices->isEmpty()) {
        echo "⚠️ No GPS devices assigned to vehicle 4\n\n";
    } else {
        echo "✅ Found " . $gpsDevices->count() . " GPS device(s):\n";
        foreach ($gpsDevices as $gps) {
            echo "   - IMEI: {$gps->imei_no}, Status: {$gps->status}\n";
        }
        echo "\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 5: Check if our GPS device exists in Traccar
echo "5️⃣ Checking if GPS device exists in Traccar...\n";
if ($traccarDbHost && $traccarDbName) {
    try {
        $imei = '862504128003736';
        $device = \Illuminate\Support\Facades\DB::connection('traccar_test')
            ->select('SELECT * FROM tc_devices WHERE uniqueid = ?', [$imei]);
        
        if (empty($device)) {
            echo "❌ Device with IMEI {$imei} NOT found in Traccar\n\n";
        } else {
            echo "✅ Device found in Traccar:\n";
            echo "   - ID: {$device[0]->id}\n";
            echo "   - Name: {$device[0]->name}\n";
            echo "   - IMEI: {$device[0]->uniqueid}\n";
            echo "   - Status: {$device[0]->status}\n\n";
            
            // Check latest position
            $position = \Illuminate\Support\Facades\DB::connection('traccar_test')
                ->select('SELECT * FROM tc_positions WHERE deviceid = ? ORDER BY devicetime DESC LIMIT 1', [$device[0]->id]);
            
            if (!empty($position)) {
                echo "   Latest position:\n";
                echo "   - Lat: {$position[0]->latitude}\n";
                echo "   - Lng: {$position[0]->longitude}\n";
                echo "   - Speed: " . round($position[0]->speed * 1.852, 2) . " km/h\n";
                echo "   - Time: {$position[0]->devicetime}\n\n";
            } else {
                echo "   ⚠️ No position data found\n\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n\n";
    }
}

// Test 6: Check SimpleTripTrackingService
echo "6️⃣ Checking SimpleTripTrackingService...\n";
$serviceFile = __DIR__ . '/app/Services/SimpleTripTrackingService.php';
if (file_exists($serviceFile)) {
    $content = file_get_contents($serviceFile);
    if (strpos($content, 'broadcastToPusher') !== false) {
        echo "✅ SimpleTripTrackingService has broadcastToPusher method\n";
        
        // Count occurrences
        $count = substr_count($content, 'broadcastToPusher');
        echo "   Found {$count} reference(s) to broadcastToPusher\n\n";
    } else {
        echo "❌ broadcastToPusher method NOT found in SimpleTripTrackingService\n\n";
    }
} else {
    echo "❌ SimpleTripTrackingService.php not found\n\n";
}

echo "================================\n";
echo "📊 SUMMARY\n";
echo "================================\n\n";

echo "✅ = Working\n";
echo "⚠️ = Warning/Not configured\n";
echo "❌ = Error/Not working\n\n";

echo "Next steps:\n";
echo "1. If Traccar DB not configured: Add to .env\n";
echo "2. If no active trips: Start a trip from admin panel\n";
echo "3. If device not in Traccar: Add device to Traccar\n";
echo "4. Start listener: php artisan traccar:http-listen --interval=5\n\n";

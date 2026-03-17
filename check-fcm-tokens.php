<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\School;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schoolId = $argv[1] ?? 7;
$userId = $argv[2] ?? 14;

echo "🔍 Checking FCM Tokens\n";
echo str_repeat("=", 50) . "\n\n";

$school = School::on('mysql')->where('id', $schoolId)->first();

Config::set('database.connections.school.database', $school->database_name);
DB::purge('school');
DB::connection('school')->reconnect();
DB::setDefaultConnection('school');

echo "🏫 School: {$school->name}\n";
echo "👤 User ID: {$userId}\n\n";

$devices = DB::table('user_devices')
    ->where('user_id', $userId)
    ->where('fcm_id', '!=', '')
    ->get();

echo "📱 Total Devices with FCM Token: " . $devices->count() . "\n\n";

if ($devices->isEmpty()) {
    echo "⚠️  No FCM tokens found\n";
    exit(0);
}

echo str_repeat("-", 50) . "\n";
echo "Device Details:\n";
echo str_repeat("-", 50) . "\n\n";

$uniqueTokens = [];
$duplicateCount = 0;

foreach ($devices as $index => $device) {
    $tokenPreview = substr($device->fcm_id, 0, 50) . "...";
    echo ($index + 1) . ". Device ID: {$device->id}\n";
    echo "   Token: {$tokenPreview}\n";
    echo "   Created: {$device->created_at}\n";
    
    if (in_array($device->fcm_id, $uniqueTokens)) {
        echo "   ⚠️  DUPLICATE TOKEN!\n";
        $duplicateCount++;
    } else {
        $uniqueTokens[] = $device->fcm_id;
    }
    
    echo "\n";
}

echo str_repeat("=", 50) . "\n";
echo "📊 Summary:\n";
echo str_repeat("=", 50) . "\n";
echo "Total devices: " . $devices->count() . "\n";
echo "Unique tokens: " . count($uniqueTokens) . "\n";
echo "Duplicate tokens: " . $duplicateCount . "\n\n";

if ($duplicateCount > 0) {
    echo "⚠️  WARNING: Duplicate FCM tokens found!\n";
    echo "This will cause multiple notifications to be sent.\n\n";
    echo "💡 Solution: Clean up duplicate tokens\n";
    echo "Run: php cleanup-duplicate-tokens.php {$schoolId} {$userId}\n";
} else {
    echo "✅ No duplicate tokens\n";
    
    if ($devices->count() > 1) {
        echo "\n💡 User has multiple devices (this is normal)\n";
        echo "Each device will receive 1 notification\n";
    }
}

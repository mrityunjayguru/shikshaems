<?php
/**
 * Test script to check how many devices/FCM tokens a user has
 * This helps identify if multiple notifications are due to multiple devices
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\UserDevices;
use Illuminate\Support\Facades\DB;

echo "=== User Devices & FCM Tokens Check ===\n\n";

// Get user ID from command line or use default
$userId = $argv[1] ?? null;

if (!$userId) {
    echo "Usage: php test-user-devices.php <user_id>\n";
    echo "Example: php test-user-devices.php 123\n\n";
    
    // Show recent users with devices
    echo "Recent users with devices:\n";
    $recentUsers = UserDevices::select('user_id', DB::raw('COUNT(*) as device_count'))
        ->where('fcm_id', '!=', '')
        ->groupBy('user_id')
        ->orderBy('device_count', 'DESC')
        ->limit(10)
        ->get();
    
    foreach ($recentUsers as $user) {
        echo "User ID: {$user->user_id} - Devices: {$user->device_count}\n";
    }
    exit;
}

// Get user details
$user = User::find($userId);
if (!$user) {
    echo "❌ User not found with ID: {$userId}\n";
    exit;
}

echo "User: {$user->full_name} (ID: {$user->id})\n";
echo "Email: {$user->email}\n";
echo "Mobile: {$user->mobile}\n\n";

// Get all devices for this user
$devices = UserDevices::where('user_id', $userId)->get();

echo "Total Devices: " . $devices->count() . "\n\n";

if ($devices->isEmpty()) {
    echo "⚠️ No devices found for this user\n";
    exit;
}

echo "Device Details:\n";
echo str_repeat("-", 80) . "\n";

foreach ($devices as $index => $device) {
    echo "Device #" . ($index + 1) . ":\n";
    echo "  ID: {$device->id}\n";
    echo "  FCM Token: " . ($device->fcm_id ? substr($device->fcm_id, 0, 50) . "..." : "EMPTY") . "\n";
    echo "  Model: {$device->model}\n";
    echo "  Created: {$device->created_at}\n";
    echo "  Updated: {$device->updated_at}\n";
    echo "\n";
}

// Check for duplicate FCM tokens
$fcmTokens = $devices->where('fcm_id', '!=', '')->pluck('fcm_id');
$uniqueFcmTokens = $fcmTokens->unique();

echo str_repeat("-", 80) . "\n";
echo "FCM Token Analysis:\n";
echo "  Total FCM Tokens: " . $fcmTokens->count() . "\n";
echo "  Unique FCM Tokens: " . $uniqueFcmTokens->count() . "\n";

if ($fcmTokens->count() > $uniqueFcmTokens->count()) {
    echo "  ⚠️ WARNING: Duplicate FCM tokens found!\n";
    echo "  This means same device is registered multiple times\n";
} else if ($uniqueFcmTokens->count() > 1) {
    echo "  ℹ️ User has multiple devices registered\n";
    echo "  Each device will receive a notification\n";
} else {
    echo "  ✓ User has only one device\n";
}

echo "\n=== Summary ===\n";
echo "If this user receives {$uniqueFcmTokens->count()} notifications, it's because they have {$uniqueFcmTokens->count()} devices.\n";
echo "If they receive MORE than {$uniqueFcmTokens->count()} notifications, then there's a webhook duplication issue.\n";

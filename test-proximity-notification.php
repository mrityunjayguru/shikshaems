<?php

/**
 * Test Proximity Notification
 * 
 * This script tests the proximity notification functionality
 * Usage: php test-proximity-notification.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RouteVehicleHistory;
use App\Models\PickupPoint;
use App\Models\TransportationPayment;
use App\Models\Students;
use App\Models\School;
use Illuminate\Support\Facades\DB;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🧪 PROXIMITY NOTIFICATION TEST\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get all schools
$schools = School::where('status', 1)->get();

if ($schools->isEmpty()) {
    echo "❌ No active schools found\n";
    exit(1);
}

echo "🏫 Available Schools:\n";
foreach ($schools as $index => $school) {
    echo "   " . ($index + 1) . ". {$school->name} (ID: {$school->id})\n";
    echo "      Database: {$school->database_name}\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Select a school (enter number): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$schoolIndex = (int)trim($line) - 1;
fclose($handle);

if (!isset($schools[$schoolIndex])) {
    echo "❌ Invalid school selection\n";
    exit(1);
}

$selectedSchool = $schools[$schoolIndex];

echo "\n✅ Selected School: {$selectedSchool->name}\n";
echo "   Switching to database: {$selectedSchool->database_name}\n\n";

// Switch to school database
config(['database.connections.school.database' => $selectedSchool->database_name]);
DB::purge('school');
DB::reconnect('school');
DB::setDefaultConnection('school');

echo "✅ Connected to school database\n\n";

// Get active trip
$trip = RouteVehicleHistory::whereDate('date', today())
    ->whereIn('status', ['inprogress', 'in_progress', 'started'])
    ->with(['route', 'vehicle'])
    ->first();

if (!$trip) {
    echo "❌ No active trip found today\n";
    echo "Please start a trip first\n";
    exit(1);
}

echo "✅ Active Trip Found:\n";
echo "   Trip ID: {$trip->id}\n";
echo "   Route: {$trip->route->name}\n";
echo "   Vehicle: {$trip->vehicle->vehicle_number}\n";
echo "   Status: {$trip->status}\n\n";

// Get all stops for this route
$stops = DB::table('route_pickup_points')
    ->join('pickup_points', 'route_pickup_points.pickup_point_id', '=', 'pickup_points.id')
    ->where('route_pickup_points.route_id', $trip->route_id)
    ->orderBy('route_pickup_points.order')
    ->select('pickup_points.*', 'route_pickup_points.order')
    ->get();

if ($stops->isEmpty()) {
    echo "❌ No stops found for this route\n";
    exit(1);
}

echo "📍 Available Stops:\n";
foreach ($stops as $index => $stop) {
    echo "   " . ($index + 1) . ". {$stop->name} (ID: {$stop->id})\n";
    echo "      Lat: {$stop->latitude}, Lng: {$stop->longitude}\n";
    
    // Check students at this stop
    $studentCount = TransportationPayment::where('pickup_point_id', $stop->id)
        ->where('status', 'paid')
        ->where('expiry_date', '>', now())
        ->count();
    
    echo "      Students: {$studentCount}\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Select a stop to test notification (enter number): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$stopIndex = (int)trim($line) - 1;
fclose($handle);

if (!isset($stops[$stopIndex])) {
    echo "❌ Invalid stop selection\n";
    exit(1);
}

$selectedStop = $stops[$stopIndex];

echo "\n✅ Selected Stop: {$selectedStop->name}\n";
echo "   Lat: {$selectedStop->latitude}, Lng: {$selectedStop->longitude}\n\n";

// Get students at this stop
$transportPayments = TransportationPayment::where('pickup_point_id', $selectedStop->id)
    ->where('status', 'paid')
    ->where('expiry_date', '>', now())
    ->with('user')
    ->get();

if ($transportPayments->isEmpty()) {
    echo "❌ No students found at this stop\n";
    exit(1);
}

echo "👥 Students at this stop:\n";
foreach ($transportPayments as $payment) {
    $student = $payment->user;
    if ($student) {
        echo "   • {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
        
        // Get guardian
        $studentRecord = Students::where('user_id', $student->id)->first();
        if ($studentRecord && $studentRecord->guardian_id) {
            $guardian = \App\Models\User::find($studentRecord->guardian_id);
            if ($guardian) {
                echo "     Guardian: {$guardian->first_name} {$guardian->last_name} (ID: {$guardian->id})\n";
            }
        }
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Send notification? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirm = trim(strtolower($line));
fclose($handle);

if ($confirm !== 'yes' && $confirm !== 'y') {
    echo "❌ Cancelled\n";
    exit(0);
}

echo "\n🔔 Sending notifications...\n\n";

// Send notifications
$distance = 0.03; // 30 meters
$distanceInMeters = 30;
$vehicleNumber = $trip->vehicle->vehicle_number ?? 'School Bus';

$title = 'Bus Approaching';
$body = "Your bus ({$vehicleNumber}) is {$distanceInMeters}m away from {$selectedStop->name}";
$type = 'bus_proximity';

$totalNotificationsSent = 0;
$notificationDetails = [];

foreach ($transportPayments as $transportPayment) {
    $student = $transportPayment->user;
    
    if (!$student) {
        continue;
    }

    // Get parent/guardian IDs
    $guardianIds = Students::where('user_id', $student->id)
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
        'stop_id' => $selectedStop->id,
        'stop_name' => $selectedStop->name,
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
        
        echo "✅ Notification sent to {$student->first_name} {$student->last_name}\n";
        
    } catch (\Exception $e) {
        echo "❌ Failed to send notification to student {$student->id}: " . $e->getMessage() . "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ NOTIFICATION TEST COMPLETED!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📱 Total Recipients: {$totalNotificationsSent}\n";
echo "👥 Students Notified: " . count($notificationDetails) . "\n\n";

echo "📋 Notification Details:\n";
foreach ($notificationDetails as $detail) {
    echo "   • {$detail['student_name']} (ID: {$detail['student_id']})\n";
    echo "     Recipients: {$detail['recipients']} (Student + " . count($detail['guardian_ids']) . " Guardian(s))\n";
    if (!empty($detail['guardian_ids'])) {
        echo "     Guardian IDs: " . implode(', ', $detail['guardian_ids']) . "\n";
    }
}

echo "\n💬 Message: \"{$body}\"\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

<?php
// Direct Pusher Test - No Laravel dependencies

require __DIR__ . '/vendor/autoload.php';

use Pusher\Pusher;

echo "🚀 Testing Pusher Direct Broadcast\n\n";

// Pusher credentials
$pusher = new Pusher(
    '906de0215505a87e7a7e',  // key
    '6979179892b397963a9b',  // secret
    '2118507',               // app_id
    [
        'cluster' => 'ap2',
        'useTLS' => true
    ]
);

echo "✅ Pusher instance created\n";

// Test data
$testData = [
    'trip_id' => 18,
    'vehicle_number' => 'TEST-DIRECT',
    'current_location' => [
        'latitude' => 25.677,
        'longitude' => 82.244,
        'speed' => 45,
        'device_time' => date('Y-m-d H:i:s'),
        'ignition' => true,
        'battery' => 90
    ],
    'current_stop' => null,
    'next_stop' => [
        'id' => 1,
        'name' => 'Direct Test Stop',
        'latitude' => '25.678',
        'longitude' => '82.245'
    ],
    'distance_to_next' => 1.2,
    'eta_minutes' => 3,
    'stops_status' => [
        [
            'id' => 1,
            'name' => 'Direct Test Stop',
            'latitude' => '25.678',
            'longitude' => '82.245',
            'status' => 'approaching',
            'distance_km' => 1.2,
            'order' => 1
        ]
    ],
    'timestamp' => date('Y-m-d H:i:s')
];

echo "📡 Broadcasting to channel: trip.18\n";
echo "📡 Event: location.update\n";
echo "📦 Data: " . json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

try {
    $result = $pusher->trigger('trip.18', 'location.update', $testData);
    
    if ($result) {
        echo "✅ SUCCESS! Broadcast sent successfully!\n";
        echo "📱 Check your browser - data should appear now!\n";
    } else {
        echo "❌ FAILED! Broadcast returned false\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔍 Pusher Info:\n";
echo "   App ID: 2118507\n";
echo "   Key: 906de0215505a87e7a7e\n";
echo "   Cluster: ap2\n";
echo "   Channel: trip.18\n";
echo "   Event: location.update\n";

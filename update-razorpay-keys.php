<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\PaymentConfiguration;

echo "🔧 Razorpay Keys Updater\n";
echo "========================\n\n";

// Get school
$schoolId = 7;
$school = School::find($schoolId);

if (!$school) {
    echo "❌ School not found\n";
    exit(1);
}

echo "🏫 School: {$school->name}\n\n";

// Switch to school database
DB::setDefaultConnection('school');
Config::set('database.connections.school.database', $school->database_name);
DB::purge('school');
DB::reconnect('school');

// Get current config
$config = PaymentConfiguration::where('payment_method', 'Razorpay')->first();

if (!$config) {
    echo "❌ Razorpay configuration not found\n";
    exit(1);
}

echo "📋 Current Configuration:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "API Key: {$config->api_key}\n";
echo "Secret Key: {$config->secret_key}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Prompt for new keys
echo "Enter NEW Razorpay credentials:\n";
echo "(Get from: https://dashboard.razorpay.com/app/keys)\n\n";

// Read API Key
echo "Enter API Key (rzp_test_xxx or rzp_live_xxx): ";
$newApiKey = trim(fgets(STDIN));

if (empty($newApiKey)) {
    echo "❌ API Key cannot be empty\n";
    exit(1);
}

if (!preg_match('/^rzp_(test|live)_/', $newApiKey)) {
    echo "⚠️  Warning: API Key should start with 'rzp_test_' or 'rzp_live_'\n";
    echo "Continue anyway? (y/n): ";
    $confirm = trim(fgets(STDIN));
    if (strtolower($confirm) !== 'y') {
        echo "❌ Cancelled\n";
        exit(1);
    }
}

// Read Secret Key
echo "Enter Secret Key: ";
$newSecretKey = trim(fgets(STDIN));

if (empty($newSecretKey)) {
    echo "❌ Secret Key cannot be empty\n";
    exit(1);
}

// Confirm update
echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 New Configuration:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "API Key: {$newApiKey}\n";
echo "Secret Key: " . substr($newSecretKey, 0, 10) . "...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Update these keys in database? (y/n): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'y') {
    echo "❌ Cancelled\n";
    exit(1);
}

// Update database
try {
    $config->api_key = $newApiKey;
    $config->secret_key = $newSecretKey;
    $config->save();
    
    echo "\n✅ Keys updated successfully!\n\n";
    
    // Test the new keys
    echo "🔄 Testing new credentials...\n\n";
    
    $api = new \Razorpay\Api\Api($newApiKey, $newSecretKey);
    
    $orderData = [
        'amount' => 100,
        'currency' => 'INR',
        'receipt' => 'test_' . time(),
        'notes' => ['test' => 'credentials_check']
    ];
    
    $order = $api->order->create($orderData);
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ SUCCESS! Credentials are working!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Test Order ID: {$order->id}\n";
    echo "Amount: ₹" . ($order->amount / 100) . "\n";
    echo "Status: {$order->status}\n";
    echo "\n";
    echo "🎉 Your Razorpay integration is now working!\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: {$e->getMessage()}\n";
    echo "\n💡 The keys were updated in database, but testing failed.\n";
    echo "Please verify the keys are correct from Razorpay dashboard.\n";
}

echo "\n";

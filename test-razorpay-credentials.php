<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\PaymentConfiguration;

echo "🔍 Razorpay Credentials Checker\n";
echo "================================\n\n";

// Get school
$schoolId = 7; // Change this to your school ID
$school = School::find($schoolId);

if (!$school) {
    echo "❌ School not found with ID: {$schoolId}\n";
    exit(1);
}

echo "🏫 School: {$school->name}\n";
echo "📊 Database: {$school->database_name}\n\n";

// Switch to school database
DB::setDefaultConnection('school');
Config::set('database.connections.school.database', $school->database_name);
DB::purge('school');
DB::reconnect('school');

// Get Razorpay configuration
$razorpayConfig = PaymentConfiguration::where('payment_method', 'Razorpay')->first();

if (!$razorpayConfig) {
    echo "❌ Razorpay configuration not found in database\n";
    echo "\n💡 Solution: Go to System Settings → Payment Gateway → Configure Razorpay\n";
    exit(1);
}

echo "📋 Razorpay Configuration Found:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Status: " . ($razorpayConfig->status == 1 ? "✅ Enabled" : "❌ Disabled") . "\n";
echo "API Key: " . ($razorpayConfig->api_key ? substr($razorpayConfig->api_key, 0, 15) . "..." : "❌ Not Set") . "\n";
echo "Secret Key: " . ($razorpayConfig->secret_key ? substr($razorpayConfig->secret_key, 0, 10) . "..." : "❌ Not Set") . "\n";
echo "Webhook Secret: " . ($razorpayConfig->webhook_secret_key ? "✅ Set" : "⚠️  Not Set") . "\n";
echo "Currency: " . ($razorpayConfig->currency_code ?? 'INR') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Validation checks
$errors = [];
$warnings = [];

if ($razorpayConfig->status != 1) {
    $errors[] = "Razorpay is disabled. Enable it from System Settings.";
}

if (empty($razorpayConfig->api_key)) {
    $errors[] = "API Key is not set.";
} else {
    // Check if it's test or live key
    if (strpos($razorpayConfig->api_key, 'rzp_test_') === 0) {
        echo "🧪 Mode: TEST MODE\n";
    } elseif (strpos($razorpayConfig->api_key, 'rzp_live_') === 0) {
        echo "🔴 Mode: LIVE MODE\n";
    } else {
        $errors[] = "API Key format is invalid. Should start with 'rzp_test_' or 'rzp_live_'";
    }
}

if (empty($razorpayConfig->secret_key)) {
    $errors[] = "Secret Key is not set.";
}

if (empty($razorpayConfig->webhook_secret_key)) {
    $warnings[] = "Webhook Secret is not set. Webhooks won't work.";
}

echo "\n";

// Display errors
if (!empty($errors)) {
    echo "❌ ERRORS FOUND:\n";
    foreach ($errors as $i => $error) {
        echo "   " . ($i + 1) . ". {$error}\n";
    }
    echo "\n";
}

// Display warnings
if (!empty($warnings)) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $i => $warning) {
        echo "   " . ($i + 1) . ". {$warning}\n";
    }
    echo "\n";
}

// If no errors, test the credentials
if (empty($errors)) {
    echo "✅ Configuration looks good. Testing credentials...\n\n";
    
    try {
        $api = new \Razorpay\Api\Api($razorpayConfig->api_key, $razorpayConfig->secret_key);
        
        // Try to create a test order
        echo "🔄 Creating test order with Razorpay...\n";
        
        $orderData = [
            'amount' => 100, // ₹1 in paise
            'currency' => 'INR',
            'receipt' => 'test_' . time(),
            'notes' => [
                'test' => 'credentials_check'
            ]
        ];
        
        $order = $api->order->create($orderData);
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ SUCCESS! Razorpay credentials are working!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Test Order Created:\n";
        echo "  Order ID: {$order->id}\n";
        echo "  Amount: ₹" . ($order->amount / 100) . "\n";
        echo "  Status: {$order->status}\n";
        echo "  Currency: {$order->currency}\n";
        echo "\n";
        echo "✅ Your Razorpay integration is working correctly!\n";
        echo "✅ You can now process payments.\n";
        
    } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "❌ SIGNATURE VERIFICATION ERROR\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Error: {$e->getMessage()}\n\n";
        echo "💡 Solution: Check webhook secret key\n";
        
    } catch (\Razorpay\Api\Errors\BadRequestError $e) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "❌ BAD REQUEST ERROR\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Error: {$e->getMessage()}\n\n";
        echo "💡 Possible Issues:\n";
        echo "   1. API Key or Secret Key is incorrect\n";
        echo "   2. Account is not activated on Razorpay\n";
        echo "   3. Currency not supported\n";
        
    } catch (\Exception $e) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "❌ AUTHENTICATION FAILED\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Error: {$e->getMessage()}\n\n";
        echo "💡 Common Causes:\n";
        echo "   1. API Key is incorrect\n";
        echo "   2. Secret Key is incorrect\n";
        echo "   3. Keys are from different Razorpay accounts\n";
        echo "   4. Test key used with live secret (or vice versa)\n\n";
        
        echo "🔧 How to Fix:\n";
        echo "   1. Login to Razorpay Dashboard: https://dashboard.razorpay.com/\n";
        echo "   2. Go to Settings → API Keys\n";
        echo "   3. Generate new keys if needed\n";
        echo "   4. Copy BOTH Key ID and Secret\n";
        echo "   5. Update in System Settings → Payment Gateway\n\n";
        
        echo "📋 Current Keys in Database:\n";
        echo "   API Key: {$razorpayConfig->api_key}\n";
        echo "   Secret: " . substr($razorpayConfig->secret_key, 0, 10) . "...\n\n";
        
        echo "⚠️  Make sure both keys are from the SAME Razorpay account!\n";
    }
} else {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "❌ Cannot test credentials due to configuration errors\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🔧 Fix the errors above and run this script again.\n";
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📚 Useful Links:\n";
echo "   Razorpay Dashboard: https://dashboard.razorpay.com/\n";
echo "   API Keys: https://dashboard.razorpay.com/app/keys\n";
echo "   Test Cards: https://razorpay.com/docs/payments/payments/test-card-details/\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

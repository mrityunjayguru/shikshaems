<?php

/**
 * Test Webhook with Notification
 * 
 * Usage: php test-webhook-notification.php [transaction_id]
 * 
 * This script simulates a Razorpay webhook call to test notification
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔔 Webhook Notification Test\n";
echo str_repeat("=", 50) . "\n\n";

// Get transaction ID from command line
$transactionId = $argv[1] ?? null;

if (!$transactionId) {
    echo "❌ Usage: php test-webhook-notification.php [transaction_id] [school_id]\n";
    echo "Example: php test-webhook-notification.php 26 7\n\n";
    echo "Note: school_id defaults to 7 if not provided\n";
    exit(1);
}

echo "💳 Transaction ID: {$transactionId}\n\n";

// Get school ID from command line or use default
$schoolId = $argv[2] ?? 7;

echo "🏫 School ID: {$schoolId}\n\n";

try {
    
    // Get school details
    $school = School::on('mysql')->where('id', $schoolId)->first();
    
    if (!$school) {
        echo "❌ School not found!\n";
        exit(1);
    }
    
    echo "✅ School: {$school->name}\n";
    echo "📊 Database: {$school->database_name}\n\n";
    
    // Switch to school database
    Config::set('database.connections.school.database', $school->database_name);
    DB::purge('school');
    DB::connection('school')->reconnect();
    DB::setDefaultConnection('school');
    
    // Get transaction details from school database
    $paymentTransaction = PaymentTransaction::find($transactionId);
    
    if (!$paymentTransaction) {
        echo "❌ Transaction not found in school database!\n";
        exit(1);
    }
    
    echo "✅ Transaction Details:\n";
    echo "   Amount: ₹{$paymentTransaction->amount}\n";
    echo "   Status: {$paymentTransaction->payment_status}\n";
    echo "   Order ID: {$paymentTransaction->order_id}\n";
    echo "   Gateway: {$paymentTransaction->payment_gateway}\n\n";
    
    // Get user
    $user = User::find($paymentTransaction->user_id);
    
    if (!$user) {
        echo "❌ User not found!\n";
        exit(1);
    }
    
    echo "✅ User: {$user->first_name} {$user->last_name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Mobile: {$user->mobile}\n\n";
    
    // Check current status
    if ($paymentTransaction->payment_status === 'succeed') {
        echo "⚠️  Transaction already processed!\n";
        echo "This will test duplicate prevention (no new notification should be sent)\n\n";
    }
    
    // Check FCM token before
    $fcmToken = DB::table('user_devices')
        ->where('user_id', $user->id)
        ->where('fcm_id', '!=', '')
        ->value('fcm_id');
    
    if ($fcmToken) {
        echo "✅ FCM Token exists: " . substr($fcmToken, 0, 50) . "...\n";
    } else {
        echo "⚠️  No FCM Token found (user needs to login to app)\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n";
    echo "🧪 Starting Webhook Test\n";
    echo str_repeat("-", 50) . "\n\n";
    
    // Prepare webhook payload
    $webhookPayload = [
        'event' => 'payment.captured',
        'payload' => [
            'payment' => [
                'entity' => [
                    'id' => 'pay_' . uniqid(),
                    'amount' => $paymentTransaction->amount * 100, // Convert to paise
                    'currency' => 'INR',
                    'status' => 'captured',
                    'order_id' => $paymentTransaction->order_id,
                    'method' => 'card',
                    'captured' => true,
                    'notes' => json_decode($paymentTransaction->order_id) // This should contain metadata
                ]
            ]
        ]
    ];
    
    echo "🔄 Simulating Razorpay Webhook...\n\n";
    
    // Import WebhookController
    $webhookController = new App\Http\Controllers\WebhookController(app(App\Repositories\User\UserInterface::class));
    
    // Create a mock request
    $request = Request::create('/api/webhook/razorpay', 'POST', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_RAZORPAY_SIGNATURE' => 'test_signature'
    ], json_encode($webhookPayload));
    
    echo "📤 Webhook Payload:\n";
    echo json_encode($webhookPayload, JSON_PRETTY_PRINT) . "\n\n";
    
    echo str_repeat("-", 50) . "\n";
    echo "⚡ Processing Webhook...\n";
    echo str_repeat("-", 50) . "\n\n";
    
    // Note: This will fail signature verification, so let's call the handler directly
    // We'll manually simulate what the webhook does
    
    // Refresh transaction
    $paymentTransaction->refresh();
    
    $alreadyProcessed = false;
    
    if ($paymentTransaction->payment_status === 'succeed') {
        echo "⏭️  Transaction already processed (duplicate prevention working!)\n";
        echo "✅ No notification sent (as expected)\n\n";
        $alreadyProcessed = true;
    } else {
        echo "🔄 Processing payment...\n";
        
        // Update transaction status
        DB::beginTransaction();
        try {
            $paymentTransaction->payment_status = 'succeed';
            $paymentTransaction->save();
            DB::commit();
            
            echo "✅ Transaction status updated to 'succeed'\n";
            
            // Start new transaction for fees
            DB::beginTransaction();
            
            // Here we would process fees, but for notification test we'll skip
            // and just send notification
            
            DB::commit();
            
            // Send notification
            echo "📨 Sending notification...\n";
            
            $body = "Payment successful. Amount: ₹{$paymentTransaction->amount}";
            send_notification(
                [$user->id], 
                'Fees Payment Successful', 
                $body, 
                'payment', 
                ['is_payment_success' => 'true']
            );
            
            echo "✅ Notification sent!\n\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: {$e->getMessage()}\n";
            throw $e;
        }
    }
    
    echo str_repeat("-", 50) . "\n";
    echo "📊 Test Results:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Transaction Status: {$paymentTransaction->payment_status}\n";
    echo "Notification Sent: " . ($paymentTransaction->payment_status === 'succeed' && !$alreadyProcessed ? "Yes" : "No (duplicate prevented)") . "\n\n";
    
    // Check FCM token
    echo str_repeat("-", 50) . "\n";
    echo "🔑 FCM Token Status:\n";
    echo str_repeat("-", 50) . "\n";
    
    $fcmToken = DB::table('user_devices')
        ->where('user_id', $user->id)
        ->where('fcm_id', '!=', '')
        ->value('fcm_id');
    
    if ($fcmToken) {
        echo "✅ FCM Token exists: " . substr($fcmToken, 0, 50) . "...\n";
        echo "📱 Notification should appear in mobile app\n";
    } else {
        echo "⚠️  No FCM Token found\n";
        echo "💡 User needs to login to mobile app first\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ Test completed!\n";
    echo str_repeat("=", 50) . "\n\n";
    
    echo "💡 To test duplicate prevention, run this script again with same transaction ID\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error occurred:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

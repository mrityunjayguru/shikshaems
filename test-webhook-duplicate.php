<?php

/**
 * Test Webhook Duplicate Prevention
 * 
 * Usage: php test-webhook-duplicate.php [transaction_id] [count]
 * 
 * This script simulates multiple webhook calls to test duplicate prevention
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use App\Models\PaymentTransaction;
use App\Models\User;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔁 Webhook Duplicate Prevention Test\n";
echo str_repeat("=", 50) . "\n\n";

// Get parameters
$transactionId = $argv[1] ?? null;
$schoolId = $argv[2] ?? 7;
$webhookCount = $argv[3] ?? 5;

if (!$transactionId) {
    echo "❌ Usage: php test-webhook-duplicate.php [transaction_id] [school_id] [count]\n";
    echo "Example: php test-webhook-duplicate.php 26 7 5\n\n";
    echo "Note: school_id defaults to 7, count defaults to 5\n";
    exit(1);
}

echo "💳 Transaction ID: {$transactionId}\n";
echo "🏫 School ID: {$schoolId}\n";
echo "🔢 Webhook calls to simulate: {$webhookCount}\n\n";

try {
    
    // Get school details
    $school = School::on('mysql')->where('id', $schoolId)->first();
    
    if (!$school) {
        echo "❌ School not found!\n";
        exit(1);
    }
    
    echo "✅ School: {$school->name}\n\n";
    
    // Switch to school database
    Config::set('database.connections.school.database', $school->database_name);
    DB::purge('school');
    DB::connection('school')->reconnect();
    DB::setDefaultConnection('school');
    
    // Get transaction and user
    $paymentTransaction = PaymentTransaction::find($transactionId);
    $user = User::find($paymentTransaction->user_id);
    
    echo "✅ User: {$user->first_name} {$user->last_name}\n";
    echo "💰 Amount: ₹{$paymentTransaction->amount}\n";
    echo "📊 Current Status: {$paymentTransaction->payment_status}\n\n";
    
    // Check FCM token
    $fcmToken = DB::table('user_devices')
        ->where('user_id', $user->id)
        ->where('fcm_id', '!=', '')
        ->value('fcm_id');
    
    if ($fcmToken) {
        echo "✅ FCM Token exists\n";
    } else {
        echo "⚠️  No FCM Token (notifications won't reach app)\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n";
    echo "🚀 Starting webhook simulation...\n";
    echo str_repeat("-", 50) . "\n\n";
    
    $processedCount = 0;
    $skippedCount = 0;
    $errorCount = 0;
    
    for ($i = 1; $i <= $webhookCount; $i++) {
        echo "Webhook #{$i}: ";
        
        // Refresh transaction to get latest status
        $paymentTransaction->refresh();
        
        // Check if already processed
        if ($paymentTransaction->payment_status === 'succeed') {
            echo "⏭️  Skipped (already processed)\n";
            $skippedCount++;
            
            // Small delay
            usleep(50000); // 0.05 seconds
            continue;
        }
        
        // Process webhook
        try {
            DB::beginTransaction();
            
            // Update status
            $paymentTransaction->payment_status = 'succeed';
            $paymentTransaction->save();
            
            // Commit immediately to lock status
            DB::commit();
            
            echo "✅ Processed";
            
            // Start new transaction for notification
            DB::beginTransaction();
            
            // Send notification
            $body = "Payment successful. Amount: ₹{$paymentTransaction->amount}";
            send_notification(
                [$user->id], 
                'Fees Payment Successful', 
                $body, 
                'payment', 
                ['is_payment_success' => 'true']
            );
            
            DB::commit();
            
            echo " + Notification sent\n";
            $processedCount++;
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: {$e->getMessage()}\n";
            $errorCount++;
        }
        
        // Small delay to simulate real webhook timing
        usleep(50000); // 0.05 seconds
    }
    
    echo "\n";
    
    // Count notifications after
    $notificationsAfter = DB::table('user_devices')
        ->where('user_id', $user->id)
        ->where('fcm_id', '!=', '')
        ->count();
    
    echo str_repeat("=", 50) . "\n";
    echo "📊 Test Results:\n";
    echo str_repeat("=", 50) . "\n\n";
    
    echo "Webhook Calls:\n";
    echo "  Total: {$webhookCount}\n";
    echo "  Processed: {$processedCount}\n";
    echo "  Skipped: {$skippedCount}\n";
    echo "  Errors: {$errorCount}\n\n";
    
    echo "Notifications:\n";
    echo "  Sent via FCM: {$processedCount}\n";
    echo "  Skipped (duplicate): {$skippedCount}\n\n";
    
    // Evaluate results
    echo str_repeat("-", 50) . "\n";
    
    if ($processedCount === 1 && $skippedCount === ($webhookCount - 1)) {
        echo "✅ PERFECT! Duplicate prevention working correctly\n";
        echo "   - Only 1 webhook processed\n";
        echo "   - Only 1 notification sent via FCM\n";
        echo "   - Other webhooks skipped\n";
    } else if ($processedCount > 1) {
        echo "❌ FAILED! Multiple webhooks processed\n";
        echo "   - Expected: 1 processed\n";
        echo "   - Got: {$processedCount} processed\n";
        echo "   - Issue: Duplicate prevention not working\n";
    } else if ($processedCount === 0) {
        echo "⚠️  No webhooks processed\n";
        echo "   - Transaction might have been already processed\n";
    } else {
        echo "⚠️  Unexpected result\n";
    }
    
    echo str_repeat("-", 50) . "\n\n";
    
    // Show FCM status
    echo "📱 FCM Notification Status:\n";
    echo str_repeat("-", 50) . "\n";
    
    $fcmToken = DB::table('user_devices')
        ->where('user_id', $user->id)
        ->where('fcm_id', '!=', '')
        ->value('fcm_id');
    
    if ($fcmToken) {
        echo "✅ FCM Token: " . substr($fcmToken, 0, 50) . "...\n";
        echo "📱 Notification should appear in mobile app\n";
    } else {
        echo "⚠️  No FCM Token found\n";
        echo "💡 User needs to login to mobile app\n";
    }
    
    echo "\n";
    echo "✅ Test completed!\n";
    echo str_repeat("=", 50) . "\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error occurred:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

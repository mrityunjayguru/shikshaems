<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\PaymentTransaction;

echo "🧪 Testing Multiple Fees Webhook\n";
echo "=================================\n\n";

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

// Get the pending payment transaction
$orderId = 'order_SNsXRikkCjPfOb'; // Your order ID
$transaction = PaymentTransaction::where('order_id', $orderId)->first();

if (!$transaction) {
    echo "❌ Transaction not found with order ID: {$orderId}\n";
    exit(1);
}

echo "📋 Transaction Found:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "ID: {$transaction->id}\n";
echo "Amount: ₹{$transaction->amount}\n";
echo "Status: {$transaction->payment_status}\n";
echo "Order ID: {$transaction->order_id}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($transaction->payment_status === 'succeed') {
    echo "⚠️  Transaction already processed\n";
    exit(0);
}

echo "🔄 Simulating successful payment...\n\n";

try {
    DB::beginTransaction();
    
    // Update transaction status
    $transaction->payment_status = 'succeed';
    $transaction->payment_id = 'pay_test_' . time();
    $transaction->save();
    
    echo "✅ Transaction status updated to 'succeed'\n\n";
    
    // Get metadata from transaction
    $metadata = json_decode(json_encode([
        'student_id' => 16,
        'parent_id' => 14,
        'school_id' => 7,
        'class_id' => 2,
        'session_year_id' => 1,
        'payment_transaction_id' => $transaction->id,
        'fees_type' => 'multiple',
        'multiple_fees' => '[{"fees_id":4,"fees_name":"Tution Fee - Class 2 - English","compulsory_amount":2500,"optional_amount":2000,"total":4500,"compulsory_details":[{"id":4,"amount":2500,"name":"Monthly Fee","fees_id":4}],"optional_details":[{"id":7,"amount":2000,"name":"Annual Fee","fees_id":4}],"installment_details":[],"due_charges":0,"advance":0},{"fees_id":2,"fees_name":"Admission Fee - Class 2 - English","compulsory_amount":5000,"optional_amount":0,"total":5000,"compulsory_details":[{"id":2,"amount":5000,"name":"Annual Fee","fees_id":2}],"optional_details":[],"installment_details":[],"due_charges":0,"advance":0}]'
    ]));
    
    // Process multiple fees
    $multipleFees = json_decode($metadata->multiple_fees, true);
    
    echo "📦 Processing {count($multipleFees)} fee groups...\n\n";
    
    foreach ($multipleFees as $feeData) {
        $feesId = $feeData['fees_id'];
        $compulsoryAmount = $feeData['compulsory_amount'] ?? 0;
        $optionalAmount = $feeData['optional_amount'] ?? 0;
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Processing Fees ID: {$feesId}\n";
        echo "Fees Name: {$feeData['fees_name']}\n";
        echo "Compulsory: ₹{$compulsoryAmount}\n";
        echo "Optional: ₹{$optionalAmount}\n";
        echo "Total: ₹{$feeData['total']}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        // Get fees details
        $fees = App\Models\Fee::where('id', $feesId)
            ->with(['fees_class_type', 'fees_class_type.fees_type'])
            ->first();
        
        if (!$fees) {
            echo "❌ Fees not found for ID: {$feesId}\n\n";
            continue;
        }
        
        // Update or create fees paid record
        $feesPaidDB = App\Models\FeesPaid::where([
            'fees_id' => $feesId,
            'student_id' => $metadata->student_id,
            'school_id' => $metadata->school_id
        ])->first();
        
        $feeTotal = $compulsoryAmount + $optionalAmount;
        $totalAmount = !empty($feesPaidDB) ? $feesPaidDB->amount + $feeTotal : $feeTotal;
        
        $feesPaidData = [
            'amount' => $totalAmount,
            'date' => date('Y-m-d'),
            'school_id' => $metadata->school_id,
            'fees_id' => $feesId,
            'student_id' => $metadata->student_id,
            'is_fully_paid' => $totalAmount >= ($fees->total_compulsory_fees ?? 0),
            'is_used_installment' => false
        ];
        
        $feesPaidResult = App\Models\FeesPaid::updateOrCreate(
            ['id' => $feesPaidDB->id ?? null],
            $feesPaidData
        );
        
        echo "✅ FeesPaid record created/updated (ID: {$feesPaidResult->id})\n";
        
        // Process compulsory fees
        if ($compulsoryAmount > 0) {
            $current_date = date('Y-m-d');
            
            $compulsoryFee = App\Models\CompulsoryFee::create([
                'student_id' => $metadata->student_id,
                'payment_transaction_id' => $transaction->id,
                'type' => 'Full Payment',
                'installment_id' => null,
                'mode' => 'Online',
                'cheque_no' => null,
                'amount' => $compulsoryAmount,
                'due_charges' => 0,
                'fees_paid_id' => $feesPaidResult->id,
                'status' => "Success",
                'date' => $current_date,
                'school_id' => $metadata->school_id,
            ]);
            
            echo "✅ CompulsoryFee record created (ID: {$compulsoryFee->id}, Amount: ₹{$compulsoryAmount})\n";
        }
        
        // Process optional fees
        if ($optionalAmount > 0) {
            $optionalDetails = $feeData['optional_details'] ?? [];
            $current_date = date('Y-m-d');
            
            foreach ($optionalDetails as $optional_fee) {
                $optionalFeeRecord = App\Models\OptionalFee::create([
                    'student_id' => $metadata->student_id,
                    'payment_transaction_id' => $transaction->id,
                    'class_id' => $metadata->class_id,
                    'fees_class_id' => $optional_fee['id'],
                    'mode' => 'Online',
                    'cheque_no' => null,
                    'amount' => $optional_fee['amount'],
                    'fees_paid_id' => $feesPaidResult->id,
                    'date' => $current_date,
                    'school_id' => $metadata->school_id,
                    'status' => "Success",
                ]);
                
                echo "✅ OptionalFee record created (ID: {$optionalFeeRecord->id}, Amount: ₹{$optional_fee['amount']})\n";
            }
        }
        
        echo "\n";
    }
    
    DB::commit();
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ SUCCESS! Payment processed successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "📊 Summary:\n";
    echo "   Total Amount: ₹{$transaction->amount}\n";
    echo "   Fees Processed: " . count($multipleFees) . "\n";
    echo "   Transaction ID: {$transaction->id}\n";
    echo "   Payment ID: {$transaction->payment_id}\n\n";
    
    echo "✅ Now check the fee summary API - due amount should be updated!\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n";
}

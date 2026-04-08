<?php
/**
 * Test script for receipt API
 * Tests if fees_paid record exists for given child_id and fees_id
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Students;
use App\Models\Fee;
use App\Models\FeesPaid;
use App\Services\CachingService;

echo "=== Receipt API Test ===\n\n";

// Get parameters from command line
$childId = $argv[1] ?? null;
$feesId = $argv[2] ?? null;

if (!$childId || !$feesId) {
    echo "Usage: php test-receipt-api.php <child_id> <fees_id>\n";
    echo "Example: php test-receipt-api.php 5 10\n\n";
    
    // Show available students with fees
    echo "Available Students with Fees:\n";
    echo str_repeat("-", 80) . "\n";
    
    $studentsWithFees = FeesPaid::with(['student', 'fees'])
        ->limit(10)
        ->get();
    
    foreach ($studentsWithFees as $fp) {
        echo "Child ID: {$fp->student->id} | Student: {$fp->student->full_name} | Fees ID: {$fp->fees_id} | Fee: {$fp->fees->name}\n";
    }
    exit;
}

// Get student
$student = Students::with(['user', 'class_section.class'])->find($childId);
if (!$student) {
    echo "❌ Student not found with Child ID: {$childId}\n";
    exit;
}

echo "Student: {$student->full_name}\n";
echo "Student User ID: {$student->user_id}\n";
echo "Class: {$student->class_section->class->name}\n";
echo "School ID: {$student->user->school_id}\n\n";

// Get fees
$fee = Fee::find($feesId);
if (!$fee) {
    echo "❌ Fee not found with ID: {$feesId}\n";
    exit;
}

echo "Fee: {$fee->name}\n";
echo "Fee Class ID: {$fee->class_id}\n\n";

// Check if fees_paid record exists
echo str_repeat("=", 80) . "\n";
echo "Checking FeesPaid Record\n";
echo str_repeat("=", 80) . "\n";

$feesPaid = FeesPaid::where([
    'fees_id' => $feesId,
    'student_id' => $student->user_id
])->first();

if ($feesPaid) {
    echo "✓ FeesPaid record found!\n\n";
    echo "Details:\n";
    echo "  ID: {$feesPaid->id}\n";
    echo "  Amount: ₹{$feesPaid->amount}\n";
    echo "  Date: {$feesPaid->date}\n";
    echo "  Is Fully Paid: " . ($feesPaid->is_fully_paid ? "Yes" : "No") . "\n";
    echo "  Transaction ID: " . ($feesPaid->transaction_id ?? 'N/A') . "\n\n";
    
    // Check compulsory fees
    $compulsoryFees = $feesPaid->compulsory_fee;
    echo "Compulsory Fees Records: " . $compulsoryFees->count() . "\n";
    foreach ($compulsoryFees as $cf) {
        echo "  - Amount: ₹{$cf->amount}, Status: " . ($cf->status == 1 ? "Success" : "Pending/Failed") . "\n";
    }
    
    // Check optional fees
    $optionalFees = $feesPaid->optional_fee;
    echo "\nOptional Fees Records: " . $optionalFees->count() . "\n";
    foreach ($optionalFees as $of) {
        echo "  - Amount: ₹{$of->amount}, Status: " . ($of->status == 1 ? "Success" : "Pending/Failed") . "\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "✓ Receipt API should work for this combination\n";
    echo "API Call: GET /api/parent/fees/receipt?child_id={$childId}&fees_id={$feesId}\n";
} else {
    echo "❌ No FeesPaid record found!\n\n";
    echo "This means:\n";
    echo "  - Either no payment has been made for this fee\n";
    echo "  - Or the payment was made but fees_paid record was not created\n";
    echo "  - Or wrong child_id/fees_id combination\n\n";
    
    // Check if any payment exists for this student
    $anyPayment = FeesPaid::where('student_id', $student->user_id)->count();
    echo "Total payments for this student: {$anyPayment}\n\n";
    
    if ($anyPayment > 0) {
        echo "Available fees_id for this student:\n";
        $availableFees = FeesPaid::where('student_id', $student->user_id)
            ->with('fees:id,name')
            ->get();
        
        foreach ($availableFees as $fp) {
            echo "  - Fees ID: {$fp->fees_id} | Fee: {$fp->fees->name} | Amount: ₹{$fp->amount}\n";
        }
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "❌ Receipt API will fail with error: 'No payment record found for this fee'\n";
}

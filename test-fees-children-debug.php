<?php
/**
 * Debug script for fees/children API
 * Tests payable amount calculation
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Students;
use App\Models\Fee;
use App\Models\FeesPaid;
use App\Models\CompulsoryFee;
use App\Models\OptionalFee;
use App\Services\CachingService;
use Illuminate\Support\Facades\DB;

echo "=== Fees Children API Debug ===\n\n";

// Get student ID from command line
$studentId = $argv[1] ?? null;

if (!$studentId) {
    echo "Usage: php test-fees-children-debug.php <student_id>\n";
    echo "Example: php test-fees-children-debug.php 123\n\n";
    exit;
}

// Get student
$student = Students::with(['user', 'class_section.class'])->find($studentId);
if (!$student) {
    echo "❌ Student not found with ID: {$studentId}\n";
    exit;
}

echo "Student: {$student->full_name}\n";
echo "Class: {$student->class_section->class->name}\n";
echo "School ID: {$student->user->school_id}\n\n";

$classId = $student->class_section->class_id;
$schoolId = $student->user->school_id;
$cache = app(CachingService::class);
$currentSessionYear = $cache->getDefaultSessionYear($schoolId);

echo "Session Year: {$currentSessionYear->name}\n\n";

// Get all fees for this student's class
$fees = Fee::where('class_id', $classId)
    ->where('session_year_id', $currentSessionYear->id)
    ->with([
        'fees_class_type',
        'fees_paid' => function ($query) use ($student) {
            $query->where('student_id', $student->user_id)
                ->with(['compulsory_fee', 'optional_fee']);
        }
    ])
    ->get();

echo "Total Fees Groups: " . $fees->count() . "\n";
echo str_repeat("=", 80) . "\n\n";

$grandTotalFees = 0;
$grandTotalPaid = 0;

foreach ($fees as $index => $fee) {
    echo "Fee Group #" . ($index + 1) . ": {$fee->name}\n";
    echo str_repeat("-", 80) . "\n";
    
    // Get compulsory and optional fees amount
    $compulsoryAmount = $fee->fees_class_type->where('optional', 0)->sum('amount');
    $optionalAmount = $fee->fees_class_type->where('optional', 1)->sum('amount');
    $totalFeesAmount = $compulsoryAmount + $optionalAmount;
    
    echo "Compulsory Fees: ₹{$compulsoryAmount}\n";
    echo "Optional Fees: ₹{$optionalAmount}\n";
    echo "Total Fees: ₹{$totalFeesAmount}\n\n";
    
    // Check if already paid
    $feesPaid = $fee->fees_paid->first();
    
    if ($feesPaid) {
        echo "Fees Paid Record Found (ID: {$feesPaid->id})\n";
        echo "  Amount in fees_paid table: ₹{$feesPaid->amount}\n";
        echo "  Is Fully Paid: " . ($feesPaid->is_fully_paid ? "Yes" : "No") . "\n\n";
        
        // Calculate paid amount from compulsory_fees table
        $compulsoryPaidRecords = $feesPaid->compulsory_fee;
        echo "  Compulsory Fee Records: " . $compulsoryPaidRecords->count() . "\n";
        
        $compulsoryPaidTotal = 0;
        foreach ($compulsoryPaidRecords as $cf) {
            $statusText = $cf->status == 1 ? "Success" : "Pending/Failed";
            echo "    - ID: {$cf->id}, Amount: ₹{$cf->amount}, Status: {$statusText}\n";
            if ($cf->status == 1) {
                $compulsoryPaidTotal += $cf->amount;
            }
        }
        echo "  Compulsory Paid (status=1): ₹{$compulsoryPaidTotal}\n\n";
        
        // Calculate paid amount from optional_fees table
        $optionalPaidRecords = $feesPaid->optional_fee;
        echo "  Optional Fee Records: " . $optionalPaidRecords->count() . "\n";
        
        $optionalPaidTotal = 0;
        foreach ($optionalPaidRecords as $of) {
            $statusText = $of->status == 1 ? "Success" : "Pending/Failed";
            echo "    - ID: {$of->id}, Amount: ₹{$of->amount}, Status: {$statusText}\n";
            if ($of->status == 1) {
                $optionalPaidTotal += $of->amount;
            }
        }
        echo "  Optional Paid (status=1): ₹{$optionalPaidTotal}\n\n";
        
        $totalPaidForThisFee = $compulsoryPaidTotal + $optionalPaidTotal;
        echo "Total Paid for this Fee Group: ₹{$totalPaidForThisFee}\n";
        
        $grandTotalPaid += $totalPaidForThisFee;
    } else {
        echo "❌ No payment record found\n";
        echo "Total Paid for this Fee Group: ₹0\n";
    }
    
    $grandTotalFees += $totalFeesAmount;
    
    $remaining = $totalFeesAmount - ($feesPaid ? ($compulsoryPaidTotal + $optionalPaidTotal) : 0);
    echo "Remaining for this Fee Group: ₹{$remaining}\n";
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
echo "SUMMARY\n";
echo str_repeat("=", 80) . "\n";
echo "Grand Total Fees: ₹{$grandTotalFees}\n";
echo "Grand Total Paid: ₹{$grandTotalPaid}\n";
echo "Total Due (Payable): ₹" . max(0, $grandTotalFees - $grandTotalPaid) . "\n\n";

echo "Expected in API Response:\n";
echo "  payment_due: " . max(0, $grandTotalFees - $grandTotalPaid) . "\n";

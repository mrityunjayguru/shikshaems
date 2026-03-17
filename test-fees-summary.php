<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Students;
use App\Models\Fee;
use App\Models\FeesPaid;
use App\Models\CompulsoryFee;
use App\Models\OptionalFee;
use App\Models\FeesClassType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "🧪 Testing Fees Summary API Calculation\n";
echo str_repeat("=", 80) . "\n\n";

// Get student ID from command line
$studentId = $argv[1] ?? null;
$schoolId = $argv[2] ?? 7;

if (!$studentId) {
    echo "❌ Usage: php test-fees-summary.php [student_id] [school_id]\n";
    echo "Example: php test-fees-summary.php 123 7\n\n";
    exit(1);
}

echo "👤 Student ID: {$studentId}\n";
echo "🏫 School ID: {$schoolId}\n\n";

try {
    // Set database connection
    $school = \App\Models\School::on('mysql')->where('id', $schoolId)->first();
    
    if (!$school) {
        echo "❌ School not found!\n";
        exit(1);
    }
    
    DB::setDefaultConnection('school');
    Config::set('database.connections.school.database', $school->database_name);
    DB::purge('school');
    DB::connection('school')->reconnect();
    DB::setDefaultConnection('school');
    
    echo "✅ Connected to database: {$school->database_name}\n\n";
    
    // Get student details
    $student = Students::where('user_id', $studentId)
        ->with('user', 'class_section.class')
        ->first();
    
    if (!$student) {
        echo "❌ Student not found!\n";
        exit(1);
    }
    
    echo "📋 Student Details:\n";
    echo "   Name: {$student->user->full_name}\n";
    echo "   Class: {$student->class_section->class->name}\n";
    echo "   Class ID: {$student->class_section->class_id}\n\n";
    
    // Get session year
    $sessionYear = app(\App\Services\CachingService::class)->getDefaultSessionYear($schoolId);
    echo "📅 Session Year: {$sessionYear->name} (ID: {$sessionYear->id})\n\n";
    
    // Get all fees for this class
    $fees = Fee::where('class_id', $student->class_section->class_id)
        ->where('session_year_id', $sessionYear->id)
        ->with([
            'fees_class_type.fees_type',
            'fees_paid' => function($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->with([
                        'compulsory_fee' => function($q) {
                            $q->where('status', 1);
                        },
                        'optional_fee' => function($q) {
                            $q->where('status', 1);
                        }
                    ]);
            }
        ])
        ->get();
    
    echo "💰 FEES CALCULATION BREAKDOWN:\n";
    echo str_repeat("=", 80) . "\n\n";
    
    $totalFees = 0;
    $totalPaid = 0;
    $totalCompulsoryFees = 0;
    $totalOptionalFees = 0;
    $totalCompulsoryPaid = 0;
    $totalOptionalPaid = 0;
    
    foreach ($fees as $fee) {
        echo "📌 Fee: {$fee->name} (ID: {$fee->id})\n";
        echo str_repeat("-", 80) . "\n";
        
        // Get compulsory and optional fees
        $compulsoryItems = $fee->fees_class_type->where('optional', 0);
        $optionalItems = $fee->fees_class_type->where('optional', 1);
        
        $compulsoryAmount = $compulsoryItems->sum('amount');
        $optionalAmount = $optionalItems->sum('amount');
        
        echo "\n   📊 Fee Structure:\n";
        echo "   ├─ Compulsory Fees:\n";
        foreach ($compulsoryItems as $item) {
            echo "   │  ├─ {$item->fees_type->name}: ₹{$item->amount}\n";
        }
        echo "   │  └─ Total Compulsory: ₹{$compulsoryAmount}\n";
        
        echo "   ├─ Optional Fees:\n";
        if ($optionalItems->count() > 0) {
            foreach ($optionalItems as $item) {
                echo "   │  ├─ {$item->fees_type->name}: ₹{$item->amount}\n";
            }
            echo "   │  └─ Total Optional: ₹{$optionalAmount}\n";
        } else {
            echo "   │  └─ No optional fees\n";
        }
        
        $totalCompulsoryFees += $compulsoryAmount;
        $totalOptionalFees += $optionalAmount;
        
        // Check payment
        $feesPaid = $fee->fees_paid->first();
        
        if ($feesPaid) {
            echo "\n   💳 Payment Details:\n";
            echo "   ├─ Payment Record ID: {$feesPaid->id}\n";
            echo "   ├─ Amount in fees_paid table: ₹{$feesPaid->amount}\n";
            
            // Compulsory fees paid
            $compulsoryPaidAmount = $feesPaid->compulsory_fee->sum('amount');
            $compulsoryPaidCount = $feesPaid->compulsory_fee->count();
            
            echo "   ├─ Compulsory Fees Paid:\n";
            echo "   │  ├─ Records: {$compulsoryPaidCount}\n";
            foreach ($feesPaid->compulsory_fee as $cf) {
                echo "   │  ├─ ID: {$cf->id}, Amount: ₹{$cf->amount}, Status: {$cf->status}, Type: {$cf->type}\n";
            }
            echo "   │  └─ Total: ₹{$compulsoryPaidAmount}\n";
            
            // Optional fees paid
            $optionalPaidAmount = $feesPaid->optional_fee->sum('amount');
            $optionalPaidCount = $feesPaid->optional_fee->count();
            
            echo "   ├─ Optional Fees Paid:\n";
            echo "   │  ├─ Records: {$optionalPaidCount}\n";
            if ($optionalPaidCount > 0) {
                foreach ($feesPaid->optional_fee as $of) {
                    $feesTypeName = $of->fees_class_type->fees_type->name ?? 'Optional Fee';
                    echo "   │  ├─ ID: {$of->id}, Type: {$feesTypeName}, Amount: ₹{$of->amount}, Status: {$of->status}\n";
                }
            }
            echo "   │  └─ Total: ₹{$optionalPaidAmount}\n";
            
            $totalPaidForThisFee = $compulsoryPaidAmount + $optionalPaidAmount;
            echo "   └─ Total Paid for this Fee: ₹{$totalPaidForThisFee}\n";
            
            $totalCompulsoryPaid += $compulsoryPaidAmount;
            $totalOptionalPaid += $optionalPaidAmount;
            $totalPaid += $totalPaidForThisFee;
        } else {
            echo "\n   ❌ No Payment Record Found\n";
        }
        
        echo "\n" . str_repeat("-", 80) . "\n\n";
    }
    
    echo "\n📊 FINAL SUMMARY:\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // Current API Logic (WRONG - includes optional in total)
    $wrongTotalFees = $totalCompulsoryFees + $totalOptionalFees;
    $wrongDue = $wrongTotalFees - $totalPaid;
    
    // Correct Logic (only compulsory in total)
    $correctTotalFees = $totalCompulsoryFees;
    $correctDue = $correctTotalFees - $totalPaid;
    
    echo "❌ CURRENT API CALCULATION (WRONG):\n";
    echo "   Total Fees (Compulsory + Optional): ₹{$wrongTotalFees}\n";
    echo "   Total Paid: ₹{$totalPaid}\n";
    echo "   Due: ₹{$wrongDue}\n\n";
    
    echo "✅ CORRECT CALCULATION (FIXED):\n";
    echo "   Total Compulsory Fees: ₹{$correctTotalFees}\n";
    echo "   Total Optional Fees: ₹{$totalOptionalFees} (not counted in total)\n";
    echo "   Total Paid (Compulsory + Optional): ₹{$totalPaid}\n";
    echo "   Due: ₹{$correctDue}\n\n";
    
    echo "📋 BREAKDOWN:\n";
    echo "   Compulsory Fees: ₹{$totalCompulsoryFees}\n";
    echo "   Compulsory Paid: ₹{$totalCompulsoryPaid}\n";
    echo "   Compulsory Due: ₹" . ($totalCompulsoryFees - $totalCompulsoryPaid) . "\n\n";
    
    echo "   Optional Fees Available: ₹{$totalOptionalFees}\n";
    echo "   Optional Fees Paid: ₹{$totalOptionalPaid}\n";
    echo "   Optional Fees Unpaid: ₹" . ($totalOptionalFees - $totalOptionalPaid) . "\n\n";
    
    echo str_repeat("=", 80) . "\n\n";
    
    // Analysis
    if ($wrongDue > 0 && $correctDue <= 0) {
        echo "⚠️  ISSUE CONFIRMED:\n";
        echo "   - Current API shows due: ₹{$wrongDue}\n";
        echo "   - But compulsory fees are fully paid!\n";
        echo "   - The due amount is from unpaid optional fees\n";
        echo "   - Optional fees should NOT be counted in total_fees\n";
        echo "   - Fix: Only count compulsory fees in total\n\n";
    } else if ($correctDue > 0) {
        echo "✅ LEGITIMATE DUE:\n";
        echo "   - Compulsory fees are not fully paid\n";
        echo "   - Due amount: ₹{$correctDue}\n\n";
    } else {
        echo "✅ ALL PAID:\n";
        echo "   - All compulsory fees are paid\n";
        echo "   - No due amount\n\n";
    }
    
    // API Response Format
    echo "📱 API RESPONSE FORMAT:\n";
    echo str_repeat("=", 80) . "\n";
    echo json_encode([
        'summary' => [
            'total_fees' => number_format($correctTotalFees, 2),
            'paid' => number_format($totalPaid, 2),
            'due' => number_format($correctDue, 2)
        ],
        'breakdown' => [
            'compulsory_fees' => number_format($totalCompulsoryFees, 2),
            'compulsory_paid' => number_format($totalCompulsoryPaid, 2),
            'optional_fees_available' => number_format($totalOptionalFees, 2),
            'optional_paid' => number_format($totalOptionalPaid, 2)
        ]
    ], JSON_PRETTY_PRINT) . "\n";
    echo str_repeat("=", 80) . "\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}

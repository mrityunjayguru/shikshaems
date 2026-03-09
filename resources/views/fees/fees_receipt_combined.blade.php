<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        .receipt-container { max-width: 800px; margin: 30px auto; background: #fff; border: 2px solid #000; padding: 20px; font-size: 13px; color: #000; }
        .receipt-header { border-bottom: 2px solid #000; padding-bottom: 10px; }
        .receipt-title { text-align: center; margin: 15px 0; }
        .receipt-title span { border: 1px solid #000; padding: 5px 15px; font-weight: bold; }
        .info-row { border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        table th { text-align: left; }
        .text-right { text-align: right; }
        .footer-note { border-top: 1px solid #000; margin-top: 10px; padding-top: 8px; font-size: 12px; text-align: center; }
        .signature { text-align: right; margin-top: 30px; font-weight: bold; }
        table.receipt-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .receipt-table th { border-bottom: 1px solid #000; padding: 6px; text-align: left; font-weight: bold; }
        .receipt-table td { padding: 6px; vertical-align: top; }
        .receipt-table .total-row td { border-top: 1px solid #000; font-weight: bold; }
        .small-text { font-size: 12px; }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fees Receipt || {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="receipt-container">
        <table width="100%" class="receipt-header">
            <tr>
                <td width="15%" align="left">
                    @if (!empty($schoolVerticalLogo) && $schoolVerticalLogo->data)
                        <img src="{{ public_path('storage/') . $schoolVerticalLogo->data }}" alt="School Logo" width="80">
                    @elseif(!empty($systemVerticalLogo) && $systemVerticalLogo->data)
                        <img src="{{ public_path('storage/') . $systemVerticalLogo->data }}" alt="Logo" width="80">
                    @endif
                </td>
                <td width="60%" align="center">
                    <strong style="font-size: 18px;">{{ $school['school_name'] ?? 'SCHOOL NAME' }}</strong><br>
                    <small>{{ $school['school_address'] ?? 'School Address' }}</small><br>
                    <small>(Affiliated to CBSE, School Code: {{ $school['school_code'] ?? 'XXXXX' }})</small>
                </td>
                <td width="25%" align="right">
                    <strong>Contact</strong><br>
                    {{ $school['school_phone'] ?? '+91 XXXXX XXXXX' }}
                </td>
            </tr>
        </table>
        <div class="receipt-title"><span>FEE RECEIPT</span></div>
        <table width="100%" class="info-row">
            <tr>
                <td width="50%" valign="top">
                    <strong>Receipt No:</strong> {{ $paymentTransaction->id ?? 'N/A' }}<br>
                    <strong>Name:</strong> {{ $student->user->full_name }}<br>
                    <strong>Admission No:</strong> {{ $student->admission_no ?? '' }}<br>
                    <strong>Class & Section:</strong> {{ $student->class_section->full_name }}
                </td>
                <td width="50%" valign="top">
                    <strong>Contact:</strong> {{ $student->user->mobile ?? $student->guardian->mobile }}<br>
                    <strong>Guardian Name:</strong> {{ $student->guardian->first_name . ' ' . $student->guardian->last_name ?? 'N/A' }}<br>
                    <strong>Address:</strong> {{ $student->user->current_address ?? 'N/A' }}<br>
                </td>
            </tr>
        </table>
        <table class="receipt-table mb-3">
            <thead>
                <tr>
                    <th colspan="2">Particulars</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_fees = 0;
                    $due_charges = 0;
                @endphp
                @foreach ($feesPaidRecords as $feesPaid)
                    <tr>
                        <td colspan="3" style="background-color: #f0f0f0; font-weight: bold; padding: 8px 6px;">
                            {{ $feesPaid->fees->name ?? 'Fee' }}
                        </td>
                    </tr>
                    @foreach ($feesPaid->compulsory_fee ?? [] as $compulsoryFee)
                        @php
                            $total_fees += $compulsoryFee->amount;
                            $due_charges += $compulsoryFee->due_charges ?? 0;
                        @endphp
                        <tr>
                            <td colspan="2">
                                {{ $compulsoryFee->installment_fee->name ?? 'Compulsory Fee' }}
                                @if ($compulsoryFee->type)
                                    <small>({{ $compulsoryFee->type }})</small>
                                @endif
                                @if ($compulsoryFee->due_charges > 0)
                                    <br><small class="small-text">Due Charges: ₹{{ $compulsoryFee->due_charges }}</small>
                                @endif
                            </td>
                            <td class="text-right">₹{{ number_format($compulsoryFee->amount + ($compulsoryFee->due_charges ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                    @foreach ($feesPaid->optional_fee ?? [] as $optionalFee)
                        @php
                            $total_fees += $optionalFee->amount;
                        @endphp
                        <tr>
                            <td colspan="2">{{ $optionalFee->fees_class_type->fees_type->name ?? 'Optional Fee' }}</td>
                            <td class="text-right">₹{{ number_format($optionalFee->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="total-row">
                    <td colspan="2"><strong>Total Amount</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($total_fees + $due_charges, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        <table width="100%" style="margin-top: 15px; border-top: 1px solid #000; padding-top: 10px;">
            <tr>
                <td width="50%" valign="top">
                    <strong>Payment Details:</strong><br>
                    <strong>Transaction ID:</strong> {{ $paymentTransaction->order_id ?? $paymentTransaction->payment_id ?? 'N/A' }}<br>
                    <strong>Date:</strong> {{ $paymentTransaction->created_at ? date('d M Y', strtotime($paymentTransaction->created_at)) : date('d M Y') }}<br>
                </td>
                <td width="50%" valign="top" class="text-right">
                    <strong>Total Paid:</strong> ₹{{ number_format($total_fees + $due_charges, 2) }}
                </td>
            </tr>
        </table>
        <div class="signature">Authorized Signature</div>
        <div class="footer-note">This is a computer-generated receipt and does not require a signature.</div>
    </div>
</body>
</html>

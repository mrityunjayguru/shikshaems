<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
    <style>
        .receipt-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border: 2px solid #000;
            padding: 20px;
            font-size: 13px;
            color: #000;
        }

        .receipt-header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .receipt-title {
            text-align: center;
            margin: 15px 0;
        }

        .receipt-title span {
            border: 1px solid #000;
            padding: 5px 15px;
            font-weight: bold;
        }

        .info-row {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer-note {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 8px;
            font-size: 12px;
            text-align: center;
        }

        .signature {
            text-align: right;
            margin-top: 30px;
            font-weight: bold;
        }

        table.receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .receipt-table th {
            border-bottom: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-weight: bold;
        }

        .receipt-table td {
            padding: 6px;
            vertical-align: top;
        }

        .receipt-table .amount {
            text-align: right;
            white-space: nowrap;
        }

        .receipt-table .total-row td {
            border-top: 1px solid #000;
            font-weight: bold;
        }

        .small-text {
            font-size: 12px;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fees Receipt || {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="receipt-container">

        {{-- HEADER --}}
        <table width="100%" class="gap-3">
            <tr>
                <td width="25%">
                    @if ($school['horizontal_logo'] ?? '')
                        <img src="{{ public_path('storage/' . $school['horizontal_logo']) }}"
                            style="height:30px; margin-bottom:2px;">
                    @endif
                </td>
               
                <td width="50%" align="center">
                    <strong style="font-size:18px;">{{ $school['school_name'] }}</strong><br>
                    <small>{{ $school['school_address'] }}</small><br>
                    <small>(Affiliated to CBSE, School Code: {{ $school['school_code'] ?? 'XXXXX' }})</small>
                </td>

                <td width="25%" align="right">
                    <strong>Contact</strong><br>
                    {{ $school['school_phone'] ?? '+91 XXXXX XXXXX' }}
                </td>
            </tr>
        </table>

        {{-- TITLE --}}
        <div class="receipt-title">
            <span>FEE RECEIPT</span>
        </div>

        {{-- RECEIPT META --}}
        <table width="100%" class="info-row">
            <tr>
                <td width="50%" valign="top">
                    <strong>Receipt Date:</strong> {{ $paymentTransaction->created_at ? date('d M Y h:i A', strtotime($paymentTransaction->created_at)) : date('d M Y h:i A') }}
                </td>

                <td width="50%" valign="top">
                    <strong>Fee for the month of:</strong> -
                </td>
            </tr>
        </table>
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
                    <strong>Guardian Name:</strong>
                    {{ $student->guardian->first_name . ' ' . $student->guardian->last_name ?? 'N/A' }}<br>
                    <strong>Address:</strong> {{ $student->user->current_address ?? 'N/A' }}<br>
                </td>
            </tr>
        </table>
        {{-- TABLE --}}
        <table class="receipt-table mb-3">
            <thead>
                <tr>
                    <th colspan="2">PARTICULARS</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $total_fees = 0;
                    $due_charges = 0;
                @endphp

                {{-- Combined Fees from all records --}}
                @foreach ($feesPaidRecords as $feesPaid)
                    @php
                        $compulsoryFeesType = $feesPaid->fees->compulsory_fees->pluck('fees_type_name')->implode(', ');
                    @endphp

                    {{-- Compulsory Fees --}}
                    @foreach ($feesPaid->compulsory_fee ?? [] as $compulsoryFee)
                        <tr>
                            <td colspan="2">
                                <strong>{{ $compulsoryFee->type }}</strong><br>
                                <span class="small-text">( {{ $compulsoryFeesType }} )</span><br>
                                <span class="small-text">Payment Mode : ({{ $compulsoryFee->mode }})</span>
                            </td>
                            <td class="amount">
                                {{ $school['currency_symbol'] ?? '' }}
                                {{ number_format($compulsoryFee->amount) }}
                            </td>
                        </tr>

                        @php
                            $total_fees += $compulsoryFee->amount;
                        @endphp

                        @if (!empty($compulsoryFee->due_charges))
                            <tr>
                                <td colspan="2">Due Charges</td>
                                <td class="amount">
                                    {{ $school['currency_symbol'] ?? '' }}
                                    {{ number_format($compulsoryFee->due_charges) }}
                                </td>
                            </tr>
                            @php
                                $due_charges += $compulsoryFee->due_charges;
                            @endphp
                        @endif
                    @endforeach

                    {{-- Optional Fees --}}
                    @foreach ($feesPaid->optional_fee ?? [] as $optionalFee)
                        <tr>
                            <td colspan="2">
                                {{ $optionalFee->fees_class_type->fees_type->name ?? $optionalFee->fees_class_type->fees_type_name }}
                                <span class="small-text">(Optional)</span><br>
                                <span class="small-text">Mode : ({{ $optionalFee->mode }})</span>
                            </td>
                            <td class="amount">
                                {{ $school['currency_symbol'] ?? '' }}
                                {{ number_format($optionalFee->amount) }}
                            </td>
                        </tr>

                        @php
                            $total_fees += $optionalFee->amount;
                        @endphp
                    @endforeach
                @endforeach

                {{-- Total --}}
                <tr class="total-row">
                    <td colspan="2">Total Amount</td>
                    <td class="amount">
                        {{ $school['currency_symbol'] ?? '' }}
                        {{ number_format($total_fees + $due_charges) }}
                    </td>
                </tr>
                @if ($advanceAmount != 0)
                    <tr class="total-row">
                        <td colspan="2">Advance Amount</td>
                        <td class="amount">
                            {{ $school['currency_symbol'] ?? '' }}
                            {{ number_format($advanceAmount) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td width="50%" valign="top">
                        <strong>Discount Type:</strong> NA<br>
                        <strong>Discount Fee:</strong> 0<br>
                        <strong>Transaction ID:</strong> {{ $paymentTransaction->order_id ?? ($paymentTransaction->payment_id ?? '-') }}<br>
                        <strong>Date:</strong> {{ $paymentTransaction->created_at ? date('Y-m-d', strtotime($paymentTransaction->created_at)) : date('Y-m-d') }}<br>
                    </td>
                </tr>
            </tbody>
        </table>
        
        {{-- FOOTER --}}
        <p class="mt-2">
            Received with thanks
            <strong>{{ $school['currency_symbol'] ?? '' }}{{ number_format($total_fees + $due_charges) }}</strong>
        </p>

        <div class="signature">
            ( Cashier / Accountant )
        </div>

        <div class="footer-note">
            This is a computer-generated receipt and does not require a signature.
        </div>

    </div>
</body>

</html>

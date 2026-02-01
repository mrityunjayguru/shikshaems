<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* * {
            font-family: Mulish, sans-serif;
        } */
        * {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
    <style>
        /* body {
            background: #f5f5f5;
        } */

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

        /* table th,
        table td {
            border-bottom: 1px solid #000;
            padding: 6px;
        } */

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
        {{-- <div class="receipt-header">
            <div class="row align-items-center text-center">

                <div class="col-md-4">
                    @if ($school['horizontal_logo'] ?? '')
                        <img src="{{ public_path('storage/' . $school['horizontal_logo']) }}" style="height:60px;">
                    @else
                        <img src="{{ public_path('assets/horizontal-logo2.svg') }}" style="height:60px;">
                    @endif
                </div>

                <div class="col-md-4">
                    <strong style="font-size:18px">{{ $school['school_name'] }}</strong><br>
                    <small>{{ $school['school_address'] }}</small><br>
                    <small>(Affiliated to CBSE, School Code: XXXXX)</small>
                </div>

                <div class="col-md-4">
                    <strong>Contact</strong><br>
                    {{ $school['phone'] ?? '+91 XXXXX XXXXX' }}
                </div>

            </div>
        </div> --}}
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
        {{-- <div class="row info-row">
            <div class="col-md-6">
                <strong>Receipt Date:</strong> {{ date('d M Y h:i A') }}<br>
                <strong>Receipt No:</strong> {{ $feesPaid->id }}<br>
                <strong>Name:</strong> {{ $student->user->full_name }}<br>
                <strong>Admission No:</strong> {{ $student->admission_no ?? '' }}<br>
                <strong>Class & Section:</strong> {{ $student->class_section->full_name }}
            </div>

            <div class="col-md-6">
                <strong>Payment Mode:</strong> {{ $feesPaid->payment_mode ?? 'Online' }}<br>
                <strong>Transaction ID:</strong> {{ $feesPaid->transaction_id ?? '-' }}<br>
                <strong>Month:</strong> January
            </div>
        </div> --}}
        <table width="100%" class="info-row">
            <tr>
                <td width="50%" valign="top">
                    <strong>Receipt Date:</strong> {{ date('d M Y h:i A') }}
                </td>

                <td width="50%" valign="top">
                    <strong>Fee for the month of:</strong> January
                </td>
            </tr>
        </table>
        <table width="100%" class="info-row">
            <tr>
                <td width="50%" valign="top">
                    <strong>Receipt No:</strong> {{ $feesPaid->id }}<br>
                    <strong>Name:</strong> {{ $student->user->full_name }}<br>
                    <strong>Admission No:</strong> {{ $student->admission_no ?? '' }}<br>
                    <strong>Class & Section:</strong> {{ $student->class_section->full_name }}
                </td>

                <td width="50%" valign="top">
                    <strong>Contact:</strong> {{ $student->user->mobile ?? $student->guardian->mobile }}<br>
                    <strong>Gaurdian Name:</strong>
                    {{ $student->guardian->first_name . ' ' . $student->guardian->last_name ?? 'N/A' }}<br>
                    <strong>Address:</strong> {{ $student->user->current_address ?? 'N/A' }}<br>
                    {{-- <strong>Payment Mode:</strong> {{ $feesPaid->payment_mode ?? 'Online' }}<br>
                    <strong>Transaction ID:</strong> {{ $feesPaid->transaction_id ?? '-' }}<br> --}}
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
                            {{ $optionalFee->fees_class_type->fees_type_name }}
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

                {{-- Total --}}
                <tr class="total-row">
                    <td colspan="2">Total Amount</td>
                    <td class="amount">
                        {{ $school['currency_symbol'] ?? '' }}
                        {{ number_format($total_fees + $due_charges) }}
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2">Advance Amount</td>
                    <td class="amount">
                        {{ $school['currency_symbol'] ?? '' }}
                        {{ number_format($advanceAmount) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            {{-- <thead>
                <tr>
                    <th>PARTICULARS</th>
                    <th class="text-right">DUES</th>
                    <th class="text-right">BALANCE</th>
                </tr>
            </thead> --}}

            <tbody>
                <tr>
                    <td width="50%" valign="top">
                        <strong>Discount Type:</strong> NA<br>
                        <strong>Discount Fee:</strong> 0<br>
                        {{-- <strong>Payment Mode:</strong> {{ $feesPaid->payment_mode ?? 'Online' }}<br> --}}
                        {{-- <strong>Bank:</strong> {{ $feesPaid->payment_mode ?? 'Online' }}<br> --}}
                        <strong>Transaction ID:</strong> {{ $feesPaid->transaction_id ?? '-' }}<br>
                        <strong>Date:</strong> {{ $feesPaid->date ?? '-' }}<br>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- FOOTER --}}
        <p class="mt-2">
            Received with thanks <strong>{{ $school['currency_symbol'] ?? '' }}{{ $total_fees + $due_charges }}</strong>
           
        </p>
     
        <div class="signature">
            ( Cashier / Accountant )
        </div>

        <div class="footer-note">
            The fee is to be deposited on or before {{ $feesPaid->fees->due_date }} .
            After {{ $feesPaid->fees->due_date }} fine of Rs. {{ $feesPaid->fees->due_charges_amount }}- per day.
        </div>

    </div>
</body>

</html>

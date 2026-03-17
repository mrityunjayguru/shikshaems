<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Fees Receipt</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
        }

        .receipt-container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 60px;
            margin-bottom: 8px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .header small {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #999;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        .summary {
            margin-top: 20px;
            border-top: 2px solid #444;
            padding-top: 10px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="header">
            @if ($school['horizontal_logo'] ?? '')
                <img src="{{ public_path('storage/') . $school['horizontal_logo'] }}" alt="School Logo">
            @else
                <img src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="School Logo">
            @endif
            <h2>{{ $school['school_name'] ?? '' }}</h2>
            <small>{{ $school['school_address'] ?? '' }}</small><br>
            <h3>Fees Receipt</h3>
        </div>

        <!-- Student Details -->
        <table>
            <tr>
                <th>Receipt No.</th>
                <td>{{ $feesPaid->id ?? 'N/A' }}</td>
                <th>Date</th>
                <td>{{ date('d-m-Y h:i A') }}</td>
            </tr>
            <tr>
                <th>Student Name</th>
                <td>{{ $student->user->full_name }}</td>
                <th>Admission No.</th>
                <td>{{ $student->admission_no ?? '' }}</td>
            </tr>
            <tr>
                <th>Class</th>
                <td>{{ $student->class_section->class->name }}</td>
                <th>Section</th>
                <td>{{ $student->class_section->section->name ?? '' }}</td>
            </tr>
            <tr>
                <th>Contact</th>
                <td>{{ $student->user->mobile ?? $student->guardian->mobile }}</td>
                <th>Guardian Name</th>
                <td>{{ $student->guardian->first_name . ' ' . $student->guardian->last_name ?? 'N/A' }}</td>
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
                        <strong>Date:</strong> {{ $feesPaid->date ?? date('Y-m-d') }}<br>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- FOOTER --}}
        <p class="mt-2">
            Received with thanks
            <strong>{{ $school['currency_symbol'] ?? '' }}{{ $total_fees + $due_charges }}</strong>

        </p>

        <div class="signature">
            ( Cashier / Accountant )
        </div>

        <div class="footer-note">
            The fee is to be deposited on or before {{ $feesPaid->fees->due_date ?? 'N/A' }} .
            After {{ $feesPaid->fees->due_date ?? 'N/A' }} fine of Rs. {{ $feesPaid->fees->due_charges_amount ?? 0 }}- per day.
        </div>

    </div>
</body>

</html>

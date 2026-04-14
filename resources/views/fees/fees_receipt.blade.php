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

        <table width="100%" class="gap-3">
            <tr>
                <td width="25%">
                    @if ($school['horizontal_logo'] ?? '')
                        <img src="{{ public_path('storage/' . $school['horizontal_logo']) }}"
                            style="height:30px; margin-bottom:2px;">
                    @endif
                </td>

                <td width="50%" align="center">
                    <div style="font-size:18px; font-weight:bold; margin-bottom:2px;">{{ $school['school_name'] }}</div>
                    <div style="font-size:11px; margin-bottom:1px;">{{ $school['school_address'] }}</div>
                    <div style="font-size:11px;">(Affiliated to CBSE, School Code: {{ $school['school_code'] ?? 'XXXXX' }})</div>
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
                    <strong>Receipt Date:</strong> {{ $feesPaid->date ? \Carbon\Carbon::parse($feesPaid->date)->format('d M Y') : '-' }}
                </td>
                <td width="50%" valign="top">
                    @php
                        $currency = $school['currency_symbol'] ?? '';

                        $monthNames = [
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ];

                        $short = fn($n) => substr($n, 0, 3);

                        $headerMonths = '';

                        /*
                |--------------------------------------------------------------------------
                | 1) First check compulsory fee months
                |--------------------------------------------------------------------------
                */
                        $allMonths = collect();

                        foreach ($feesPaid->compulsory_fee ?? [] as $cf) {
                            if (!empty($cf->months) && $cf->months->count()) {
                                $allMonths = $allMonths->merge($cf->months);
                            }
                        }

                        $allMonths = $allMonths->sortBy('month_number');

                        if ($allMonths->count()) {
                            $grouped = $allMonths
                                ->groupBy('month_number')
                                ->map(function ($records) {
                                    $hasFull = $records->contains('is_partial', false);

                                    return (object) [
                                        'month_number' => $records->first()->month_number,
                                        'month_name' => $records->first()->month_name,
                                        'is_partial' => !$hasFull,
                                        'amount' => $records->sum('amount'),
                                    ];
                                })
                                ->sortBy('month_number');

                            $fullMonths = $grouped->where('is_partial', false);
                            $partialMonth = $grouped->where('is_partial', true)->last();

                            if ($fullMonths->count() >= 2) {
                                $r =
                                    $short($fullMonths->first()->month_name) .
                                    ' to ' .
                                    $short($fullMonths->last()->month_name);
                            } elseif ($fullMonths->count() === 1) {
                                $r = $short($fullMonths->first()->month_name);
                            } else {
                                $r = '';
                            }

                            $p = $partialMonth
                                ? $short($partialMonth->month_name) .
                                    ' (Partial ' .
                                    $currency .
                                    number_format($partialMonth->amount) .
                                    ')'
                                : '';

                            $headerMonths = $r && $p ? $r . ' and ' . $p : ($r ?: $p);
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | 2) If no compulsory months found, check optional fee months
                        |--------------------------------------------------------------------------
                        */
                        if (empty($headerMonths)) {
                            $optionalMonths = collect();

                            foreach ($feesPaid->optional_fee ?? [] as $optionalFee) {
                                $rawMonths = $optionalFee->fees_class_type->applicable_months ?? '[]';
                                $months = is_array($rawMonths) ? $rawMonths : json_decode($rawMonths, true);

                                if (!empty($months)) {
                                    foreach ($months as $monthNo) {
                                        $monthNo = (int) $monthNo;

                                        if (isset($monthNames[$monthNo])) {
                                            $optionalMonths->push($monthNames[$monthNo]);
                                        }
                                    }
                                }
                            }

                            $optionalMonths = $optionalMonths->unique()->values()->toArray();

                            if (!empty($optionalMonths)) {
                                $headerMonths = implode(', ', $optionalMonths);
                            }
                        }
                    @endphp

                    <strong>Fee for the month of:</strong> {{ $headerMonths ?: '-' }}
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
                    $compulsoryFeesType = $feesPaid->fees->compulsory_fees->pluck('fees_type_name')->implode(', ');
                @endphp

                {{-- Compulsory Fees --}}
                @foreach ($feesPaid->compulsory_fee ?? [] as $compulsoryFee)
                    <tr>
                        <td colspan="2">
                            <strong>{{ $compulsoryFee->type }}</strong><br>
                            <span class="small-text">( {{ $compulsoryFeesType }} )</span><br>
                            <span class="small-text">Payment Mode : ({{ $compulsoryFee->mode }})</span>
                            @if ($compulsoryFee->months && $compulsoryFee->months->count())
                                @php
                                    $fullMonths = $compulsoryFee->months
                                        ->where('is_partial', false)
                                        ->sortBy('month_number');
                                    $partialMonth = $compulsoryFee->months->where('is_partial', true)->first();
                                    $currency = $school['currency_symbol'] ?? '';
                                    $short = fn($n) => substr($n, 0, 3);

                                    if ($fullMonths->count() >= 2) {
                                        $rangeStr =
                                            $short($fullMonths->first()->month_name) .
                                            ' to ' .
                                            $short($fullMonths->last()->month_name);
                                    } elseif ($fullMonths->count() === 1) {
                                        $rangeStr = $short($fullMonths->first()->month_name);
                                    } else {
                                        $rangeStr = '';
                                    }

                                    $partialStr = $partialMonth
                                        ? $short($partialMonth->month_name) .
                                            ' (Partial ' .
                                            $currency .
                                            number_format($partialMonth->amount) .
                                            ')'
                                        : '';

                                    $coveredStr =
                                        $rangeStr && $partialStr
                                            ? $rangeStr . ' and ' . $partialStr
                                            : ($rangeStr ?:
                                            $partialStr);
                                @endphp
                                <br><span class="small-text">Months : <strong>{{ $coveredStr }}</strong></span>
                            @endif
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
                            @php
                                $rawMonths = $optionalFee->fees_class_type->applicable_months ?? null;
                                $months = is_array($rawMonths) ? $rawMonths : json_decode($rawMonths, true);
                                $monthNames = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                ];

                                $selectedMonths = [];

                                if (!empty($months)) {
                                    foreach ($months as $m) {
                                        $selectedMonths[] = $monthNames[(int) $m] ?? '';
                                    }
                                }
                            @endphp
                            @if (!empty($selectedMonths))
                                <br><span class="small-text">
                                    Months: <strong>{{ implode(', ', $selectedMonths) }}</strong>
                                </span>
                            @endif

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

                {{-- Transportation Fee --}}
                @if(!empty($feesPaid->transportation_payment))
                @php
                    $tp = $feesPaid->transportation_payment;
                    $total_fees += $tp->amount;
                @endphp
                <tr>
                    <td colspan="2">
                        <strong>Transportation Fee</strong><br>
                        @if($tp->pickupPoint)
                            <span class="small-text">Pickup Point: {{ $tp->pickupPoint->name }}</span><br>
                        @endif
                        @if($tp->routeVehicle)
                            <span class="small-text">Vehicle/Route: {{ $tp->routeVehicle->vehicle->name ?? '' }}</span><br>
                        @endif
                        <span class="small-text">Date: {{ $tp->paid_at ? date('d-m-Y', strtotime($tp->paid_at)) : '-' }}</span>
                    </td>
                    <td class="amount">
                        {{ $school['currency_symbol'] ?? '' }}
                        {{ number_format($tp->amount) }}
                    </td>
                </tr>
                @endif

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
                        <strong>Transaction ID:</strong> {{ $feesPaid->transaction_id ?? '-' }}<br>
                        <strong>Date:</strong> {{ $feesPaid->date ? \Carbon\Carbon::parse($feesPaid->date)->format('d M Y') : '-' }}<br>
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
            The fee is to be deposited on or before {{ $feesPaid->fees->due_date }} .
            After {{ $feesPaid->fees->due_date }} fine of Rs. {{ $feesPaid->fees->due_charges_amount }}- per day.
        </div>

    </div>
</body>

</html>

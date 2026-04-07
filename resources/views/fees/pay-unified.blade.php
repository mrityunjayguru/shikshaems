@extends('layouts.master')

@section('title')
    {{ __('Pay Fees') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('Pay Fees') }}</h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $student->full_name }} — {{ $student->student->class_section->full_name }}</h5>
                        <a href="{{ route('fees.paid.index') }}" class="btn btn-theme btn-sm">{{ __('back') }}</a>
                    </div>
                    <div class="card-body">
                        <form class="create-form form-validation" method="post" action="{{ route('fees.unified.store') }}"
                            data-success-function="successFunction" novalidate="novalidate">
                            @csrf
                            <input type="hidden" name="fees_id" value="{{ $fees->id }}">
                            <input type="hidden" name="student_id" value="{{ $student->id }}">

                            {{-- Date & Mode --}}
                            <div class="row mb-3">
                                {{-- <div class="form-group col-md-4">
                                <label>{{ __('date') }} <span class="text-danger">*</span></label>
                                <input type="text" name="date" class="datepicker-popup form-control" placeholder="{{ __('date') }}" autocomplete="off" required>
                            </div> --}}
                                <div class="form-group col-md-4">
                                    <label>{{ __('Mode') }} <span class="text-danger">*</span></label>
                                    <select name="mode" id="mode" class="form-control" required>
                                        <option value="Cash">{{ __('cash') }}</option>
                                        <option value="Cheque">{{ __('cheque') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="cheque-no-wrap" style="display:none">
                                    <label>{{ __('cheque_no') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="cheque_no" class="form-control"
                                        placeholder="{{ __('cheque_no') }}">
                                </div>
                                {{-- Enter Amount --}}
                                <div class="form-group col-md-4">
                                    <label>{{ __('Enter Amount') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="enter_amount" id="enter_amount" class="form-control"
                                        min="0" step="0.01" required placeholder="0.00">
                            <small class="text-muted">Amount will be applied: Compulsory → Transportation →
                                        Optional</small>
                                    <small class="text-info d-block" id="total-available-hint"></small>
                                </div>

                            </div>
                            @if ($student->fees_advances_sum_amount)
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>{{ __('Advance Amount') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" disabled placeholder="0.00"
                                            value="{{ $student->fees_advances_sum_amount }}">
                                        {{-- <small class="text-muted">Amount will be applied: Compulsory → Transportation →
                                        Optional</small> --}}
                                    </div>
                                </div>
                            @endif

                            <hr>

                            {{-- 1. COMPULSORY FEES --}}
                            <h5 class="mb-2">{{ __('Compulsory Fees') }}</h5>
                            @php
                                $totalCompulsory = $fees->total_compulsory_fees;
                                $alreadyPaid = $student->fees_paid->amount ?? 0;
                                $compulsoryDue = max(0, $totalCompulsory - $alreadyPaid);
                            @endphp
                            <table class="table table-bordered mb-3">
                                <thead>
                                    <tr>
                                        <th>{{ __('Fee Type') }}</th>
                                        <th class="text-right">{{ __('Yearly Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fees->compulsory_fees as $cf)
                                        <tr>
                                            <td>{{ $cf->fees_type_name }}</td>
                                            <td class="text-right">{{ $currencySymbol }}
                                                {{ number_format($cf->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td><strong>{{ __('Total') }}</strong></td>
                                        <td class="text-right"><strong>{{ $currencySymbol }}
                                                {{ number_format($totalCompulsory, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Already Paid') }}</td>
                                        <td class="text-right text-success">{{ $currencySymbol }}
                                            {{ number_format($alreadyPaid, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Remaining') }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $currencySymbol }}
                                                {{ number_format($compulsoryDue, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- 2. TRANSPORTATION FEE --}}
                            <h5 class="mb-2">{{ __('Transportation Fee') }}</h5>
                            @if ($transportRequest && !$transportPaid)
                                @php $transportAmount = $transportRequest->transportationFee->fee_amount ?? 0;
                                $partialPaid = $transportPartial->amount ?? 0;
                                $transportDue = max(0, $transportAmount - $partialPaid);
                                @endphp
                                <table class="table table-bordered mb-3">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('Pickup Point') }}</td>
                                            <td class="text-right">{{ $transportRequest->pickupPoint->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Fee Amount') }}</td>
                                            <td class="text-right">{{ $currencySymbol }} {{ number_format($transportAmount, 2) }}</td>
                                        </tr>
                                        @if($partialPaid > 0)
                                        <tr>
                                            <td>{{ __('Already Paid') }}</td>
                                            <td class="text-right text-success">{{ $currencySymbol }} {{ number_format($partialPaid, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>{{ __('Remaining') }}</strong></td>
                                            <td class="text-right text-danger"><strong>{{ $currencySymbol }} {{ number_format($transportDue, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @elseif($transportPaid)
                                <div class="alert alert-success py-2">{{ __('Transportation fee already paid') }}</div>
                            @else
                                <div class="alert alert-secondary py-2">{{ __('No transportation request found') }}</div>
                            @endif

                            {{-- 3. OPTIONAL FEES --}}
                            @if ($optionalFeesData->count())
                                <h5 class="mb-2">{{ __('Optional Fees') }}</h5>
                                <table class="table table-bordered mb-3">
                                    <thead>
                                        <tr>
                                            <th width="40px"></th>
                                            <th>{{ __('Fee Type') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($optionalFeesData as $opt)
                                            @php
                                                $isPaid    = $opt->optional_fees_paid->whereIn('status', ['paid','Success'])->isNotEmpty();
                                                $isPartial = !$isPaid && $opt->optional_fees_paid->where('status', 'partial')->isNotEmpty();
                                                $partialAmt = $isPartial ? $opt->optional_fees_paid->where('status', 'partial')->first()->amount : 0;
                                                $optDue     = max(0, $opt->amount - $partialAmt);
                                            @endphp
                                            <tr>
                                                <td>
                                                    @if (!$isPaid)
                                                        <input type="checkbox" name="optional_fees[]"
                                                            value="{{ $opt->id }}" class="optional-fee-chk"
                                                            data-amount="{{ $opt->amount }}">
                                                    @endif
                                                </td>
                                                <td>{{ $opt->fees_type->name ?? '-' }}</td>
                                                <td class="text-right">{{ $currencySymbol }} {{ number_format($opt->amount, 2) }}
                                                    @if($isPartial)
                                                        <br><small class="text-success">Paid: {{ $currencySymbol }} {{ number_format($partialAmt, 2) }}</small>
                                                        <br><small class="text-danger">Due: {{ $currencySymbol }} {{ number_format($optDue, 2) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($isPaid)
                                                        <span class="badge badge-success">{{ __('Paid') }}</span>
                                                    @elseif($isPartial)
                                                        <span class="badge badge-warning">Partial</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ __('Unpaid') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                                
                            <input class="btn btn-theme float-right" type="submit" value="{{ __('submit') }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Show/hide cheque no
        $('#mode').on('change', function() {
            $('#cheque-no-wrap').toggle(this.value === 'Cheque');
            if (this.value === 'Cheque') {
                $('[name="cheque_no"]').attr('required', true);
            } else {
                $('[name="cheque_no"]').removeAttr('required');
            }
        });

        // Show total available (entered + advance)
        @php $advanceAmt = $student->fees_advances_sum_amount ?? 0; @endphp
        $('#enter_amount').on('input', function () {
            var entered = parseFloat($(this).val()) || 0;
            var advance = {{ $advanceAmt }};
            var total   = entered + advance;
            if (advance > 0) {
                $('#total-available-hint').text('Total available: ₹' + total.toLocaleString('en-IN') + ' (₹' + entered.toLocaleString('en-IN') + ' entered + ₹' + advance.toLocaleString('en-IN') + ' advance)');
            }
        });

        function successFunction() {
            window.location.href = "{{ route('fees.paid.index') }}";
        }
    </script>
@endsection

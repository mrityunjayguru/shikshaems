@extends('layouts.master')

@section('title')
    {{ __('Payment Settings') }}
@endsection

{{-- THIS VIEW IS COMMON FOR BOTH THE SUPER ADMIN & SCHOOL ADMIN --}}
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                @if (Auth::user()->school_id)
                    {{ __('fees_payment_settings') }}
                @else
                    {{ __('Payment Settings') }}
                @endif
            </h3>
        </div>
        <div class="row grid-margin">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form class="create-form-without-reset" action="{{ route('system-settings.payment.update') }}"
                            method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            
                            @include('settings.forms.payment-form')
                          <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color);">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color);">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        window.onload = setTimeout(() => {
            $('#currency_code').trigger("change");
        }, 500);

        @if (!empty($paymentGateway['Stripe']['currency_code']))
            $('#currency_code').val("{{ $settings['currency_code'] }}").trigger("change");
        @endif

        $('#currency_code').on('change', function() {
            $('#stripe_currency').val($(this).val());
        })

        $('#currency_code').on('change', function() {
            $('#razorpay_currency').val($(this).val());
        })

        $('#currency_code').on('change', function() {
            $('#flutterwave_currency').val($(this).val());
        })

        $('#currency_code').on('change', function() {
            $('#paystack_currency').val($(this).val());
        })

        $('#currency_code').on('change', function() {
            $('#bank_transfer_currency').val($(this).val());
        })
    </script>
@endsection

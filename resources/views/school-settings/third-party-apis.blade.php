@extends('layouts.master')

@section('title')
    {{ __('Third-Party APIs') }}
@endsection


@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Third-Party APIs') }}
            </h3>
        </div>
        <div class="row grid-margin">
            <div class="col-lg-12">
                <div class="card">
                    <div class="custom-card-body">
                        <form id="formdata" class="create-form-without-reset" action="{{ route('school-settings.third-party.update') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            {{-- System Settings --}}
                            <div class="border border-secondary rounded-lg my-4 mx-1">
                                <div class="col-md-12 mt-3">
                                    <h4>{{ __('google_recaptcha') }}</h4>
                                </div>
                                <div class="col-12 mb-3">
                                    <hr class="mt-0">
                                </div>
                                <div class="row my-4 mx-1">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label for="RECAPTCHA_SITE_KEY">{{ __('RECAPTCHA_SITE_KEY') }}</label>
                                        <input name="SCHOOL_RECAPTCHA_SITE_KEY" id="RECAPTCHA_SITE_KEY" value="{{ $schoolSettings['SCHOOL_RECAPTCHA_SITE_KEY'] ?? '' }}" type="text" placeholder="{{ __('RECAPTCHA_SITE_KEY') }}" class="form-control"/>
                                    </div>

                                    <div class="form-group col-md-4 col-sm-12">
                                        <label for="RECAPTCHA_SECRET_KEY">{{ __('RECAPTCHA_SECRET_KEY') }}</label>
                                        <input name="SCHOOL_RECAPTCHA_SECRET_KEY" id="RECAPTCHA_SECRET_KEY" value="{{ $schoolSettings['SCHOOL_RECAPTCHA_SECRET_KEY'] ?? '' }}" type="text" placeholder="{{ __('RECAPTCHA_SECRET_KEY') }}" class="form-control"/>
                                    </div>
    
                                </div>

                                {{-- Add link for reCAPTCHA Admin --}}
                                <div class="col-md-12">
                                    <p class="mt-4">
                                        <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="text-info">
                                            {{ __('Click here to create or manage reCAPTCHA keys') }}
                                        </a>
                                    </p>
                                </div>

                                {{-- Add link for the video tutorial --}}
                                <div class="col-md-12">
                                    <p class="my-4">
                                        <a href="https://drive.google.com/file/d/1sw2YJd-n8eJbm7R-IS5CUv_nx4bc5oDy/view?usp=sharing" target="_blank" class="text-info">
                                            {{ __('Watch the video tutorial for setup steps') }}
                                        </a>
                                    </p>
                                </div>

                                <div class="col-md-12">
                                    <p class="mt-2 text-primary">
                                        <b>Note: </b>
                                        This reCAPTCHA used at <b>Admission Page & Contact us</b> page.
                                    </p>
                                </div>

                            </div>
                            {{-- End System Settings --}}

                              <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

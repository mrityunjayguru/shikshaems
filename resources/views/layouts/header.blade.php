<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ URL::to('/dashboard') }}">
            <img src="{{ $schoolSettings['horizontal_logo'] ?? '' }}" alt="logo" data-custom-image="{{$systemSettings['horizontal_logo'] ?? asset('/assets/horizontal-logo2.svg')}}" class="custom-default-image">
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ URL::to('/dashboard') }}">
            <img src="{{ $schoolSettings['vertical_logo'] ?? '' }}" alt="logo" data-custom-image="{{$systemSettings['vertical_logo'] ?? asset('/assets/vertical-logo.svg')}}">
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="fa fa-bars"></span>
        </button>

        <div class="align-items-stretch d-none d-md-block d-sm-block cache-clear">
            <span class="align-self-center cache-clear" onclick="window.location.href = '{{ url('cache-flush') }}'">
                {{ __('cache_clear') }}
            </span>
        </div>

        @if ($schoolSettings['school_name'] ?? '')
        <div class="align-items-stretch d-none d-md-block d-sm-block mt-4">
            <span class="ml-4" style='color : #202020;'>{{ $schoolSettings['school_name'] ?? '' }}</span>
        </div>
        @endif
        @if (isset($systemSettings['email_verified']) && !$systemSettings['email_verified'])
        @can('email-setting-create')
        <div class="mx-auto order-0">
            <div class="alert alert-fill-danger my-2" role="alert">
                <i class="fa fa-exclamation"></i>
                {{ __('Email Configuration is not verified') }} <a href="{{ route('system-settings.email.index') }}" class="alert-link">{{ __('Click here to redirect to email configuration') }}</a>.
            </div>
        </div>
        @endcan
        @endif
        <ul class="navbar-nav navbar-nav-right">
            @can('class-teacher')
            <li class="nav-item">
                {{-- TODO :: CLASS TEACHER CLASS NAME --}}
                {{-- @php $class_section = Auth::user()->teacher->class_section @endphp
                    <div class="text-dark">{{__('Class').' : '.$class_section->class->name.' '.$class_section->section->name.' - '.$class_section->class->medium->name}}
    </div> --}}
    </li>
    @endcan

    @if (isset($sessionYear) && !Auth::user()->hasRole('Super Admin'))
    <li class="d-none d-md-block d-sm-block nav-item">
        <div class="" style='color : #202020;'> {{ __('session_years') . ' : '}} <span id="sessionYearNameHeader">{{$sessionYear->name}}</span><span id="semesterNameHeader">{{ (isset($semester) ? ', '.$semester->name : null)}}</span></div>
    </li>
    @endif

    {{-- <li class="d-none d-md-block d-sm-block nav-item ml-4">
                <div class="text-dark">
                    <span><i class="mdi mdi-weather-sunny fa-2x cursor-pointer theme"></i></span>
                </div>
            </li> --}}

    <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
            <!-- <i class="fa fa-language"></i> -->
            <img src="{{ asset('assets/icons/languages.svg') }}" alt="languages" class="w-5 h-5 svg-theme-stroke">
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
            @foreach ($languages as $key => $language)
            <a class="dropdown-item preview-item" href="{{ url('set-language') . '/' . $language->code }}">
                <div class="preview-thumbnail">
                    {{-- <img src="../../../assets/images/faces/face3.jpg" alt="image" class="profile-pic"> --}}
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">{{ $language->name }}</h6>
                    {{-- <p class="text-gray mb-0"> 18 Minutes ago </p> --}}
                </div>
            </a>
            <div class="dropdown-divider"></div>
            @endforeach
        </div>
    </li>
    <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="true">
            <div class="nav-profile-img">
                <img src="{{ Auth::user()->image }}" alt="image">
            </div>
            <div class="nav-profile-text">
                <p class="mb-1 text-black">{{ Auth::user()->first_name }}</p>
            </div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
            {{-- @can('update-admin-profile') --}}
            <a class="dropdown-item" href="{{ route('auth.profile.edit') }}">
                <svg width="24" height="24" class="mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                </i>{{ __('profile') }}</a>
            <div class="dropdown-divider"></div>
            {{-- @endcan --}}
            <a class="dropdown-item" href="{{ route('auth.change-password.index') }}">
                <svg width="24" height="24" class="mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17.5 12C17.5 15.04 15.04 17.5 12 17.5C8.96 17.5 7.10999 14.44 7.10999 14.44M7.10999 14.44H9.59M7.10999 14.44V17.19M6.5 12C6.5 8.96 8.94 6.5 12 6.5C15.67 6.5 17.5 9.56 17.5 9.56M17.5 9.56V6.81M17.5 9.56H15.06" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                {{ __('change_password') }}</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('auth.logout') }}">
                <svg width="24" height="24" class="mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4399 14.62L19.9999 12.06L17.4399 9.5" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.76001 12.0601H19.93" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11.76 20C7.34001 20 3.76001 17 3.76001 12C3.76001 7 7.34001 4 11.76 4" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ __('signout') }}
            </a>
        </div>
    </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="fa fa-bars"></span>
    </button>
    </div>
</nav>
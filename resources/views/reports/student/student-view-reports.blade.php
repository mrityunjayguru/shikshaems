@extends('layouts.master')

@section('title')
    {{ __('student_profile') }} - {{ $student->user->first_name }} {{ $student->user->last_name }}
@endsection

@section('css')
<style>

    .card {
        border-radius: 14px;
        border: 1px solid #eee;
        box-shadow: 0 3px 8px rgba(0,0,0,0.04);
        margin-bottom: 18px;
    }

    .card-body {
        padding: 22px !important;
    }


    .student-profile-header {
        background: #f5f3f7;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .student-profile-header img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        /* border: 3px solid #fff; */
        /* box-shadow: 0 3px 6px rgba(0,0,0,0.12); */
    }

    .student-profile-header h4 {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
        color: #202020;
    }

    .student-profile-header p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }


    .list-group-item {
        border: none !important;
        padding: 10px 0;
        font-size: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-item span:first-child {
        font-weight: 600;
        color: #444;
        font-size: 14px;
    }

    .list-group-item span:last-child {
        font-weight: 700;
        color: #202020;
    }


    .nav-tabs .nav-link {
        font-weight: 600;
        color: #555;
        border: none;
        padding: 12px 18px;
    }

    .nav-tabs .nav-link.active {
        color: var(--theme-color);
        border-bottom: 3px solid var(--theme-color);
        background: transparent;
    }

    .card-header {
        background: #fafafa !important;
        border-bottom: 1px solid #eee;
        font-weight: 700;
        color: var(--theme-color);
    }


    .profile-header-box {
        background: #f6f4f8;
        border-radius: 14px;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        gap: 25px;
    }


    .profile-header-box .profile-img {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .profile-header-box h3 {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 12px;
        color: #111;
    }

  
    .profile-info-row {
        display: flex;
        gap: 55px;
        flex-wrap: wrap;
    }

    .profile-info-item span {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        display: block;
        margin-bottom: 4px;
    }

    .profile-info-item p {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: #111;
    }
</style>
@endsection


@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
           {{ __('student_profile') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('reports.student.student-reports') }}">{{ __('student_reports') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('profile') }}</li>
            </ol>
        </nav>
    </div>
    <div class="col">
     <div class="card">
    <div class="card-body">

        <div class="profile-header-box">

            <img src="{{ $student->user->image ?? asset('images/default-user.png') }}"
                 class="profile-img"
                 alt="student">

            <div>

                <h3>
                    {{ $student->user->first_name }}
                    {{ $student->user->last_name }}
                </h3>

                <div class="profile-info-row">

                    <div class="profile-info-item">
                        <span>{{ __('admission_no') }}</span>
                        <p>{{ $student->admission_no ?? '-' }}</p>
                    </div>

                    <div class="profile-info-item">
                        <span>{{ __('class') }}</span>
                        <p>{{ $student->class_section->class->name ?? '-' }}</p>
                    </div>

                    <div class="profile-info-item">
                        <span>{{ __('section') }}</span>
                        <p>{{ $student->class_section->section->name ?? '-' }}</p>
                    </div>

                    <div class="profile-info-item">
                        <span>{{ __('dob') }}</span>
                        <p>{{ $student->user->dob ?? '-' }}</p>
                    </div>

                    <div class="profile-info-item">
                        <span>{{ __('gender') }}</span>
                        <p>{{ ucfirst($student->user->gender ?? '-') }}</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>


    
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
        
                    <ul class="nav nav-tabs nav-tabs-line" id="studentTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">
                                {{ __('profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab">
                                {{ __('attendance') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="exam-tab" data-toggle="tab" href="#exam" role="tab">
                                {{ __('exam') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="fees-tab" data-toggle="tab" href="#fees" role="tab">
                                {{ __('Fees') }}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content border-0 px-0" id="studentTabContent">
                        <div class="tab-pane fade show active py-3" id="profile" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-gradient-light p-2">
                                    <h5 class="mb-0 text-theme">{{ __('basic_information') }}</h5>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('admission_date') }}:</strong> {{ $student->admission_date ?? '-' }}</p>
                                            <p><strong>{{ __('dob') }}:</strong> {{ $student->user->dob ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('mobile_number') }}:</strong> {{ $student->user->mobile ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="card">
                                <div class="card-header bg-gradient-light p-2">
                                    <h5 class="mb-0 text-theme">{{ __('address_information') }}</h5>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">{{ __('current_address') }}</h6>
                                            <p>{{ $student->user->current_address ?: '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted">{{ __('permanent_address') }}</h6>
                                            <p>{{ $student->user->permanent_address ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header bg-gradient-light p-2">
                                    <h5 class="mb-0 text-theme">{{ __('parent_guardian_information') }}</h5>
                                </div>
                                <div class="card-body p-2">
                                    @if(isset($student->guardian))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('name') }}:</strong> {{ $student->guardian->first_name }} {{ $student->guardian->last_name }}</p>
                                            <p><strong>{{ __('gender') }}:</strong> {{ ucfirst($student->guardian->gender) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('email') }}:</strong> {{ $student->guardian->email }}</p>
                                            <p><strong>{{ __('mobile_number') }}:</strong> {{ $student->guardian->mobile }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <div class="alert alert-info">
                                        {{ __('no_guardian_information_available') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <div class="tab-pane fade py-3" id="attendance" role="tabpanel">
                            @include('reports.student.attendance-report-tab', ['sessionYears' => $sessionYears])
                        </div>
                        <div class="tab-pane fade py-3" id="exam" role="tabpanel">
                            @include('reports.student.exam-report-tab')
                        </div>
                        <div class="tab-pane fade py-3" id="fees" role="tabpanel">
                            @include('reports.student.fees-report-tab', ['studentFees' => $studentFees])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Handle tab navigation
        $('#studentTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush


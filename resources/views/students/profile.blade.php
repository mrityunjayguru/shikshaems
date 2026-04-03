@extends('layouts.master')

@section('css')
    <style>
        /* Map Container Fixes */
        #map,
        #pickup_map {
            width: 100% !important;
            height: 200px !important;
            margin: 0 !important;
            display: block !important;
        }

        .map-wrapper {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            background: #eee;
            margin-top: 12px;
        }

        .text-warning {
            color: var(--theme-color) !important;
        }

        .details {
            padding-left: 14px !important;
        }

        /* ============================= */
        /* ✅ PROFILE INFO (Table Removed) */
        /* ============================= */

        .student-info h5 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 12px;
            color: #202020;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        .info-item {
            min-width: 140px;
        }

        .info-item span {
            font-size: 13px;
            font-weight: 600;
            display: block;
            color: #474747ff;
            margin-bottom: 4px;
        }

        .info-item p {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 22px !important;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #202020;
        }

        small {
            font-size: 13px;
        }
    </style>
@endsection

@section('title')
    {{ __('students') }}
@endsection

@section('content')
    @php
        $student = $student[0] ?? null;
        $route_details = $route_details[0] ?? null;
        $pickup_point = $pickup_point[0] ?? null;

        $pickupPoint = $route_details ? $route_details->pickupPoint : null;

        if (isset($student->class_section) && isset($student->class_section->class_teachers)) {
            $classTeacher = $student->class_section->class_teachers[0] ?? null;
        } else {
            $classTeacher = null;
        }
    @endphp

    <div class="content-wrapper">

        {{-- Page Header --}}
        <div class="page-header mb-3 d-flex align-items-center justify-content-between">
            <h3 class="page-title">{{ __('Student Details') }}</h3>
            <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-arrow-left mr-1 text-white"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12">

                {{-- PROFILE CARD --}}
                <div class="card mb-3">
                    <div class="card-body">

                        {{-- Top Profile Section --}}
                        <div class="p-3 rounded" style="background:#f5f3f7;">
                            <div class="row align-items-center">

                                {{-- Profile Image --}}
                                <div class="col-md-2 text-center">
                                    <img src="{{ $student->user->image ?? asset('assets/dummy_logo.jpg') }}"
                                        class="rounded-circle mb-2" width="90" height="90"
                                        onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">
                                </div>

                                {{-- Student Info (Table Removed) --}}
                                <div class="col-md-10">

                                    <div class="student-info">

                                        <h5>{{ $student->full_name }}</h5>

                                        <div class="info-grid">

                                            <div class="info-item">
                                                <span>Admission No</span>
                                                <p>{{ $student->admission_no }}</p>
                                            </div>

                                            <div class="info-item">
                                                <span>Class</span>
                                                <p>{{ $student->class_section->class->name ?? '-' }}</p>
                                            </div>

                                            <div class="info-item">
                                                <span>Section</span>
                                                <p>{{ $student->class_section->section->name ?? '-' }}</p>
                                            </div>

                                            <div class="info-item">
                                                <span>DOB</span>
                                                <p>{{ $student->user->dob }}</p>
                                            </div>

                                            <div class="info-item">
                                                <span>Gender</span>
                                                <p>{{ ucfirst($student->user->gender) }}</p>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- DETAILS SECTION --}}
                        <div class="row mt-3">

                            {{-- LOCATION --}}
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Home Address</h6>

                                        <p id="student_address">
                                            Address :- {{ $student->user->current_address ?? 'N/A' }}
                                        </p>

                                        <div class="row mt-2" style="font-size:13px;">
                                            <div class="col-6">
                                                Latitude: <span id="lat">{{ $student->latitude ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-6">
                                                Longitude: <span id="lng">{{ $student->longitude ?? 'N/A' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 rounded overflow-hidden" style="background:#eee;">
                                            <div id="map" style="height: 200px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PICKUP POINT --}}
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Pickup Point</h6>

                                        <p id="pickuppoint_address">
                                            Address :- {{ $pickupPoint->name ?? 'N/A' }}
                                        </p>

                                        <div class="row mt-2" style="font-size:13px;">
                                            <div class="col-6">
                                                Latitude: <span
                                                    id="pickup_lat">{{ $pickupPoint->latitude ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-6">
                                                Longitude: <span
                                                    id="pickup_lng">{{ $pickupPoint->longitude ?? 'N/A' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 rounded overflow-hidden" style="background:#eee;">
                                            <div id="pickup_map" style="height: 200px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- GUARDIAN DETAILS --}}
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Guardian Details</h6>

                                        <div class="d-flex justify-content-center align-items-center">
                                            <img src="{{ $student->guardian->image }}" class="rounded-circle mr-2"
                                                width="40" height="40"
                                                onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">

                                            <div class="ms-2 details">
                                                <div class="fw-semibold" style="font-size:16px;">
                                                    {{ $student->guardian->full_name }}
                                                </div>
                                                <small>{{ $student->guardian->mobile }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CLASS STAFF --}}
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Class Staff</h6>

                                        @if ($classTeacher && isset($classTeacher->teacher))
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ $classTeacher->teacher->image }}"
                                                    class="rounded-circle" width="40" height="40"
                                                    onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">
                                                <div class="ms-2 details">
                                                    <div class="fw-semibold" style="font-size:16px;">
                                                        {{ $classTeacher->teacher->full_name }}
                                                    </div>
                                                    <small>Class Teacher</small>
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            $groupedTeachers = $subjectTeachers->groupBy('teacher_id');
                                        @endphp

                                        @foreach ($groupedTeachers as $teacherId => $teacherSubjects)
                                            @php
                                                $firstEntry = $teacherSubjects->first();
                                                $teacherUser = $firstEntry->teacher ?? null;
                                                $subjectNames = $teacherSubjects
                                                    ->map(function ($ts) {
                                                        return $ts->subject->name_with_type ??
                                                            ($ts->subject->name ?? null);
                                                    })
                                                    ->filter()
                                                    ->implode(', ');
                                            @endphp
                                            @if ($teacherUser)
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{ $teacherUser->image }}"
                                                        class="rounded-circle" width="40" height="40"
                                                        onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">
                                                    <div class="ms-2 details">
                                                        <div class="fw-semibold" style="font-size:15px;">
                                                            {{ $teacherUser->full_name }}
                                                        </div>
                                                        <small>{{ $subjectNames ?: 'Subject Teacher' }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ROUTE --}}
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Route Plan</h6>

                                        <p style="font-size:16px;">
                                            Route :- {{ $route_details->routeVehicle->route->name ?? 'N/A' }}
                                        </p>

                                        <div class="row mt-2" style="font-size:16px;">
                                            <div class="col-6">
                                                Stop: {{ $pickupPoint->name ?? 'N/A' }}
                                            </div>
                                            <div class="col-6">
                                                Pickup: {{ $pickupPoint->pickup_time ?? 'N/A' }}
                                            </div>
                                            <div class="col-6 mt-2">
                                                Drop: {{ $pickupPoint->dropoff_time ?? 'N/A' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let map1, marker1;
        let map2, marker2;

        const studentLat = {{ $student->latitude ?? 'null' }};
        const studentLng = {{ $student->longitude ?? 'null' }};
        const pickupLat = {{ $pickupPoint->latitude ?? 'null' }};
        const pickupLng = {{ $pickupPoint->longitude ?? 'null' }};

        const DEFAULT_LAT = 20.5937;
        const DEFAULT_LNG = 78.9629;

        function initMaps() {
            initStudentMap();
            initPickupMap();
        }

        function initStudentMap() {
            const lat = studentLat !== null ? parseFloat(studentLat) : DEFAULT_LAT;
            const lng = studentLng !== null ? parseFloat(studentLng) : DEFAULT_LNG;
            const position = {
                lat,
                lng
            };

            map1 = new google.maps.Map(document.getElementById("map"), {
                center: position,
                zoom: studentLat !== null ? 14 : 4,
            });

            marker1 = new google.maps.Marker({
                position,
                map: map1,
                draggable: false
            });

            if (studentLat !== null) {
                fetchAddress(lat, lng, 'student_address');
            }
        }

        function initPickupMap() {
            const lat = pickupLat !== null ? parseFloat(pickupLat) : DEFAULT_LAT;
            const lng = pickupLng !== null ? parseFloat(pickupLng) : DEFAULT_LNG;
            const position = {
                lat,
                lng
            };

            map2 = new google.maps.Map(document.getElementById("pickup_map"), {
                center: position,
                zoom: pickupLat !== null ? 14 : 4,
            });

            marker2 = new google.maps.Marker({
                position,
                map: map2,
                draggable: false
            });
        }

        function fetchAddress(lat, lng, targetId) {
            fetch("https://app.trackroutepro.com/common/jioCode", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                })
                .then(res => res.json())
                .then(data => {
                    let address = data?.address || data?.result?.address || '';
                    const el = document.getElementById(targetId);
                    if (!el) return;
                    el.innerText = 'Address :- ' + (address || 'N/A');
                })
                .catch(err => console.error("Jio API error:", err));
        }
    </script>
@endsection

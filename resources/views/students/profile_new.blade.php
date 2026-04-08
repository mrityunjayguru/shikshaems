@extends('layouts.master')

@section('css')
<style>
    #map,
    #pickup_map {
        width: 100%;
        height: 400px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('title')
{{ __('students') }}
@endsection

@section('content')

@php
    // 🔒 SAFETY GUARDS (ONLY FIX)
    $student[0] = $student[0] ?? null;
    $route_details[0] = $route_details[0] ?? null;
    $pickup_point[0] = $pickup_point[0] ?? null;
    
    if(isset($student[0]->class_section)) {
        $student[0]->class_section->class_teachers[0] =
            $student[0]->class_section->class_teachers[0] ?? null;
    }
@endphp

<div class="content-wrapper">

    <div class="page-header mb-3">
        <h3 class="page-title">{{ __('Student Details') }}</h3>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card mb-3">
                <div class="card-body">

                    {{-- PROFILE --}}
                    <div class="p-3 rounded" style="background:#f5f3f7;">
                        <div class="row align-items-center">

                            <div class="col-md-2 text-center">
                                <img src="{{ $student[0]->user->image ?? asset('assets/dummy_logo.jpg') }}"
                                     class="rounded-circle mb-2"
                                     width="90" height="90"
                                     onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">
                            </div>

                            <div class="col-md-10">
                                <table class="table table-borderless mb-0">
                                    <thead class="text-muted">
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Admission No</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>DOB</th>
                                            <th>Gender</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">{{ $student[0]->full_name ?? '-' }}</td>
                                            <td>{{ $student[0]->admission_no ?? '-' }}</td>
                                            <td>{{ $student[0]->class_section->class->name ?? '-' }}</td>
                                            <td>{{ $student[0]->class_section->section->name ?? '-' }}</td>
                                            <td>{{ $student[0]->user->dob ?? '-' }}</td>
                                            <td>{{ ucfirst($student[0]->user->gender ?? '-') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    {{-- DETAILS --}}
                    <div class="row mt-3">

                        {{-- LOCATION --}}
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-warning mb-3">Location</h6>
                                    Address :- {{ $student[0]->user->current_address ?? 'N/A' }}
                                    <div id="map"></div>
                                </div>
                            </div>
                        </div>

                        {{-- PICKUP POINT --}}
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-warning mb-3">Pickup Point</h6>
                                    Address :- {{ $route_details[0]->pickupPoint->name ?? 'N/A' }}
                                    <div id="pickup_map"></div>
                                </div>
                            </div>
                        </div>

                        {{-- GUARDIAN --}}
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-warning mb-3">Guardian Details</h6>
                                    <div>{{ $student[0]->guardian->full_name ?? 'N/A' }}</div>
                                    <small>{{ $student[0]->guardian->mobile ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- CLASS TEACHER --}}
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-warning mb-3">Class Staff</h6>
                                    {{ $student[0]->class_section->class_teachers[0]->teacher->full_name ?? 'Not Assigned' }}
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
                                    Route :- {{ $route_details[0]->routeVehicle->route->name ?? 'N/A' }}
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
    const pickupLat = {{ $pickup_point[0]->pickupPoint->latitude ?? 25.9644 }};
    const pickupLng = {{ $pickup_point[0]->pickupPoint->longitude ?? 85.2722 }};
</script>
@endsection
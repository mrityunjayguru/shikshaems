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
    <div class="page-header mb-3">
        <h3 class="page-title">{{ __('Student Details') }}</h3>
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
                                    class="rounded-circle mb-2"
                                    width="90" height="90"
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
                                    <h6 class="text-warning mb-3">Location</h6>

                                    <p id="student_address">
                                        Address :- {{ $student->user->current_address ?? 'N/A' }}
                                    </p>

                                    <div class="row mt-2" style="font-size:13px;">
                                        <div class="col-6">
                                            Latitude: <span id="lat"></span>
                                        </div>
                                        <div class="col-6">
                                            Longitude: <span id="lng"></span>
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
                                            Latitude: <span id="pickup_lat">{{ $pickupPoint->latitude ?? '-' }}</span>
                                        </div>
                                        <div class="col-6">
                                            Longitude: <span id="pickup_lng">{{ $pickupPoint->longitude ?? '-' }}</span>
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

                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/dummy_logo.jpg') }}"
                                            class="rounded-circle"
                                            width="40" height="40">

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
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/dummy_logo.jpg') }}"
                                                class="rounded-circle"
                                                width="40" height="40">

                                            <div class="ms-2 details">
                                                <div class="fw-semibold" style="font-size:16px;">
                                                    {{ $classTeacher->teacher->full_name }}
                                                </div>
                                                <small>Class Teacher</small>
                                            </div>
                                        </div>
                                    @endif

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
                                        <div class="col-6" >
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
        window.onload = function() {
            initMap();
            pickupInitMap();
        };
    </script>
    <script>
        let map1, marker1;
        let map2, marker2;

        const pickupLat = {{ $pickup_point->pickupPoint->latitude ?? 25.9644 }};
        const pickupLng = {{ $pickup_point->pickupPoint->longitude ?? 85.2722 }};

        function initMap() {

            const defaultPosition = {
                lat: 25.1516,
                lng: 84.9818
            };

            map1 = new google.maps.Map(document.getElementById("map"), {
                center: defaultPosition,
                zoom: 10,
            });

            marker1 = new google.maps.Marker({
                position: defaultPosition,
                map: map1,
                draggable: true
            });

            updateLatLng(defaultPosition);

            const input = createSearchInput(map1);

            const searchBox = new google.maps.places.SearchBox(input);

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (!places.length) return;

                const place = places[0];
                if (!place.geometry) return;

                map1.setCenter(place.geometry.location);
                map1.setZoom(14);

                marker1.setPosition(place.geometry.location);
                updateLatLng(place.geometry.location);
            });

            map1.addListener("click", (event) => {
                marker1.setPosition(event.latLng);
                updateLatLng(event.latLng);
            });

            marker1.addListener("dragend", (event) => {
                updateLatLng(event.latLng);
            });
        }

        function pickupInitMap() {

            const defaultPosition = {
                lat: parseFloat(pickupLat),
                lng: parseFloat(pickupLng)
            };

            map2 = new google.maps.Map(document.getElementById("pickup_map"), {
                center: defaultPosition,
                zoom: 14,
            });

            marker2 = new google.maps.Marker({
                position: defaultPosition,
                map: map2,
                draggable: true
            });

            pickupPointUpdateLatLng(defaultPosition);

            const input = createSearchInput(map2);

            const searchBox = new google.maps.places.SearchBox(input);

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (!places.length) return;

                const place = places[0];
                if (!place.geometry) return;

                map2.setCenter(place.geometry.location);
                map2.setZoom(14);

                marker2.setPosition(place.geometry.location);
                pickupPointUpdateLatLng(place.geometry.location);
            });

            map2.addListener("click", (event) => {
                marker2.setPosition(event.latLng);
                pickupPointUpdateLatLng(event.latLng);
            });

            marker2.addListener("dragend", (event) => {
                pickupPointUpdateLatLng(event.latLng);
            });
        }

        function createSearchInput(map) {
            const input = document.createElement("input");
            input.type = "text";
            input.placeholder = "Search location";
            input.style.cssText = `
            box-sizing: border-box;
            border: 1px solid #ccc;
            width: 300px;
            height: 40px;
            margin: 10px;
            padding: 0 10px;
            font-size: 14px;
        `;
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            return input;
        }

        function updateLatLng(location) {
            const lat = typeof location.lat === "function" ? location.lat() : location.lat;
            const lng = typeof location.lng === "function" ? location.lng() : location.lng;

            document.getElementById("lat").innerText = lat;
            document.getElementById("lng").innerText = lng;

            fetchAddress(lat, lng, 'student_address');
        }


        function pickupPointUpdateLatLng(location) {
            const lat = typeof location.lat === "function" ? location.lat() : location.lat;
            const lng = typeof location.lng === "function" ? location.lng() : location.lng;

            document.getElementById("pickup_lat").innerText = lat;
            document.getElementById("pickup_lng").innerText = lng;

            fetchAddress(lat, lng, 'pickuppoint_address');
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

                    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                        el.value = address;
                    }
                    else {
                        el.innerText = 'Address :- ' + address;
                    }
                })
                .catch(err => console.error("Jio API error:", err));
        }
    </script>
@endsection
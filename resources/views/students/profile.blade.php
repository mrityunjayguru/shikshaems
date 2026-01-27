@extends('layouts.master')

@section('css')
    <style>
        #map,
        #pickup_map {
            width: 100%;
            height: 400px;
            /* REQUIRED */
            margin-bottom: 20px;
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
                                        class="rounded-circle mb-2" width="90" height="90"
                                        onerror="this.src='{{ asset('assets/dummy_logo.jpg') }}'">
                                </div>

                                {{-- Student Info --}}
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
                                                <td class="fw-semibold">{{ $student->full_name }}</td>
                                                <td>{{ $student->admission_no }}</td>
                                                <td>{{ $student->class_section->class->name ?? '-' }}</td>
                                                <td>{{ $student->class_section->section->name ?? '-' }}</td>
                                                <td>{{ $student->user->dob }}</td>
                                                <td>{{ ucfirst($student->user->gender) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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

                                        <p class="mb-1">
                                            Address :- {{ $student->user->current_address ?? 'N/A' }}
                                        </p>

                                        <div class="row small text-muted">
                                            <div class="col-6">Latitude: <span id="lat"></span><br></div>
                                            <div class="col-6">Longitude: <span id="lng"></span></div>
                                        </div>

                                        {{-- Map Placeholder --}}
                                        <div class="mt-3 rounded overflow-hidden" style="height:150px;background:#eee;">
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

                                        <p class="mb-1">
                                            Address :- {{ $pickupPoint->name ?? 'N/A' }}
                                        </p>

                                        <div class="row small text-muted">
                                            <div class="col-6">Latitude: <span
                                                    id="pickup_lat">{{ $pickupPoint->latitude ?? '-' }}</span><br>
                                            </div>
                                            <div class="col-6">Longitude: <span
                                                    id="pickup_lng">{{ $pickupPoint->longitude ?? '-' }}</span>
                                            </div>
                                        </div>

                                        {{-- Map Placeholder --}}
                                        <div class="mt-3 rounded overflow-hidden" style="height:150px;background:#eee;">
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

                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ asset('assets/dummy_logo.jpg') }}" class="rounded-circle"
                                                width="35" height="35">
                                            <div class="ms-2">
                                                <div class="fw-semibold">{{ $student->guardian->full_name }}</div>
                                                <small class="text-muted">{{ $student->guardian->mobile }}</small>
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

                                        {{-- @foreach ($classStaff as $staff) --}}
                                        @if ($classTeacher && isset($classTeacher->teacher))
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('assets/dummy_logo.jpg') }}" class="rounded-circle"
                                                    width="35" height="35">
                                                <div class="ms-2">
                                                    <div class="fw-semibold">
                                                        {{ $classTeacher->teacher->full_name }}
                                                    </div>
                                                    <small class="text-muted">Class Teacher</small>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- @endforeach --}}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            {{-- ROUTE --}}
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">Route Plan</h6>

                                        <p class="mb-1">
                                            Route :- {{ $route_details->routeVehicle->route->name ?? 'N/A' }}
                                        </p>

                                        <div class="row small text-muted">
                                            <div class="col-6">Stop: <span id="stop">
                                                    {{ $pickupPoint->name ?? 'N/A' }}</span><br></div>
                                            <div class="col-6">pickup time:
                                                <span>{{ $pickupPoint->pickup_time ?? 'N/A' }}</span><br>
                                            </div>
                                            <div class="col-6 mt-2">drop time:
                                                <span>{{ $pickupPoint->dropoff_time ?? 'N/A' }}</span>
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
        }


        function pickupPointUpdateLatLng(location) {
            const lat = typeof location.lat === "function" ? location.lat() : location.lat;
            const lng = typeof location.lng === "function" ? location.lng() : location.lng;

            document.getElementById("pickup_lat").innerText = lat;
            document.getElementById("pickup_lng").innerText = lng;
        }
    </script>
@endsection

@extends('layouts.master')

@section('title')
    {{ __('pickup_points') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_pickup_points') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create_pickup_point') }}
                        </h4>
                        <form class="pt-3 pickup-point-create-form" id="create-form"
                            action="{{ route('pickup-points.store') }}" method="POST" novalidate="novalidate">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" placeholder="{{ __('name') }}"
                                        class="form-control" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ __('status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Latitude<span class="text-danger">*</span></label>
                                    <input type="text" id="lat" placeholder="Latitude" name="latitude"
                                        class="form-control mt-2" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Longitude<span class="text-danger">*</span></label>
                                    <input type="text" id="lng" placeholder="Longitude" name="longitude"
                                        class="form-control mt-2" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <div id="map" style="height: 400px; width: 100%;"></div>
                                </div>

                            </div>
                            <input class="btn btn-theme float-right" id="create-btn" type="submit"
                                value={{ __('submit') }}>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list_pickup_points') }}
                        </h4>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('pickup-points.show', [1]) }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-maintain-selected="true" data-query-params="queryParams"
                            data-show-export="true" data-export-options='{"fileName": "pickup-points-list-<?= date('d-m-y')
                            ?>","ignoreColumn":
                            ["operate"]}'
                            data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}</th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="name" data-sortable="true">{{ __('name') }}</th>
                                    {{-- <th scope="col" data-field="transportation_fees"
                                        data-formatter="transportationFeesFormatter" data-escape="false"
                                        data-sortable="false">{{ __('transportation_fees') }}</th> --}}
                                    <th scope="col" data-field="status" data-formatter="activeStatusFormatter"
                                        data-sortable="false">{{ __('status') }}</th>
                                    <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                        data-events="pickupPointEvents" data-escape="false">{{ __('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('edit_pickup_point') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="pt-3 pickup-point-edit-form" id="edit-form" action="{{ url('pickup-points') }}"
                            method="POST" novalidate="novalidate">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="edit_id" id="edit_id" value="" />
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                        <input name="name" id="edit_name" type="text"
                                            placeholder="{{ __('name') }}" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>{{ __('status') }}</label>
                                        <select name="status" id="edit_status" class="form-control">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Latitude<span class="text-danger">*</span></label>
                                        <input type="text" id="edit_lat" placeholder="Latitude" name="latitude"
                                            class="form-control mt-2" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Longitude<span class="text-danger">*</span></label>
                                        <input type="text" id="edit_lng" placeholder="Longitude" name="longitude"
                                            class="form-control mt-2" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div id="edit_map" style="height: 400px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('close') }}</button>
                                <input class="btn btn-theme" type="submit" value={{ __('submit') }} />
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
        let map, marker, editMap, editMarker;

        function initMap() {
            // Default India center
            const defaultPosition = {
                lat: 20.5937,
                lng: 78.9629
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultPosition,
                zoom: 5,
            });

            // Create Draggable Marker
            marker = new google.maps.Marker({
                position: defaultPosition,
                map: map,
                draggable: true,
                title: "Drag Me"
            });

            // Update lat/lng on marker drag
            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById("lat").value = event.latLng.lat().toFixed(6);
                document.getElementById("lng").value = event.latLng.lng().toFixed(6);
            });
        }

        function initEditMap() {
            let lat = parseFloat($('#edit_lat').val());
            let lng = parseFloat($('#edit_lng').val());

            // If empty, use India center
            if (!lat || !lng) {
                lat = 20.5937;
                lng = 78.9629;
            }

            const position = {
                lat: lat,
                lng: lng
            };

            editMap = new google.maps.Map(document.getElementById("edit_map"), {
                center: position,
                zoom: lat === 20.5937 ? 5 : 12, // If default India → zoom out
            });

            editMarker = new google.maps.Marker({
                position: position,
                map: editMap,
                draggable: true,
                title: "Edit Location",
            });

            // Update inputs on drag
            google.maps.event.addListener(editMarker, 'dragend', function(event) {
                $('#edit_lat').val(event.latLng.lat().toFixed(6));
                $('#edit_lng').val(event.latLng.lng().toFixed(6));
            });

            // Click on map to move marker
            google.maps.event.addListener(editMap, 'click', function(event) {
                editMarker.setPosition(event.latLng);
                $('#edit_lat').val(event.latLng.lat().toFixed(6));
                $('#edit_lng').val(event.latLng.lng().toFixed(6));
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqOdT7uQebSHbnuZcqpWSYFtM8mryin4o&callback=initMap" async
        defer></script>
@endsection

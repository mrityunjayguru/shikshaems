@extends('layouts.master')

@section('title')
{{ __('Route Vehicles') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            {{ __('manage_route_vehicles') }}
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('route-vehicle.store') }}" method="POST" class="create-form" id="create-form"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <h4 class="card-title mb-4">{{ __('create_route_vehicle') }}</h4>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="route_id">{{ __('route') }} <span class="text-danger">*</span></label>
                                <select name="route_id" id="route_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="" selected>{{ __('select_route') }}</option>
                                    @if (isset($routes) && $routes->count() < 1)
                                        <option value="">No records found</option>
                                        @endif
                                        @foreach ($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }} @if ($route->shift)
                                            - {{ $route->shift->name }}
                                            @endif
                                        </option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="vehicle_id">{{ __('vehicle') }} <span class="text-danger">*</span></label>
                                <select name="vehicle_id" id="vehicle_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_vehicle') }}</option>
                                    @if (isset($vehicles) && $vehicles->count() < 1)
                                        <option value="">No records found</option>
                                        @endif
                                        @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="driver_id">{{ __('driver') }} <span class="text-danger">*</span></label>
                                <select name="driver_id" id="driver_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_driver') }}</option>
                                    @if (isset($drivers) && $drivers->count() < 1)
                                        <option value="">No records found</option>
                                        @endif
                                        @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->getFullNameAttribute() }}
                                        </option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="helper_id">{{ __('helper') }} <span class="text-danger">*</span></label>
                                <select name="helper_id" id="helper_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_helper') }}</option>
                                    @if (isset($helpers) && $helpers->count() < 1)
                                        @if ($helpers->count() == 1)
                                        <option value="">No records found</option>
                                        @endif
                                        @endif
                                        @foreach ($helpers as $helper)
                                        <option value="{{ $helper->id }}">{{ $helper->getFullNameAttribute() }}
                                        </option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">{{ __('role') }} <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-control" required
                                    onchange="getTeacherOrStaff()">
                                    <option value="">{{ __('Select Role') }}</option>
                                    <option value="teacher">{{ __('Teacher') }}</option>
                                    <option value="staff">{{ __('Staff') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="staff_id">{{ __('staff') }} <span class="text-danger">*</span></label>
                                {{-- <select name="staff_id" id="staff_id"
                                        class="form-control select2-dropdown select2-hidden-accessible" required>
                                        <option value="">{{ __('select_staff') }}</option>
                                @if (isset($staff) && $staff->count() < 1)
                                    @if ($staff->count() == 1)
                                    <option value="">No records found</option>
                                    @endif
                                    @endif
                                    @foreach ($staff as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->first_name . ' ' . $value->last_name }}
                                    </option>
                                    @endforeach
                                    </select> --}}
                                    <select id="user_id" class="form-control mt-2" name="staff_id">
                                        <option value="">Select</option>
                                    </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('pickup_trip_start_time') }} <span class="text-danger">*</span></label>
                                <input type="time" name="pickup_trip_start_time" class="form-control"
                                    placeholder="{{ __('pickup_trip_start_time') }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('pickup_trip_end_time') }} <span class="text-danger">*</span></label>
                                <input type="time" name="pickup_trip_end_time" class="form-control"
                                    placeholder="{{ __('pickup_trip_end_time') }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('drop_trip_start_time') }} <span class="text-danger">*</span></label>
                                <input type="time" name="drop_trip_start_time" class="form-control"
                                    placeholder="{{ __('drop_trip_start_time') }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('drop_trip_end_time') }} <span class="text-danger">*</span></label>
                                <input type="time" name="drop_trip_end_time" class="form-control"
                                    placeholder="{{ __('drop_trip_end_time') }}" required>
                            </div>
                        </div>

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

    {{-- Table --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ __('list_route_vehicles') }}
                    </h4>
                    <div class="col-12 text-right">
                        <b><a href="#" class="table-list-type active mr-2"
                                data-id="0">{{ __('all') }}</a></b> | <a href="#"
                            class="ml-2 table-list-type" data-id="1">{{ __('Trashed') }}</a>
                    </div>

                    <table class='table' id='table_list' data-toggle="table"
                        data-url="{{ route('route-vehicle.show', [1]) }}" data-click-to-select="true"
                        data-side-pagination="server" data-pagination="true"
                        data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                        data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                        data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                        data-maintain-selected="true" data-export-data-type='all'
                        data-export-options='{ "fileName": "{{ __('route_vehicle') }}-<?= date(' d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}'
                        data-show-export="true" data-query-params="schoolQueryParams" data-escape="true">
                        <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                    {{ __('id') }}
                                </th>
                                <th scope="col" data-field="no" data-sortable="true">{{ __('no.') }}</th>
                                <th scope="col" data-field="route" data-formatter="RouteNameFormatter"
                                    data-sortable="true">{{ __('route') }}</th>
                                <th scope="col" data-field="vehicle.name" data-sortable="true">
                                    {{ __('vehicle') }}
                                </th>
                                <th scope="col" data-field="driver.full_name" data-formatter="DriverNameFormatter"
                                    data-sortable="true">{{ __('driver') }}</th>
                                <th scope="col" data-field="helper.full_name" data-formatter="HelperNameFormatter"
                                    data-sortable="true">{{ __('helper') }}</th>
                                <th scope="col" data-field="status" data-sortable="true">{{ __('status') }}</th>
                                <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                    data-events="routeVehicleEvents" data-escape="false">{{ __('action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editRouteVehicleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('edit_route_vehicle') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span><i class="fa fa-close"></i></span>
                    </button>
                </div>

                <form id="edit-form" class="pt-3 edit-form" action="{{ url('route-vehicle') }}">
                    <input type="hidden" id="edit_route_vehicle_id" name="edit_route_vehicle_id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_route_id">{{ __('route') }}</label>
                                <select name="edit_route_id" id="edit_route_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_route') }}</option>
                                    @foreach ($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }} @if ($route->shift)
                                        - {{ $route->shift->name }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="edit_vehicle_id">{{ __('vehicle') }}</label>
                                <select name="edit_vehicle_id" id="edit_vehicle_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_vehicle') }}</option>
                                    @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="edit_driver_id">{{ __('Driver') }} <span
                                        class="text-danger">*</span></label>
                                <select name="edit_driver_id" id="edit_driver_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_driver') }}</option>
                                    @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->getFullNameAttribute() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="edit_helper_id">{{ __('Helper') }} <span
                                        class="text-danger">*</span></label>
                                <select name="edit_helper_id" id="edit_helper_id"
                                    class="form-control select2-dropdown select2-hidden-accessible" required>
                                    <option value="">{{ __('select_helper') }}</option>
                                    @foreach ($helpers as $helper)
                                    <option value="{{ $helper->id }}">{{ $helper->getFullNameAttribute() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group col-md-6">
                                    <label for="edit_staff_id">{{ __('staff') }} <span
                                class="text-danger">*</span></label>
                            <select name="edit_staff_id" id="edit_staff_id"
                                class="form-control select2-dropdown select2-hidden-accessible" required>
                                <option value="">{{ __('select_staff') }}</option>
                                @if (isset($staff) && $staff->count() < 1)
                                    @if ($staff->count() == 1)
                                    <option value="">No records found</option>
                                    @endif
                                    @endif
                                    @foreach ($staff as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->first_name . ' ' . $value->last_name }}
                                    </option>
                                    @endforeach
                            </select>
                        </div> --}}
                        <!-- ROLE -->
                        <div class="form-group col-md-3">
                            <label for="edit_role">{{ __('Role') }} <span
                                    class="text-danger">*</span></label>
                            <select name="edit_role" id="edit_role" class="form-control"
                                onchange="getTeacherOrStaffEdit()">
                                <option value="">Select Role</option>
                                <option value="teacher">Teacher</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <!-- USER -->
                        <div class="form-group col-md-3">
                            <label for="edit_staff_id">{{ __('staff') }} <span
                                    class="text-danger">*</span></label>
                            <select name="edit_staff_id" id="edit_user_id" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('edit_pickup_trip_start_time') }} <span
                                    class="text-danger">*</span></label>
                            <input type="time" name="edit_pickup_trip_start_time"
                                id="edit_pickup_trip_start_time" class="form-control"
                                placeholder="{{ __('edit_pickup_trip_start_time') }}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ __('edit_pickup_trip_end_time') }} <span
                                    class="text-danger">*</span></label>
                            <input type="time" name="edit_pickup_trip_end_time" id="edit_pickup_trip_end_time"
                                class="form-control" placeholder="{{ __('edit_pickup_trip_end_time') }}"
                                required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ __('edit_drop_trip_start_time') }} <span
                                    class="text-danger">*</span></label>
                            <input type="time" name="edit_drop_trip_start_time" id="edit_drop_trip_start_time"
                                class="form-control" placeholder="{{ __('edit_drop_trip_start_time') }}"
                                required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ __('edit_drop_trip_end_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="edit_drop_trip_end_time" id="edit_drop_trip_end_time"
                                class="form-control" placeholder="{{ __('edit_drop_trip_end_time') }}" required>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('close') }}</button>
                <input type="submit" class="btn btn-theme" value="{{ __('submit') }}">
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script>
    function getTeacherOrStaff() {
        const role = document.getElementById('role').value;
        if (!role) {
            $('#user_id').html('<option value="">Select</option>');
            return;
        }

        $.ajax({
            url: '/get-teacher-or-staff',
            type: 'GET',
            data: {
                role: role
            },
            dataType: 'json',
            success: function(response) {

                let options = '<option value="">Select</option>';

                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(user) {
                        options += `
                        <option value="${user.id}">
                            ${user.full_name}
                        </option>
                    `;
                    });
                }

                $('#user_id').html(options);
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Failed to load data');
            }
        });
    }

    function getTeacherOrStaffEdit(selectedUserId = null) {

        const role = $('#edit_role').val();

        if (!role) {
            $('#edit_user_id').html('<option value="">Select</option>');
            return;
        }

        $.ajax({
            url: '/get-teacher-or-staff',
            type: 'GET',
            data: {
                role: role
            },
            dataType: 'json',
            success: function(response) {

                let options = '<option value="">Select</option>';

                response.data.forEach(function(user) {
                    options += `
                    <option value="${user.id}"
                        ${selectedUserId == user.id ? 'selected' : ''}>
                        ${user.full_name}
                    </option>
                `;
                });

                $('#edit_user_id').html(options).trigger('change');
            }
        });
    }
</script>
@endsection
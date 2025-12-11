@extends('layouts.master')

@section('title')
    {{ __('vehicles') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_vehicles') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vehicles.store') }}" method="POST" class="create-form" id="create-form"
                            enctype="multipart/form-data" novalidate="novalidate">
                            @csrf
                            <h4 class="card-title mb-4">{{ __('create_vehicle') }}</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="{{ __('vehicle_name') }}" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="vehicle_number">{{ __('vehicle_number') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="vehicle_number" id="vehicle_number" class="form-control"
                                        placeholder="{{ __('vehicle_number') }}" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="capacity">{{ __('vehicle_capacity') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="capacity" id="capacity" class="form-control"
                                        placeholder="{{ __('vehicle_capacity') }}" min="1" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="vehicle_type">{{ __('vehicle_type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="vehicle_type" id="vehicle_type" class="form-control" required>
                                        <option value="" selected>Selcte Vehicle Type</option>
                                        @foreach ($vehicleType as $item)
                                            <option value="{{ $item->id }}">{{ $item->vehicle_type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="status">{{ __('status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1">{{ __('active') }}</option>
                                        <option value="0">{{ __('inactive') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mt-3" hidden>
                                    <label class="form-label">
                                        {{ __('is_device') }} <span class="text-danger">*</span>
                                    </label>

                                    <div class="d-flex">
                                        <input type="radio" class="btn-check me-1" name="is_device" id="deviceYes"
                                            value="1" autocomplete="off" onchange="showIemiInput(1)">
                                        <label class="" for="deviceYes">Yes</label>

                                        <input type="radio" class="btn-check ms-3 me-1" name="is_device" id="deviceNo"
                                            value="0" autocomplete="off" checked onchange="showIemiInput(0)">
                                        <label class="" for="deviceNo">No</label>
                                    </div>
                                </div>


                                <div class="form-group col-md-4" id="imeiDiv" hidden>
                                    <label for="iemi">{{ __('iemi') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="iemi" id="iemi" class="form-control"
                                        placeholder="{{ __('iemi') }}" min="1" required>
                                </div>
                            </div>

                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit"
                                value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list_vehicles') }}
                        </h4>
                        <div class="col-12 text-right">
                            <b><a href="#" class="table-list-type active mr-2"
                                    data-id="0">{{ __('all') }}</a></b> | <a href="#"
                                class="ml-2 table-list-type" data-id="1">{{ __('Trashed') }}</a>
                        </div>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('vehicles.show', 1) }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-maintain-selected="true" data-export-data-type='all'
                            data-export-options='{ "fileName": "{{ __('vehicle') }}-<?= date(' d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}'
                            data-show-export="true" data-query-params="schoolQueryParams" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}
                                    </th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="name">{{ __('name') }}</th>
                                    <th scope="col" data-field="vehicle_type">
                                        {{ __('vehicle_type') }}</th>
                                    <th scope="col" data-field="vehicle_icon" data-formatter="vehicleImageFormatter">
                                    {{ __('vehicle_icon') }}</th>
                                    <th scope="col" data-field="vehicle_number">{{ __('vehicle_number') }}</th>
                                    <th scope="col" data-field="capacity">{{ __('vehicle_capacity') }}</th>
                                    <th scope="col" data-field="status">
                                        {{ __('status') }}
                                    </th>
                                    <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                        data-events="vehicleEvents" data-escape="false">{{ __('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editVehicleLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVehicleLabel">{{ __('edit_vehicle') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <form id="edit-form" class="pt-3 edit-form" action="{{ url('transportation/vehicles') }}">
                        <input type="hidden" id="edit_vehicle_id" name="edit_vehicle_id">

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="edit_vehicle_name">{{ __('name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="edit_vehicle_name" id="edit_vehicle_name"
                                        class="form-control" placeholder="{{ __('vehicle_name') }}" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="edit_vehicle_number">{{ __('Vehicle') }} {{ __('number') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="edit_vehicle_number" id="edit_vehicle_number"
                                        class="form-control" placeholder="{{ __('vehicle_number') }}" required>
                                </div>

                                <div class="form-group col-md-12 ">
                                    <label for="edit_capacity">{{ __('Capacity') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="1" name="edit_capacity" id="edit_capacity"
                                        class="form-control" placeholder="{{ __('vehicle_capacity') }}" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="vehicle_type">{{ __('vehicle_type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="vehicle_type" id="edit_vehicle_type" class="form-control" required>
                                        <option value="" selected>Selcte Vehicle Type</option>
                                        @foreach ($vehicleType as $item)
                                            <option value="{{ $item->id }}">{{ $item->vehicle_type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="edit_status">{{ __('status') }}</label>
                                    <select name="edit_status" id="edit_status" class="form-control">
                                        <option value="1">{{ __('active') }}</option>
                                        <option value="0">{{ __('inactive') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 mt-3" hidden>
                                    <label class="form-label">
                                        {{ __('is_device') }} <span class="text-danger">*</span>
                                    </label>

                                    <div class="d-flex">
                                        <input type="radio" class="btn-check me-1" name="edit_is_device"
                                            id="deviceYesEdit" value="1" autocomplete="off"
                                            onchange="showIemiInputEdit(1)">
                                        <label class="" for="deviceYesEdit">Yes</label>

                                        <input type="radio" class="btn-check ms-3 me-1" name="edit_is_device"
                                            id="deviceNoEdit" value="0" autocomplete="off" checked
                                            onchange="showIemiInputEdit(0)">
                                        <label class="" for="deviceNoEdit">No</label>
                                    </div>
                                </div>


                                <div class="form-group col-md-12" id="imeiDivEdit" hidden>
                                    <label for="edit_iemi">{{ __('iemi') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="edit_iemi" id="edit_iemi" class="form-control"
                                        placeholder="{{ __('iemi') }}" min="1" required>
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
        function showIemiInput(isDevice) {
            const imeiDiv = document.getElementById('imeiDiv');
            const imeiInput = document.getElementById('iemi');

            if (isDevice == 1) {
                imeiDiv.hidden = false; // show div
                imeiInput.required = true; // make required
            } else {
                imeiDiv.hidden = true; // hide div
                imeiInput.required = false; // remove required
                imeiInput.value = ''; // clear value
            }
        }

        function showIemiInputEdit(isDevice) {
            const imeiDiv = document.getElementById('imeiDivEdit');
            const imeiInput = document.getElementById('edit_iemi');

            if (isDevice == 1) {
                imeiDiv.hidden = false; // show div
                imeiInput.required = true; // make required
            } else {
                imeiDiv.hidden = true; // hide div
                imeiInput.required = false; // remove required
                imeiInput.value = ''; // clear value
            }
        }
    </script>
@endsection

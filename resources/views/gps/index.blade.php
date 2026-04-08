@extends('layouts.master')

@section('title')
    {{ __('GPS') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage GPS') }}
            </h3>
        </div>
        @role('Super Admin')
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('gps.store') }}" method="POST" class="create-form" id="create-form"
                                enctype="multipart/form-data" novalidate="novalidate">
                                @csrf
                                <h4 class="card-title mb-4">{{ __('Create GPS') }}</h4>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="device_type">{{ __('Device Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="device_type_id" id="device_type" class="form-control">
                                            <option value="">Select Device Type</option>
                                            @foreach ($deviceType as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="imei_no">{{ __('IMEI No.') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="imei_no" id="imei_no" class="form-control"
                                            placeholder="IMEI No." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="sim_no">{{ __('SIM No.') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="sim_no" id="sim_no" class="form-control"
                                            placeholder="SIM No." required>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                    <label for="wired_device">{{ __('Wired Device') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="wired_device" id="wired_device" class="form-control">
                                        <option value="1">True</option>
                                        <option value="0">False</option>
                                    </select>
                                </div> --}}
                                </div>
                                <div class="mt-3">
                                    <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset"
                                        value={{ __('reset') }}
                                        style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                                    <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                        value={{ __('submit') }}
                                        style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            List GPS
                        </h4>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('gps.show', 1) }}" data-click-to-select="true" data-side-pagination="server"
                            data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all'
                            data-export-options='{ "fileName": "{{ __('GPS') }}-<?= date(' d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}'
                            data-show-export="true" data-query-params="schoolQueryParams" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}
                                    </th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="device_type.name">
                                        {{ __('Device Type') }}</th>

                                    <th scope="col" data-field="imei_no">{{ __('IMEI No.') }}</th>
                                    <th scope="col" data-field="sim_no">{{ __('SIM No.') }}</th>
                                    <th scope="col" data-field="status">{{ __('Status') }}</th>
                                    <th scope="col" data-field="school.name">{{ __('School Name') }}</th>
                                    <th scope="col" data-field="created_at">{{ __('created_at') }}</th>
                                    <th scope="col" data-field="assigned_on">{{ __('Assigned at') }}</th>
                                    <th scope="col" data-field="vehicle_number">{{ __('Vehicle Number') }}</th>

                                    <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                        data-events="gpsEvents" data-escape="false">{{ __('action') }}</th>
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
                        <h5 class="modal-title" id="editVehicleLabel">{{ __('Edit GPS Details') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <form id="edit-form" class="pt-3 edit-form" action="{{ route('vehicle-type.store') }}">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="edit_device_type_id">{{ __('Device Type') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="edit_device_type_id" id="edit_device_type_id" class="form-control">
                                        <option value="">Select Device Type</option>
                                        @foreach ($deviceType as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="edit_imei_no">{{ __('IMEI No.') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="edit_imei_no" id="edit_imei_no" class="form-control"
                                        placeholder="IMEI No." required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="edit_sim_no">{{ __('SIM No.') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="edit_sim_no" id="edit_sim_no" class="form-control"
                                        placeholder="SIM No." required>
                                </div>
                                {{-- <div class="form-group col-md-12">
                                    <label for="edit_wired_device">{{ __('Wired Device') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="edit_wired_device" id="edit_wired_device" class="form-control">
                                        <option value="1">True</option>
                                        <option value="0">False</option>
                                    </select>
                                </div> --}}
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

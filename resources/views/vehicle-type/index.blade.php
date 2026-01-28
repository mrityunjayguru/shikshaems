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
                        <form action="{{ route('vehicle-type.store') }}" method="POST" class="create-form" id="create-form"
                            enctype="multipart/form-data" novalidate="novalidate">
                            @csrf
                            <h4 class="card-title mb-4">{{ __('create_vehicle_type') }}</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="vehicle_type">{{ __('vehicle_type') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="vehicle_type" class="form-control"
                                        placeholder="{{ __('vehicle_type') }}" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="icon">{{ __('vehicle_icon') }}<span class="text-danger">*</span></label>
                                    {{-- <input type="file" name="icon" id="icon" class="form-control" required> --}}
                                    <input type="file" name="vehicle_icon" accept="image/*" class="file-upload-default"
                                        required />
                                    <div class="input-group col-xs-12">
                                        <input type="text" id="icon" class="form-control file-upload-info"
                                            disabled="" placeholder="{{ __('vehicle_icon') }}" />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            </div>
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
                             List Vehicle Types
                        </h4>
                        {{-- <div class="col-12 text-right">
                            <b><a href="#" class="table-list-type active mr-2"
                                    data-id="0">{{ __('all') }}</a>
                                </b> | <a href="#"
                                class="ml-2 table-list-type" data-id="1">{{ __('Trashed') }}</a>
                        </div> --}}
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('vehicle-type.show', 1) }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all'
                            data-export-options='{ "fileName": "{{ __('vehicle') }}-<?= date(' d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}'
                            data-show-export="true" data-query-params="schoolQueryParams" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}
                                    </th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="vehicle_icon" data-formatter="vehicleImageFormatter">
                                        {{ __('vehicle_icon') }}</th>

                                    <th scope="col" data-field="vehicle_type">{{ __('vehicle_type') }}</th>

                                    <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                        data-events="vehicleTypeEvents" data-escape="false">{{ __('action') }}</th>
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
                        <h5 class="modal-title" id="editVehicleLabel">{{ __('edit_vehicle_type') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <form id="edit-form" class="pt-3 edit-form" action="{{ route('vehicle-type.store') }}">
                        <input type="hidden" id="edit_vehicle_type_id" name="id" >

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="edit_vehicle_name">{{ __('name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="vehicle_type" id="edit_vehicle_type"
                                        class="form-control" placeholder="{{ __('vehicle_name') }}" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="vehicle_icon">{{ __('vehicle_icon') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="edit_vehicle_icon" name="vehicle_icon" class="file-upload-default"
                                        accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/svg" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" id="vehicle_icon" class="form-control" disabled=""
                                            value="" />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
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

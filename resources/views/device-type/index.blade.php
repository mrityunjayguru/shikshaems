@extends('layouts.master')

@section('title')
    {{ __('Device Type') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Device Type') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('device-type.store') }}" method="POST" class="create-form" id="create-form"
                            enctype="multipart/form-data" novalidate="novalidate">
                            @csrf
                            <h4 class="card-title mb-4">{{ __('Create Device Type') }}</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Device Type Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="device_type">{{ __('Device Type') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="device_type" id="device_type" class="form-control">
                                        <option value="0">Wireless</option>
                                        <option value="1">Wired</option>
                                    </select>
                                </div>
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
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            List Device Type
                        </h4>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('device-type.show', 1) }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all'
                            data-export-options='{ "fileName": "{{ __('Device Type') }}-<?= date(' d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}'
                            data-show-export="true" data-query-params="schoolQueryParams" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}
                                    </th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="name">{{ __('Name') }}</th>
                                    <th scope="col" data-field="device_type">{{ __('Type') }}</th>
                                    <th scope="col" data-field="created_at" data-visible="false">{{ __('created_at') }}
                                    </th>

                                    <th scope="col" data-field="operate" data-formatter="actionColumnFormatter"
                                        data-events="deviceTypeEvents" data-escape="false">{{ __('action') }}</th>
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
                        <h5 class="modal-title" id="editVehicleLabel">{{ __('Edit Device Type') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <form id="edit-form" class="pt-3 edit-form" action="{{ route('device-type.store') }}">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="edit_name">{{ __('Name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="edit_name" id="edit_name" class="form-control"
                                        placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="edit_device_type">{{ __('Device Type') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="edit_device_type" id="edit_device_type" class="form-control">
                                        <option value="0">Wireless</option>
                                        <option value="1">Wired</option>
                                    </select>
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

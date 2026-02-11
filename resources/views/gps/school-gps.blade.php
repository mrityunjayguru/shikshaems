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
       <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            List GPS
                        </h4>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('school.gps.show', 1) }}" data-click-to-select="true" data-side-pagination="server"
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
                                    <th scope="col" data-field="imei_no">{{ __('IMEI No.') }}</th>
                                    <th scope="col" data-field="type">{{ __('Type') }}</th>
                                    <th scope="col" data-field="vehicle_number">{{ __('Vehicle Number') }}</th>
                                    <th scope="col" data-field="status">{{ __('Assigned') }}</th>
                                    <th scope="col" data-field="gps_status">{{ __('Status') }}</th>
                                    <th scope="col" data-field="created_at" data-visible="false">{{ __('created_at') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

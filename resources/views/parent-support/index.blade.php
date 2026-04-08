@extends('layouts.master')

@section('title')
    {{ __('event') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('Supprt') }}
            </h3>
        </div>

        <div class="row">
           
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('Parents Support') }}
                        </h4>
                      
                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('parent-support.show', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                                    data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false"
                                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                    data-maintain-selected="true" data-export-data-type='all' data-show-export="true"
                                    data-export-options='{ "fileName": "support-list-<?= date('d-m-y') ?>","ignoreColumn":
                                    ["operate"]}'
                                    data-query-params="holidayQueryParams">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }} </th>
                                            <th scope="col" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-field="child.full_name" data-width="150"> {{ __('Child') }}
                                            </th>
                                            <th scope="col" data-field="subject">{{ __('subject') }} </th>
                                            <th scope="col" data-events="tableDescriptionEvents"
                                                data-formatter="descriptionFormatter" data-field="message">
                                                {{ __('message') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection

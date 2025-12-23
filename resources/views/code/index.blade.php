@extends('layouts.master')

@section('title')
    {{ __('code') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('code') }}
            </h3>
        </div>

        <div class="row">
            {{-- @if (Auth::user()->can('code-create')) --}}
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('code') }}
                        </h4>
                        <form class="create-form pt-3" id="create-form" action="{{ route('code.store') }}" method="POST"
                            novalidate="novalidate">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('code') }} <span class="text-danger">*</span></label>
                                    {!! Form::number('code', null, [
                                        'required',
                                        'placeholder' => __('code'),
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                    <span class="input-group-addon input-group-append">
                                    </span>
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('end_date') }} <span class="text-danger">*</span></label>
                                    {!! Form::date('end_date', null, ['required', 'placeholder' => __('end_date'), 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit"
                                value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('code') }}
                        </h4>
                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('code.show', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                                    data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false"
                                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                    data-maintain-selected="true" data-export-data-type='all' data-show-export="true"
                                    data-export-options='{ "fileName": "holiday-list-<?= date('d-m-y') ?>","ignoreColumn":
                                    ["operate"]}'
                                    data-query-params="holidayQueryParams">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }} </th>
                                            <th scope="col" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-field="code" data-width="150"> {{ __('code') }}
                                            </th>
                                            <th scope="col" data-field="end_date">{{ __('end_date') }} </th>
                                            <th data-events="codeEvents" data-width="150" scope="col"
                                                data-field="operate">{{ __('action') }}</th>
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


    <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('edit_code') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
                </div>
                <form id="formdata" class="edit-form" action="{{ url('code') }}" novalidate="novalidate">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('code') }} <span class="text-danger">*</span></label>
                                {!! Form::text('code', null, [
                                    'required',
                                    'placeholder' => __('code'),
                                    'class' => 'form-control',
                                    'id' => 'edit-code',
                                ]) !!}
                                <span class="input-group-addon input-group-append">
                                </span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('end_date') }} <span class="text-danger">*</span></label>
                                {!! Form::date('end_date', null, [
                                    'required',
                                    'placeholder' => __('end_date'),
                                    'class' => 'form-control',
                                    'id' => 'edit_end_date',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                        <input class="btn btn-theme" type="submit" value={{ __('submit') }}>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- <script>
        let schoolDateFormat = "{{ $schoolSettings['date_format'] }}";
        $(function() {
            const sessionStart = "{{ format_date($current_sessionYear->start_date) }}"; // e.g. 2024-04-01
            const sessionEnd = "{{ format_date($current_sessionYear->end_date) }}"; // e.g. 2025-03-31

            $('.datepicker-popup').datepicker({
                format: 'dd-mm-yyyy',
                startDate: sessionStart,
                endDate: sessionEnd,
                autoclose: true,
                todayHighlight: true
            });
        });

        function sendNotification() {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to send notification!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Send!",
                        text: "Notification has been send.",
                        icon: "success"
                    });
                }
            });
        }
    </script> --}}
@endsection

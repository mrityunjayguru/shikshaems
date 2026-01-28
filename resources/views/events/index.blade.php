@extends('layouts.master')

@section('title')
    {{ __('event') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('event') }}
            </h3>
        </div>

        <div class="row">
            {{-- @if (Auth::user()->can('event-create')) --}}
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('event') }}
                        </h4>
                        <form class="create-form pt-3" id="create-form" action="{{ route('event.store') }}" method="POST"
                            novalidate="novalidate">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('date') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('date', null, [
                                        'required',
                                        'placeholder' => __('date'),
                                        'class' => 'datepicker-popup form-control',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                    <span class="input-group-addon input-group-append">
                                    </span>
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('title', null, ['required', 'placeholder' => __('title'), 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }}</label>
                                    {!! Form::textarea('description', null, [
                                        'rows' => '2',
                                        'placeholder' => __('description'),
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <input class="btn btn-theme float-right ml-3" type="button" onclick="sendNotification()"
                                    value="Send Notification">
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
            {{-- @endif --}}
            {{-- @if (Auth::user()->can('holiday-list')) --}}
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('event') }}
                        </h4>
                        {{-- <div class="row" id="toolbar">
                                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3">
                                    <label for="filter_session_year_id" class="filter-menu">{{__("session_year")}}</label>
                                    <select name="filter_session_year_id" id="filter_session_year_id" class="form-control">
                                        @foreach ($sessionYears as $sessionYear)
                                            <option value="{{ $sessionYear->id }}" {{$sessionYear->default == 1 ? "selected" : ""}}>
                                                {{ $sessionYear->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3">
                                    <label for="filter_month_id" class="filter-menu">{{__("month")}}</label>
                                    {!! Form::select('month', ['0' => 'All'] + $months, null, ['class' => 'form-control', 'id' => 'filter_month']) !!}
                                </div>
                            </div> --}}
                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('event.show', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                                    data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false"
                                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                    data-maintain-selected="true" data-export-data-type='all' data-show-export="true"
                                    data-export-options='{ "fileName": "event-list-<?= date('d-m-y') ?>","ignoreColumn":
                                    ["operate"]}'
                                    data-query-params="holidayQueryParams">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }} </th>
                                            <th scope="col" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-field="date" data-width="150"> {{ __('date') }}
                                            </th>
                                            <th scope="col" data-field="title">{{ __('title') }} </th>
                                            <th scope="col" data-events="tableDescriptionEvents"
                                                data-formatter="descriptionFormatter" data-field="desc">
                                                {{ __('description') }}</th>
                                            {{-- @if (Auth::user()->can('holiday-edit') || Auth::user()->can('holiday-delete')) --}}
                                                <th data-events="eventEvents" data-width="150" scope="col"
                                                    data-field="operate">{{ __('action') }}</th>
                                            {{-- @endif --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>


    <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('edit_event') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
                </div>
                <form id="formdata" class="edit-form" action="{{ url('event') }}" novalidate="novalidate">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('date') }} <span class="text-danger">*</span></label>
                                {!! Form::text('date', null, [
                                    'required',
                                    'placeholder' => __('date'),
                                    'class' => 'datepicker-popup form-control',
                                    'id' => 'edit-date',
                                ]) !!}
                                <span class="input-group-addon input-group-append">
                                </span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                {!! Form::text('title', null, [
                                    'required',
                                    'placeholder' => __('title'),
                                    'class' => 'form-control',
                                    'id' => 'edit-title',
                                ]) !!}
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('description') }}</label>
                                {!! Form::textarea('description', null, [
                                    'placeholder' => __('description'),
                                    'class' => 'form-control',
                                    'id' => 'edit-description',
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
    <script>
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
    </script>
@endsection

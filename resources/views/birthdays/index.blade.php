@extends('layouts.master')

@section('title')
    {{ __('guidance') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('birthdays') }}
            </h3>
        </div>

        <div class="row">
            {{-- @if (Auth::user()->can('guidance-list')) --}}
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('birthdays') }}
                        </h4>
                        <div class="row mt-3" id="toolbar">
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="filter-menu">{{ __('Class') }} <span class="text-danger">*</span></label>
                                <select name="class_id" id="filter_class_id" class="form-control">
                                    <option value="">{{ __('all_class') }}</option>
                                    @foreach ($classes as $class)
                                        <option value={{ $class->id }}>{{ $class->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="filter-menu">{{ __('class_section') }} <span
                                        class="text-danger">*</span></label>
                                <select required name="filter_class_section_id" class="form-control"
                                    id="filter_class_section_id">
                                    <option value="">{{ __('all_section') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                 <label class="filter-menu">{{ __('month') }} <span
                                        class="text-danger">*</span></label>
                                <select name="filter_month" class="form-control" id="filter_month">
                                    <option value="">Month</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('students.birthdays.show', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                                    data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false"
                                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                    data-maintain-selected="true" data-export-types='["txt","excel"]'
                                    data-export-options='{ "fileName": "guidance-list-<?= date('d-m-y') ?>","ignoreColumn":
                                    ["operate"]}'
                                    data-query-params="birthdaysQueryParams" data-escape="true">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }} </th>
                                            <th scope="col" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-field="full_name">{{ __('name') }} </th>
                                            <th scope="col" data-field="class_section.full_name">
                                                {{ __('class_section') }}</th>
                                            <th scope="col" data-field="user.gender">{{ __('gender') }}</th>
                                            <th scope="col" data-field="user.dob">{{ __('dob') }}</th>
                                            <th scope="col" data-field="guardian.full_name">
                                                {{ __('guardian') . ' ' . __('name') }}</th>
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
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('edit') . ' ' . __('guidance') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
                </div>
                <form id="formdata" class="edit-form" action="{{ url('guidances') }}" novalidate="novalidate">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                {!! Form::text('name', null, [
                                    'required',
                                    'placeholder' => __('name'),
                                    'class' => 'form-control',
                                    'id' => 'edit-name',
                                    'maxlength' => '30',
                                ]) !!}
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12">
                                <label>{{ __('link') }} <span class="text-danger">*</span></label>
                                {!! Form::text('link', null, [
                                    'required',
                                    'placeholder' => __('link'),
                                    'class' => 'form-control',
                                    'id' => 'edit-link',
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

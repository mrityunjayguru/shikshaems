@extends('layouts.master')

@section('title')
{{ __('set_proximity') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            {{ __('set_proximity') }}
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('proximity.store') }}" method="POST" class="create-form" id="create-form"
                        enctype="multipart/form-data" novalidate="novalidate">
                        @csrf
                        <h4 class="card-title mb-4">{{ __('update_proximity') }}</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="stop_proximity">{{ __('stop_proximity') }} <span class="text-danger">*</span></label>
                                <input type="number" name="stop_proximity" id="stop_proximity" class="form-control"
                                    placeholder="{{ __('stop_proximity') }}" value="{{ isset($proximity) ? $proximity->stop_proximity : ''}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="notification_proximity">{{ __('notification_proximity') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="notification_proximity" id="notification_proximity" class="form-control" value="{{ isset($proximity) ? $proximity->notification_proximity : ''}}"
                                    placeholder="{{ __('notification_proximity') }}" required>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ isset($proximity) ? $proximity->id : ''}}">
                        <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('update') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
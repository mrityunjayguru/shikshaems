@extends('layouts.master')

@section('title')
    {{ __('Edit Syllabus') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Edit Syllabus') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-sm btn-theme" href="{{ route('syllabus.index') }}">{{ __('back') }}</a>
                        </div>
                        <h4 class="card-title">{{ __('Edit Syllabus Details') }}</h4>
                        <form class="pt-3 subject-create-form" id="edit-form"
                            action="{{ route('syllabus.update', $syllabus->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <!-- Class -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Class') }} <span class="text-danger">*</span></label>
                                        <select name="class_id" class="form-control">
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ $syllabus->class_id == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Subject') }} <span class="text-danger">*</span></label>
                                        <select name="subject_id" class="form-control">
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}"
                                                    {{ $syllabus->subject_id == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Repeater -->
                                <div class="col-md-12">
                                    <label>{{ __('Syllabus Content') }}</label>
                                    <hr>

                                    <div class="syllabus-repeater">
                                        <div data-repeater-list="syllabus_contents">

                                            @foreach ($syllabus->contents as $content)
                                                <div data-repeater-item>
                                                    <div class="row">
                                                        <input type="hidden" name="id" value="{{ $content->id }}">

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="title"
                                                                    value="{{ $content->title }}" class="form-control"
                                                                    placeholder="{{ __('title') }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>{{ __('Description') }}</label>
                                                                <textarea name="description" class="form-control" 
                                                                    placeholder="{{ __('description') }}" required style="height: 100px">{{ $content->description }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 mt-4" data-repeater-delete>
                                                            <button type="button" class="btn btn-inverse-danger btn-icon">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success" data-repeater-create>
                                                <i class="fa fa-plus"></i> {{ __('Add More') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <button class="btn btn-theme float-right ml-3" type="submit">
                                {{ __('Update') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.syllabus-repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'id': '',
                    'title': '',
                    'description': ''
                },
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    let el = $(this);
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This item will be removed',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, remove it',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            el.slideUp(deleteElement);
                        }
                    });
                }
            });
        });
    </script>
@endsection

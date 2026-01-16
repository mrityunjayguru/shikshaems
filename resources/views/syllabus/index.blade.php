@extends('layouts.master')

@section('title')
    {{ __('Syllabus') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('Syllabus') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('Syllabus') }}
                        </h4>
                        <form class="pt-3 subject-create-form" id="create-form" action="{{ route('syllabus.store') }}"
                            method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Class') }} <span class="text-danger">*</span></label>
                                        <select name="class_id" id="class_id" class="form-control">
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('subject') }} <span class="text-danger">*</span></label>
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                        <input name="title" type="text" placeholder="{{ __('title') }}"
                                            class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" placeholder="{{ __('description') }}"
                                            class="form-control"></textarea>
                                    </div>
                                </div> --}}
                                <!-- Title & Description Repeater -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('Syllabus Content') }} <span class="text-danger">*</span></label>
                                        <hr>

                                        <div class="syllabus-repeater">
                                            <div data-repeater-list="syllabus_contents">

                                                <div data-repeater-item>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="title" class="form-control"
                                                                    placeholder="{{ __('title') }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>{{ __('Description') }}</label>
                                                                <textarea name="description" class="form-control" placeholder="{{ __('description') }}" required  style="height: 100px"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 mt-4" data-repeater-delete>
                                                            <button type="button" class="btn btn-inverse-danger btn-icon">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-success" data-repeater-create>
                                                    <i class="fa fa-plus"></i> {{ __('Add More') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit"
                                value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('list') . ' ' . __('Syllabus') }}</h4>
                        <div id="toolbar">
                            <select name="filter_class_id" id="filter_class_id" class="form-control">
                                <option value="">All</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                            data-url="{{ route('syllabus.show', [1]) }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                            data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-maintain-selected="true" data-export-data-type='all'
                            data-query-params="SyllabusQueryParams" data-toolbar="#toolbar"
                            data-export-options='{ "fileName": "subject-list-<?= date('d-m-y') ?>"
                            ,"ignoreColumn":["operate"]}' data-show-export="true" data-escape="true">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                        {{ __('id') }}</th>
                                    <th scope="col" data-field="no">{{ __('no.') }}</th>
                                    <th scope="col" data-field="class.name">{{ __('class') }}</th>
                                    <th scope="col" data-field="subject.name">{{ __('subject') }}</th>

                                    <th scope="col" data-field="created_at" data-sortable="true"
                                        data-visible="false">
                                        {{ __('created_at') }}</th>
                                    <th scope="col" data-field="updated_at" data-sortable="true"
                                        data-visible="false">
                                        {{ __('updated_at') }}</th>
                                    <th scope="col" data-field="operate" data-events="syllabusEvents"
                                        data-escape="false">{{ __('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="syllabusModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Syllabus Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="syllabusModalBody">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.syllabus-repeater').repeater({
                initEmpty: false,
                defaultValues: {
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
                        text: "This item will be removed!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, remove it',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            el.slideUp(deleteElement);

                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Item removed successfully.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.view-syllabus', function() {
            let id = $(this).data('id');

            $('#syllabusModal').modal('show');

            $.get("{{ route('syllabus.details', '') }}/" + id, function(res) {

                let html = `
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Class : </strong>${res.class?.name ?? '-'}</label>
                                 </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><strong>Subject : </strong>${res.subject?.name ?? '-'}</label>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 30%">Title</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;          

                if (res.contents.length > 0) {
                    res.contents.forEach((c, i) => {
                        html += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${c.title}</td>
                                    <td>${c.description}</td>
                                </tr>
                            `;
                    });
                } else {
                    html += `
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No syllabus content available
                                </td>
                            </tr>
                        `;
                }

                html += `
                        </tbody>
                    </table>
                `;

                $('#syllabusModalBody').html(html);


                $('#syllabusModalBody').html(html);
            });
        });
    </script>
@endsection

@extends('layouts.master')

@section('title')
    {{ __('upload_profile') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Students') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3" id="create-form" enctype="multipart/form-data" action="{{ route('students.update-profile') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="row" id="toolbar">
                                        <div class="form-group col-sm-12 col-md-4">
                                            <label class="filter-menu">{{ __('Class Section') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="filter_class_section_id" id="filter_class_section_id"
                                                class="form-control">
                                                <option value="">{{ __('select_class_section') }}</option>
                                                @foreach ($class_sections as $class_section)
                                                    <option value={{ $class_section->id }}>{{ $class_section->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                        data-url="{{ route('students.list', [1]) }}" data-click-to-select="true"
                                        data-side-pagination="server" data-pagination="false"
                                        data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                                        data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                                        data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                                        data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                                        data-sort-order="desc" data-maintain-selected="true"
                                        data-query-params="studentDetailsQueryParams" data-show-export="true"
                                        data-export-options='{"fileName": "section-list-<?= date('d-m-y') ?>
                                        ","ignoreColumn": ["operate"]}'
                                        data-escape="true">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="id" data-sortable="false" data-visible="false">{{ __('id') }}</th>
                                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                                <th scope="col" data-field="user.image" data-formatter="imageFormatter"> {{ __('image') }}</th>
                                                <th scope="col" data-field="user.id" data-visible="false"> {{ __('User Id') }}</th>
                                                <th scope="col" data-field="user.full_name">{{ __('name') }}</th>
                                                <th scope="col" data-field="roll_number">{{ __('roll_no') }}</th>
                                                <th scope="col" data-field="user.gender" data-visible="false"> {{ __('gender') }}</th>

                                                <th scope="col" data-field="guardian.image" data-formatter="imageFormatter">{{ __('guardian') }} {{ __('image') }}</th>
                                                <th scope="col" data-field="guardian.id" data-visible="false"> {{ __('guardian_user_id') }} {{ __('image') }}</th>
                                                <th scope="col" data-field="guardian.email"> {{ __('guardian') . ' ' . __('email') }}</th>
                                                <th scope="col" data-field="guardian.full_name"> {{ __('guardian') . ' ' . __('name') }}</th>
                                                <th scope="col" data-field="guardian.mobile"> {{ __('guardian') . ' ' . __('mobile') }}</th>
                                                <th scope="col" data-field="guardian.gender" data-visible="false"> {{ __('guardian') . ' ' . __('gender') }}</th>

                                                <th scope="col" data-formatter="studentImageFormatter" data-field="student.profile">{{ __('student') . ' ' . __('profile') }}
                                                </th>
                                                <th scope="col" data-formatter="guardianImageFormatter" data-field="guardian.profile"> {{ __('guardian') . ' ' . __('profile') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group col-sm-12 mt-3">
                                <input class="btn btn-theme submit_bulk_file float-right" type="submit" value="{{ __('submit') }}" name="submit"
                                    id="submit_bulk_file">
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- Bulk Profile Crop Modal --}}
    <div class="modal fade" id="bulkCropModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image</h6>
                    <button type="button" class="close" id="bulkCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="bulk_crop_preview" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="bulkCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="bulkCropDone">Crop & Use</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection

@section('script')
    <link rel="stylesheet" href="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css' }}"/>
    <script src="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js' }}"></script>
    <script>
        let bulkCropper = null;
        let bulkCropMeta = null; // { type: 'student'|'guardian', id: '...' }

        // Delegate file input change for dynamically rendered rows
        $(document).on('change', '.bulk-student-file-input', function() {
            if (!this.files || !this.files[0]) return;
            const uid = $(this).data('uid');
            bulkCropMeta = { type: 'student', id: uid };
            openBulkCrop(this.files[0]);
            $(this).val('');
        });

        $(document).on('change', '.bulk-guardian-file-input', function() {
            if (!this.files || !this.files[0]) return;
            const gid = $(this).data('gid');
            bulkCropMeta = { type: 'guardian', id: gid };
            openBulkCrop(this.files[0]);
            $(this).val('');
        });

        function openBulkCrop(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('bulk_crop_preview');
                img.src = e.target.result;
                if (bulkCropper) { bulkCropper.destroy(); bulkCropper = null; }
                $('#bulkCropModal').modal('show');
                $('#bulkCropModal').one('shown.bs.modal', function() {
                    bulkCropper = new Cropper(img, { aspectRatio: 308/338, viewMode: 1, autoCropArea: 1, minContainerHeight: 280, maxContainerHeight: 320 });
                });
            };
            reader.readAsDataURL(file);
        }

        $('#bulkCropDone').on('click', function() {
            if (!bulkCropper || !bulkCropMeta) return;
            const dataUrl = bulkCropper.getCroppedCanvas({ width: 308, height: 338 }).toDataURL('image/jpeg', 0.9);
            const { type, id } = bulkCropMeta;
            if (type === 'student') {
                $('#sc_student_cropped_' + id).val(dataUrl);
                $('#sc_student_preview_' + id).attr('src', dataUrl).show();
            } else {
                $('#sc_guardian_cropped_' + id).val(dataUrl);
                $('#sc_guardian_preview_' + id).attr('src', dataUrl).show();
            }
            bulkCropper.destroy(); bulkCropper = null;
            bulkCropMeta = null;
            $('#bulkCropModal').modal('hide');
        });

        $('#bulkCropClose, #bulkCropClose2').on('click', function() {
            if (bulkCropper) { bulkCropper.destroy(); bulkCropper = null; }
            bulkCropMeta = null;
            $('#bulkCropModal').modal('hide');
        });
    </script>
@endsection

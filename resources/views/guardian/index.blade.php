@extends('layouts.master')

@section('title')
    {{ __('Guardian') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('Guardian') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('Guardian') }}
                        </h4>
                        <div class="row" id="toolbar">
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="filter-menu">{{ __('Class') }} <span class="text-danger">*</span></label>
                                <select name="filter_class_id" id="filter_class_id" class="form-control">
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
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('guardian.show', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-search="true"
                                    data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                                    data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all'
                                    data-show-export="true"
                                    data-export-options='{ "fileName": "guardian-list-<?= date('d-m-y') ?>" ,"ignoreColumn":
                                    ["operate"]}'
                                    data-query-params="guardianQueryParams" data-escape="true">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-sortable="true" data-visible="false" data-align="center"
                                                data-field="id"> {{ __('id') }}</th>
                                            <th scope="col" data-align="center" data-field="no"> {{ __('no.') }}
                                            </th>
                                            <th scope="col" data-field="user.full_name"
                                                data-formatter="GuardianNameFormatter"> {{ __('name') }} </th>
                                            <th scope="col" data-align="center" data-events="guardianEvents" data-escape="false" data-field="wardsOperate"> {{ __('Wards') }}
                                            </th>
                                            <th scope="col" data-align="center" data-field="gender"> {{ __('gender') }}
                                            </th>
                                            <th scope="col" data-align="center" data-field="mobile"> {{ __('mobile') }}
                                            </th>
                                            <th data-events="guardianEvents" data-align="center" scope="col" data-field="operate"
                                                data-escape="false"> {{ __('action') }} </th>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('edit') . ' ' . __('Guardian') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
                </div>
                <form id="edit-form" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                                <label>{{ __('first_name') }} <span class="text-danger">*</span></label>
                                {!! Form::text('first_name', null, [
                                    'required',
                                    'placeholder' => __('first_name'),
                                    'class' => 'form-control',
                                    'id' => 'first_name',
                                ]) !!}
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                <label>{{ __('last_name') }} <span class="text-danger">*</span></label>
                                {!! Form::text('last_name', null, [
                                    'required',
                                    'placeholder' => __('last_name'),
                                    'class' => 'form-control',
                                    'id' => 'last_name',
                                ]) !!}
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                <label>{{ __('email') }} <span class="text-danger">*</span></label>
                                {!! Form::text('email', null, [
                                    'required',
                                    'placeholder' => __('email'),
                                    'class' => 'form-control',
                                    'id' => 'email',
                                ]) !!}
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                <label>{{ __('mobile') }} <span class="text-danger">*</span></label>
                                {!! Form::number('mobile', null, [
                                    'required',
                                    'placeholder' => __('mobile'),
                                    'min' => 0,
                                    'class' => 'form-control',
                                    'id' => 'mobile',
                                ]) !!}
                            </div>

                            <div class="form-group col-sm-12 col-md-12">
                                <label>{{ __('gender') }} <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('gender', 'male', null, ['class' => 'form-check-input edit', 'id' => 'male']) !!}
                                            {{ __('male') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            {!! Form::radio('gender', 'female', null, ['class' => 'form-check-input edit', 'id' => 'female']) !!}
                                            {{ __('female') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                <label for="image">{{ __('image') }} </label>
                                <input type="hidden" name="image_cropped" id="guardian_edit_image_cropped">
                                <input type="file" id="guardian_edit_image_input" class="d-none" accept="image/png,image/jpeg,image/jpg"/>
                                <div class="input-group col-xs-12">
                                    <input type="text" id="image" class="form-control file-upload-info"
                                        disabled="" placeholder="{{ __('image') }}" />
                                    <span class="input-group-append">
                                        <button class="btn btn-theme" type="button" onclick="document.getElementById('guardian_edit_image_input').click()">{{ __('upload') }}</button>
                                    </span>
                                </div>
                                <div style="width:60px;" class="mt-1">
                                    <img src="" id="edit_guardian_img_tag" class="img-fluid w-100" alt=""/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4">
                                <div class="d-flex">
                                    <div class="form-check w-fit-content">
                                        <label class="form-check-label ml-4">
                                            <input type="checkbox" class="form-check-input" name="reset_password"
                                                value="1">{{ __('reset_password') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-4">Wards</h5>
                        <div class="row" id="childDetails"></div>
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

    <div class="modal fade" id="wardsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Wards Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" id="wardsDetails"></div>
            </div>

        </div>
    </div>
</div>

    {{-- Guardian Crop Modal --}}
    <div class="modal" id="guardianCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image</h6>
                    <button type="button" class="close" id="guardianCropClose" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="guardian_crop_preview" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="guardianCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="guardianCropDone">Crop & Use</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <link rel="stylesheet" href="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css' }}"/>
    <script src="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js' }}"></script>
    <script>
        let guardianCropper = null;

        $(document).ready(function() {
            $('#guardian_edit_image_input').on('change', function() {
                if (!this.files || !this.files[0]) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('guardian_crop_preview');
                    img.src = e.target.result;
                    if (guardianCropper) { guardianCropper.destroy(); guardianCropper = null; }
                    $('#guardianCropModal').modal('show');
                    $('#guardianCropModal').one('shown.bs.modal', function() {
                        guardianCropper = new Cropper(img, { aspectRatio: 308/338, viewMode: 1, autoCropArea: 1, minContainerHeight: 280, maxContainerHeight: 320 });
                    });
                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#guardianCropDone').on('click', function() {
                if (!guardianCropper) return;
                const dataUrl = guardianCropper.getCroppedCanvas({ width: 308, height: 338 }).toDataURL('image/jpeg', 0.9);
                $('#guardian_edit_image_cropped').val(dataUrl);
                $('#image').val('image_cropped.jpg');
                $('#edit_guardian_img_tag').attr('src', dataUrl);
                guardianCropper.destroy(); guardianCropper = null;
                $('#guardianCropModal').modal('hide');
                $('#guardian_edit_image_input').val('');
            });

            $('#guardianCropClose, #guardianCropClose2').on('click', function() {
                if (guardianCropper) { guardianCropper.destroy(); guardianCropper = null; }
                $('#guardianCropModal').modal('hide');
                $('#guardian_edit_image_input').val('');
            });

            $('#guardianCropModal').on('hidden.bs.modal', function() {
                if ($('#editModal').hasClass('show')) {
                    var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                    $('body').addClass('modal-open').css('padding-right', scrollbarWidth > 0 ? scrollbarWidth + 'px' : '');
                }
            });
        });
    </script>
@endsection

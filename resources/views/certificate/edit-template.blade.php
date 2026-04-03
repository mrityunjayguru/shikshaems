@extends('layouts.master')

@section('title')
    {{ __('certificate') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_certificate') . ' ' . __('template') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('edit_certificate') . ' ' . __('template') }}
                        </h4>
                        {!! Form::model($certificateTemplate, [
                            'route' => ['certificate-template.update', $certificateTemplate->id],
                            'method' => 'post',
                            'class' => 'edit-form-without-reset',
                            'novalidate' => 'novalidate',
                            'enctype' => 'multipart/form-data',
                            'data-success-function' => 'formSuccessFunction'
                        ]) !!}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('type') }} <span class="text-danger">*</span></label>
                                    <div class="col-12 d-flex row">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" {{ $certificateTemplate->type == 'Student' ? 'checked' : '' }} class="form-check-input certificate_type" name="type" value="Student" required="required">
                                                {{ __('student') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" {{ $certificateTemplate->type == 'Staff' ? 'checked' : '' }} class="form-check-input certificate_type" name="type" value="Staff" required="required">
                                                {{ __('staff') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('page_layout', ['A4 Landscape' => 'A4 Landscape','A4 Portrait' => 'A4 Portrait','Custom' => 'Custom'], null, ['class' => 'form-control page_layout']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    {!! Form::number('height', null, ['class' => 'form-control height', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    {!! Form::number('width', null, ['class' => 'form-control width', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('user_image_shape') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('user_image_shape', ['Round' => 'Round','Square' => 'Square'], null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('image_size') }} <span class="text-small text-info">({{ __('px') }})</span><span class="text-danger">*</span></label>
                                    {!! Form::number('image_size', null, ['class' => 'form-control', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('background_image') }} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="background_image_cropped" id="cert_edit_bg_cropped">
                                    <input type="file" id="cert_edit_bg_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('background_image') }}" aria-label="" id="cert_edit_bg_info"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('cert_edit_bg_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <img src="{{ $certificateTemplate->background_image }}" id="cert_edit_bg_preview" class="img-fluid w-75 mt-2" alt="">
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    <textarea id="tinymce_message" name="description" id="description" required placeholder="{{__('description')}}">{{ $certificateTemplate->description }}</textarea>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    @include('certificate.tags')
                                </div>

                            </div>
                            {{-- <input class="btn btn-theme" id="create-btn" type="submit" value={{ __('submit') }}> --}}
                            <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Certificate Edit Crop Modal --}}
    <div class="modal" id="certEditCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Background Image</h6>
                    <button type="button" class="close" id="certEditCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="cert_edit_crop_img" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="certEditCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="certEditCropDone">Crop & Use</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css' }}"/>
@endsection

@section('script')
    <script src="{{ 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js' }}"></script>
    <script>
        var certEditCropper = null;

        function showCertEditCropModal() {
            var m = document.getElementById('certEditCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            var bd = document.createElement('div');
            bd.id = 'certEditCropBackdrop';
            bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
            document.body.appendChild(bd);
        }

        function hideCertEditCropModal() {
            var m = document.getElementById('certEditCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('certEditCropBackdrop');
            if (bd) bd.remove();
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cert_edit_bg_input').addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('cert_edit_crop_img');
                    if (certEditCropper) { certEditCropper.destroy(); certEditCropper = null; }
                    img.src = e.target.result;
                    showCertEditCropModal();
                    setTimeout(function() {
                        certEditCropper = new Cropper(img, {
                            aspectRatio: NaN, viewMode: 1, autoCropArea: 1,
                            minContainerHeight: 280, maxContainerHeight: 320
                        });
                    }, 150);
                };
                reader.readAsDataURL(this.files[0]);
            });

            document.getElementById('certEditCropDone').addEventListener('click', function() {
                if (!certEditCropper) return;
                var dataUrl = certEditCropper.getCroppedCanvas().toDataURL('image/jpeg', 0.9);
                document.getElementById('cert_edit_bg_cropped').value = dataUrl;
                document.getElementById('cert_edit_bg_info').value = 'background_cropped.jpg';
                document.getElementById('cert_edit_bg_preview').src = dataUrl;
                document.getElementById('cert_edit_bg_input').value = '';
                certEditCropper.destroy(); certEditCropper = null;
                hideCertEditCropModal();
            });

            document.getElementById('certEditCropClose').addEventListener('click', function() {
                if (certEditCropper) { certEditCropper.destroy(); certEditCropper = null; }
                document.getElementById('cert_edit_bg_input').value = '';
                hideCertEditCropModal();
            });
            document.getElementById('certEditCropClose2').addEventListener('click', function() {
                if (certEditCropper) { certEditCropper.destroy(); certEditCropper = null; }
                document.getElementById('cert_edit_bg_input').value = '';
                hideCertEditCropModal();
            });
        });

        window.onload = setTimeout(() => {
            $('.page_layout').trigger('change');
            $('.certificate_type').trigger('change');
        }, 500);

        function formSuccessFunction(response) {
            setTimeout(() => {
                window.location.href = "{{route('certificate-template.index')}}"
            }, 2000);
        }
    </script>

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
                            {{ __('create_certificate') . ' ' . __('template') }}
                        </h4>
                        <form class="pt-3 subject-create-form" id="create-form" action="{{ url('certificate-template') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('type') }} <span class="text-danger">*</span></label>
                                    <div class="col-12 d-flex row">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" checked class="form-check-input certificate_type" name="type" value="Student" required="required">
                                                {{ __('student') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input certificate_type" name="type" value="Staff" required="required">
                                                {{ __('staff') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('page_layout', ['A4 Landscape' => 'A4 Landscape','A4 Portrait' => 'A4 Portrait','Custom' => 'Custom'], 'A4 Landscape', ['class' => 'form-control page_layout']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="height" min="50" type="number" required placeholder="{{ __('height') }}" class="form-control height"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="width" min="50" type="number" required placeholder="{{ __('width') }}" class="form-control width"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('user_image_shape') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('user_image_shape', ['Round' => 'Round','Square' => 'Square'], 'Round', ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('image_size') }} <span class="text-small text-info">({{ __('px') }})</span><span class="text-danger">*</span></label>
                                    <input name="image_size" min="50" required type="number" placeholder="{{ __('image_size') }}" class="form-control"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('background_image') }} </label>
                                    <input type="hidden" name="background_image_cropped" id="cert_bg_cropped">
                                    <input type="file" id="cert_bg_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('background_image') }}" aria-label="" id="cert_bg_info"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('cert_bg_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <div id="cert_bg_preview" class="d-none mt-1" style="width:120px;">
                                        <img src="" class="img-fluid w-100" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    <textarea id="tinymce_message" name="description" id="description" required placeholder="{{__('description')}}"></textarea>
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
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('list') . ' ' . __('certificate') }} {{ __('template') }}</h4>
                        <div id="toolbar">
                            
                        </div>
                        
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table" data-url="{{ route('certificate-template.show',[1]) }}" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-fixed-columns="false" data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-query-params="certificateTemplateQueryParams" data-toolbar="#toolbar" data-export-options='{ "fileName": "subject-list-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}' data-show-export="true" data-escape="true">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="name">{{ __('name') }}</th>
                                <th scope="col" data-field="type">{{ __('type') }}</th>
                                <th scope="col" data-field="page_layout">{{ __('page_layout') }}</th>
                                <th scope="col" data-field="background_image" data-formatter="imageFormatter">{{ __('background_image') }}</th>
                                <th scope="col" data-field="style" data-formatter="layoutFormatter">{{ __('layout') }}</th>
                                <th scope="col" data-field="operate" data-events="certificateTemplateEvents" data-escape="false">{{ __('action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Certificate Crop Modal --}}
    <div class="modal" id="certCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image</h6>
                    <button type="button" class="close" id="certCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="cert_crop_preview_img" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="certCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="certCropDone">Crop & Use</button>
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
        var certCropper = null;

        function showCertCropModal() {
            var m = document.getElementById('certCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            var bd = document.createElement('div');
            bd.id = 'certCropBackdrop';
            bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
            document.body.appendChild(bd);
        }

        function hideCertCropModal() {
            var m = document.getElementById('certCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('certCropBackdrop');
            if (bd) bd.remove();
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cert_bg_input').addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('cert_crop_preview_img');
                    if (certCropper) { certCropper.destroy(); certCropper = null; }
                    img.src = e.target.result;
                    showCertCropModal();
                    setTimeout(function() {
                        certCropper = new Cropper(img, {
                            aspectRatio: NaN, viewMode: 1, autoCropArea: 1,
                            minContainerHeight: 280, maxContainerHeight: 320
                        });
                    }, 150);
                };
                reader.readAsDataURL(this.files[0]);
            });

            document.getElementById('certCropDone').addEventListener('click', function() {
                if (!certCropper) return;
                var dataUrl = certCropper.getCroppedCanvas().toDataURL('image/jpeg', 0.9);
                document.getElementById('cert_bg_cropped').value = dataUrl;
                document.getElementById('cert_bg_info').value = 'background_cropped.jpg';
                var prev = document.getElementById('cert_bg_preview');
                prev.classList.remove('d-none');
                prev.querySelector('img').src = dataUrl;
                document.getElementById('cert_bg_input').value = '';
                certCropper.destroy(); certCropper = null;
                hideCertCropModal();
            });

            document.getElementById('certCropClose').addEventListener('click', function() {
                if (certCropper) { certCropper.destroy(); certCropper = null; }
                document.getElementById('cert_bg_input').value = '';
                hideCertCropModal();
            });
            document.getElementById('certCropClose2').addEventListener('click', function() {
                if (certCropper) { certCropper.destroy(); certCropper = null; }
                document.getElementById('cert_bg_input').value = '';
                hideCertCropModal();
            });
        });

        window.onload = setTimeout(() => {
            $('.page_layout').trigger('change');
            $('.certificate_type').trigger('change');
        }, 500);
    </script>

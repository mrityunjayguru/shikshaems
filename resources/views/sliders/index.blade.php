@extends('layouts.master')

@section('title')
    {{ __('sliders') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('sliders') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card search-container">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('sliders') }}
                        </h4>
                        <form class="pt-3 common-validation-rules" id="create-form" action="{{ route('sliders.store') }}"
                              method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                    <label>{{ __('image') }} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="image_cropped" id="slider_create_image_cropped">
                                    <input type="file" id="slider_create_image_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                               placeholder="{{ __('image') }}" id="slider_create_image_info"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('slider_create_image_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <div id="slider_create_preview" class="d-none mt-1" style="width:120px;">
                                        <img src="" class="img-fluid w-100" alt="">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-8">
                                    <label for="">{{ __('link') }}</label>
                                    {!! Form::text('link', null, ['class' => 'form-control','placeholder' => __('link')]) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label for="">{{ __('type') }}</label>
                                    <div class="col-12 d-flex row">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" checked name="type" value="1" required="required">
                                                {{ __('app') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="type" value="2" required="required">
                                                {{ __('web') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="type" value="3" required="required">
                                                {{ __('both') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                


                            </div>
                          <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card search-container">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list') . ' ' . __('sliders') }}
                        </h4>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                               data-url="{{ route('sliders.show', [1]) }}" data-click-to-select="true"
                               data-side-pagination="server" data-pagination="true"
                               data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="false" data-toolbar="#toolbar"
                               data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                               data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                               data-maintain-selected="true" data-export-data-type='all'
                               data-export-options='{ "fileName": "slider-list-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}'
                               data-show-export="true" data-escape="true">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="image" data-sortable="true" data-formatter="imageFormatter">{{ __('image') }}</th>
                                <th scope="col" data-formatter="linkFormatter" data-field="link" data-sortable="true">{{ __('link') }}</th>
                                <th scope="col" data-formatter="typeFormatter" data-field="type">{{ __('type') }}</th>
                                <th scope="col" data-field="created_at"  data-sortable="true" data-visible="false">{{ __('created_at') }}</th>
                                <th scope="col" data-field="updated_at"  data-sortable="true" data-visible="false">{{ __('updated_at') }}</th>
                                <th scope="col" data-field="operate" data-events="sliderEvents" data-escape="false">{{ __('action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('edit') . ' ' . __('sliders') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="pt-3 sliders-edit-form" id="edit-form" action="{{ url('sliders') }}"
                              novalidate="novalidate">
                            <div class="modal-body">
                                <input type="hidden" name="edit_id" id="edit_id" value=""/>

                                <div class="form-group">
                                    <label>{{ __('image') }} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="image_cropped" id="slider_edit_image_cropped">
                                    <input type="file" id="slider_edit_image_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control" value=""
                                               placeholder="{{ __('image') }}" id="slider_edit_image_info"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('slider_edit_image_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <br>
                                    <div class="w-100 text-center">
                                        <img src="" id="edit_slider_image" class="w-100" alt="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('link') }}</label>
                                    {!! Form::text('link', null, ['class' => 'form-control edit_link', 'placeholder' => __('link')]) !!}
                                </div>
                                
                                <div class="form-group">
                                    <label for="">{{ __('type') }}</label>
                                    <div class="col-12 d-flex row">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit_type" name="type" value="1" required="required">
                                                {{ __('app') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit_type" name="type" value="2" required="required">
                                                {{ __('web') }}
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit_type" name="type" value="3" required="required">
                                                {{ __('both') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ __('close') }}</button>
                                <input class="btn btn-theme" type="submit" value={{ __('submit') }} />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Slider Crop Modal --}}
    <div class="modal" id="sliderCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:560px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image</h6>
                    <button type="button" class="close" id="sliderCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="slider_crop_preview" src="" style="max-width:100%;max-height:360px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="sliderCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="sliderCropDone">Crop & Use</button>
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
        var sliderCropper = null;
        var sliderCropTarget = null;

        function showSliderCropModal() {
            var m = document.getElementById('sliderCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            var bd = document.createElement('div');
            bd.id = 'sliderCropBackdrop';
            bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
            document.body.appendChild(bd);
        }

        function hideSliderCropModal() {
            var m = document.getElementById('sliderCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('sliderCropBackdrop');
            if (bd) bd.remove();
            if (sliderCropTarget === 'edit') {
                var editModal = document.getElementById('editModal');
                if (editModal && editModal.classList.contains('show')) {
                    var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                    document.body.classList.add('modal-open');
                    document.body.style.paddingRight = (scrollbarWidth > 0 ? scrollbarWidth : 0) + 'px';
                } else {
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                }
            } else {
                document.body.classList.remove('modal-open');
                document.body.style.paddingRight = '';
            }
        }

        function openSliderCrop(file, target) {
            sliderCropTarget = target;
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('slider_crop_preview');
                if (sliderCropper) { sliderCropper.destroy(); sliderCropper = null; }
                img.src = e.target.result;
                showSliderCropModal();
                setTimeout(function() {
                    sliderCropper = new Cropper(img, {
                        aspectRatio: NaN,
                        viewMode: 1,
                        autoCropArea: 1,
                        minContainerHeight: 280,
                        maxContainerHeight: 320
                    });
                }, 150);
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('slider_create_image_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openSliderCrop(this.files[0], 'create');
            });
            document.getElementById('slider_edit_image_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openSliderCrop(this.files[0], 'edit');
            });
            document.getElementById('sliderCropDone').addEventListener('click', function() {
                if (!sliderCropper) return;
                var canvas = sliderCropper.getCroppedCanvas();
                var dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                if (sliderCropTarget === 'create') {
                    document.getElementById('slider_create_image_cropped').value = dataUrl;
                    document.getElementById('slider_create_image_info').value = 'image_cropped.jpg';
                    var prev = document.getElementById('slider_create_preview');
                    prev.classList.remove('d-none');
                    prev.querySelector('img').src = dataUrl;
                    document.getElementById('slider_create_image_input').value = '';
                } else {
                    document.getElementById('slider_edit_image_cropped').value = dataUrl;
                    document.getElementById('slider_edit_image_info').value = 'image_cropped.jpg';
                    document.getElementById('edit_slider_image').src = dataUrl;
                    document.getElementById('slider_edit_image_input').value = '';
                }
                sliderCropper.destroy(); sliderCropper = null;
                hideSliderCropModal();
            });
            document.getElementById('sliderCropClose').addEventListener('click', function() {
                if (sliderCropper) { sliderCropper.destroy(); sliderCropper = null; }
                hideSliderCropModal();
                if (sliderCropTarget === 'create') document.getElementById('slider_create_image_input').value = '';
                else document.getElementById('slider_edit_image_input').value = '';
            });
            document.getElementById('sliderCropClose2').addEventListener('click', function() {
                if (sliderCropper) { sliderCropper.destroy(); sliderCropper = null; }
                hideSliderCropModal();
                if (sliderCropTarget === 'create') document.getElementById('slider_create_image_input').value = '';
                else document.getElementById('slider_edit_image_input').value = '';
            });
        });
    </script>

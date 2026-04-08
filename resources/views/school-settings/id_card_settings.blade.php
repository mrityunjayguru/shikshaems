@extends('layouts.master')

@section('title')
    {{ __('id_card_setting') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('id_card_setting') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3 create-form-without-reset" id="formdata" action="{{ url('id-card-settings') }}"
                            method="POST" novalidate="novalidate">
                            <div class="row">
                                {{--  --}}
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="col-12 d-flex row">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input type" name="type"
                                                        id="type" value="Student" checked required="required">
                                                    {{ __('student') }}
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input type" name="type"
                                                        id="type" value="Staff" required="required">
                                                    {{ __('staff') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div id="student-id-card">
                                @include('school-settings.student_id_card')
                            </div>

                            <div id="staff-id-card">
                                @include('school-settings.staff_id_card')
                            </div>

                            {{-- Signature --}}
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="image">{{ __('signature') }} </label>
                                <input type="hidden" name="signature_cropped" id="signature_cropped">
                                <input type="file" id="signature_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/svg"/>
                                <div class="input-group col-xs-12">
                                    <input type="text" id="signature_info" class="form-control file-upload-info"
                                        disabled="" placeholder="{{ __('image') }}" />
                                    <span class="input-group-append">
                                        <button class="btn btn-theme" type="button" onclick="document.getElementById('signature_input').click()">{{ __('upload') }}</button>
                                    </span>
                                </div>
                                @if ($settings['signature'] ?? '')
                                    <div id="signature">
                                        <img src="{{ $settings['signature'] }}" class="img-fluid w-25" alt="">
                                        <div class="mt-2">
                                            <a href="" data-type="signature"
                                                class="btn btn-inverse-danger btn-sm id-card-settings">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                        <div class="mt-3">
                                            <span class="text-info">
                                                {{ __('note_these_signature_image_are_also_used_in_certificates') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            {{-- End signature --}}

                            <div class="mt-3">
                            <input class="btn btn-secondary float-left px-10 py-6 ml-3" type="reset" value={{ __('reset') }} style="border-radius: 4px; min-width: 150px; background: #fff; color: var(--theme-color); border: 1px solid var(--theme-color); margin-bottom: 5px;">

                            <input class="btn btn-theme float-left ml-3 px-10 py-6" id="create-btn" type="submit"
                                value={{ __('submit') }} style="border-radius: 4px; min-width: 150px; background: var(--theme-color); color: white; border: 1px solid var(--theme-color); margin-bottom: 5px;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- ID Card Crop Modal --}}
    <div class="modal" id="idCardCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="idCardCropTitle">Crop Image</h6>
                    <button type="button" class="close" id="idCardCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="idcard_crop_preview" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="idCardCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="idCardCropDone">Crop & Use</button>
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
        var idCardCropper = null;
        var idCardCropField = null; // 'student_bg' | 'staff_bg' | 'signature'

        function showIdCardCropModal(title) {
            document.getElementById('idCardCropTitle').textContent = title || 'Crop Image';
            var m = document.getElementById('idCardCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            var bd = document.createElement('div');
            bd.id = 'idCardCropBackdrop';
            bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
            document.body.appendChild(bd);
        }

        function hideIdCardCropModal() {
            var m = document.getElementById('idCardCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('idCardCropBackdrop');
            if (bd) bd.remove();
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        }

        function openIdCardCrop(file, field, title) {
            idCardCropField = field;
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('idcard_crop_preview');
                if (idCardCropper) { idCardCropper.destroy(); idCardCropper = null; }
                img.src = e.target.result;
                showIdCardCropModal(title);
                setTimeout(function() {
                    idCardCropper = new Cropper(img, {
                        aspectRatio: NaN, viewMode: 1, autoCropArea: 1,
                        minContainerHeight: 280, maxContainerHeight: 320
                    });
                }, 150);
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Student background
            document.getElementById('student_bg_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openIdCardCrop(this.files[0], 'student_bg', 'Crop Background Image');
            });
            // Staff background
            document.getElementById('staff_bg_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openIdCardCrop(this.files[0], 'staff_bg', 'Crop Background Image');
            });
            // Signature
            document.getElementById('signature_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openIdCardCrop(this.files[0], 'signature', 'Crop Signature');
            });

            document.getElementById('idCardCropDone').addEventListener('click', function() {
                if (!idCardCropper) return;
                var dataUrl = idCardCropper.getCroppedCanvas().toDataURL('image/jpeg', 0.9);
                if (idCardCropField === 'student_bg') {
                    document.getElementById('student_bg_cropped').value = dataUrl;
                    document.getElementById('student_bg_info').value = 'background_cropped.jpg';
                    document.getElementById('student_bg_input').value = '';
                } else if (idCardCropField === 'staff_bg') {
                    document.getElementById('staff_bg_cropped').value = dataUrl;
                    document.getElementById('staff_bg_info').value = 'background_cropped.jpg';
                    document.getElementById('staff_bg_input').value = '';
                } else if (idCardCropField === 'signature') {
                    document.getElementById('signature_cropped').value = dataUrl;
                    document.getElementById('signature_info').value = 'signature_cropped.jpg';
                    document.getElementById('signature_input').value = '';
                }
                idCardCropper.destroy(); idCardCropper = null;
                hideIdCardCropModal();
            });

            document.getElementById('idCardCropClose').addEventListener('click', function() {
                if (idCardCropper) { idCardCropper.destroy(); idCardCropper = null; }
                if (idCardCropField === 'student_bg') document.getElementById('student_bg_input').value = '';
                else if (idCardCropField === 'staff_bg') document.getElementById('staff_bg_input').value = '';
                else document.getElementById('signature_input').value = '';
                hideIdCardCropModal();
            });
            document.getElementById('idCardCropClose2').addEventListener('click', function() {
                if (idCardCropper) { idCardCropper.destroy(); idCardCropper = null; }
                if (idCardCropField === 'student_bg') document.getElementById('student_bg_input').value = '';
                else if (idCardCropField === 'staff_bg') document.getElementById('staff_bg_input').value = '';
                else document.getElementById('signature_input').value = '';
                hideIdCardCropModal();
            });
        });

        window.onload = setTimeout(() => {
            $('.type').trigger('change');
        }, 500);
        $('.type').change(function(e) {
            e.preventDefault();
            let type = $('input[name="type"]:checked').val();
            if (type == 'Student') {
                $('#student-id-card').slideDown(500);
                $('#staff-id-card').slideUp(500);
            }
            if (type == 'Staff') {
                $('#student-id-card').slideUp(500);
                $('#staff-id-card').slideDown(500);
            }
        });
    </script>

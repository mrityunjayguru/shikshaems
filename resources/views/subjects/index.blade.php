@extends('layouts.master')

@section('title')
    {{ __('subject') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('subject') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('subject') }}
                        </h4>
                        <small class="text-danger">{{__("Note : Subject Name,Code & Type should be Unique for Medium")}}</small>
                        <form class="pt-3 subject-create-form" id="create-form" action="{{ route('subjects.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>{{ __('medium') }} <span class="text-danger">*</span></label>
                                <div class="col-12 d-flex row">
                                    @foreach ($mediums as $medium)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="medium_id" id="medium_{{ $medium->id }}" value="{{ $medium->id }}">
                                                {{ $medium->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                <input name="name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>{{ __('type') }} <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="type" id="theory" value="Theory">{{__("Theory")}}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="type" id="practical" value="Practical">{{__("Practical")}}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="type" id="practical" value="None">{{__("None")}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('subject_code') }}</label>
                                <input name="code" type="text" placeholder="{{ __('subject_code') }}" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>{{ __('bg_color') }} <span class="text-danger">*</span></label>
                                <input name="bg_color" type="text" placeholder="{{ __('bg_color') }}" class="color-picker" autocomplete="off"/>
                            </div>

                            <div class="form-group">
                                <label>{{ __('image') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="image_cropped" id="subject_create_image_cropped"/>
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control" id="subject_create_image_name" disabled="" placeholder="{{ __('image') }}"/>
                                    <span class="input-group-append">
                                        <button class="btn btn-theme" type="button" id="subject_create_image_btn">{{ __('upload') }}</button>
                                    </span>
                                </div>
                                <input type="file" id="subject_create_image_input" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/svg" style="display:none"/>
                                <div id="subject_create_image_preview" style="margin-top:8px;display:none;">
                                    <img id="subject_create_image_preview_img" src="" style="max-height:80px;border-radius:4px;"/>
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

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('list') . ' ' . __('subject') }}</h4>
                        <div id="toolbar">
                            <select name="filter_subject_id" id="filter_subject_id" class="form-control">
                                <option value="">All</option>
                                @foreach ($mediums as $medium)
                                    <option value="{{ $medium->id }}">{{ $medium->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-block">
                            <div class="">
                                <div class="col-12 text-right d-flex justify-content-end text-right align-items-end">
                                    <b><a href="#" class="table-list-type active mr-2" data-id="0">{{ __('all') }}</a></b> |
                                    <a href="#" class="ml-2 table-list-type" data-id="1">{{ __('Trashed') }}</a>
                                </div>
                            </div>
                        </div>
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                               data-url="{{ route('subjects.show',[1]) }}" data-click-to-select="true"
                               data-side-pagination="server" data-pagination="true"
                               data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                               data-show-refresh="true" data-fixed-columns="false" data-fixed-number="2"
                               data-fixed-right-number="1" data-trim-on-search="false" data-mobile-responsive="true"
                               data-sort-name="id" data-sort-order="desc" data-maintain-selected="true"
                               data-export-data-type='all' data-query-params="SubjectQueryParams"
                               data-toolbar="#toolbar" data-export-options='{ "fileName": "subject-list-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}' data-show-export="true" data-escape="true">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="name" data-sortable="true">{{ __('name') }}</th>
                                <th scope="col" data-field="code" data-sortable="true">{{ __('subject_code') }}</th>
                                <th scope="col" data-field="bg_color" data-formatter="bgColorFormatter">{{ __('bg_color') }}</th>
                                <th scope="col" data-field="medium.name">{{ __('medium') }}</th>
                                <th scope="col" data-field="image" data-formatter="imageFormatter">{{ __('image') }}</th>
                                <th scope="col" data-field="type" data-sortable="true">{{ __('type') }}</th>
                                <th scope="col" data-field="created_at"  data-sortable="true" data-visible="false">{{ __('created_at') }}</th>
                                <th scope="col" data-field="updated_at"  data-sortable="true" data-visible="false">{{ __('updated_at') }}</th>
                                <th scope="col" data-field="operate" data-events="subjectEvents" data-escape="false">{{ __('action') }}</th>
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
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('edit') . ' ' . __('subject') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="pt-3 subject-edit-form" id="edit-form" action="{{ url('subject') }}"
                              novalidate="novalidate">
                            <div class="modal-body">
                                <input type="hidden" name="edit_id" id="edit_id" value=""/>
                                <div class="form-group">
                                    <label>{{ __('medium') }} <span class="text-danger">*</span></label>
                                    <div class="d-flex responsive-medium-list">
                                        @foreach ($mediums as $medium)
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input edit" name="medium_id" id="edit_medium_{{ $medium->id }}" value="{{ $medium->id }}"> {{ $medium->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" id="edit_name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('type') }} <span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit" name="type" id="edit_theory" value="Theory">
                                                {{ __('Theory') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit" name="type" id="edit_practical" value="Practical">
                                                {{ __('Practical') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input edit" name="type" id="edit_practical" value="None">
                                                {{ __('None') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('subject_code') }}</label>
                                    <input name="code" id="edit_code" type="text" placeholder="{{ __('subject_code') }}" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('bg_color') }} <span class="text-danger">*</span></label>
                                    <input name="bg_color" id="edit_bg_color" type="text" placeholder="{{ __('bg_color') }}" class="color-picker" autocomplete="off"/>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('image') }}</label>
                                    <input type="hidden" name="image_cropped" id="subject_edit_image_cropped"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control" id="subject_edit_image_name" disabled="" value=""/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" id="subject_edit_image_btn">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <input type="file" id="subject_edit_image_input" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/svg" style="display:none"/>
                                    <div id="subject_edit_image_preview" style="margin-top:8px;display:none;">
                                        <img id="subject_edit_image_preview_img" src="" style="max-height:80px;border-radius:4px;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                                <input class="btn btn-theme" type="submit" value={{ __('submit') }} />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"/>
<style>
.subject-crop-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99999;display:flex;align-items:center;justify-content:center;}
.subject-crop-modal-box{background:#fff;border-radius:8px;padding:16px;width:100%;max-width:480px;box-shadow:0 8px 32px rgba(0,0,0,.25);}
.subject-crop-modal-box .crop-img-wrap{max-height:320px;overflow:hidden;background:#000;}
.subject-crop-modal-box .crop-img-wrap img{display:block;max-width:100%;}
.subject-crop-modal-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:12px;}
</style>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    function initSubjectCrop(opts) {
        var btn         = document.getElementById(opts.btnId);
        var fileInput   = document.getElementById(opts.inputId);
        var croppedEl   = document.getElementById(opts.croppedId);
        var nameEl      = document.getElementById(opts.nameId);
        var previewWrap = document.getElementById(opts.previewId);
        var previewImg  = document.getElementById(opts.previewImgId);
        if (!btn || !fileInput) return;
        // remove old listeners by cloning
        var newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        var newInput = fileInput.cloneNode(true);
        fileInput.parentNode.replaceChild(newInput, fileInput);

        newBtn.addEventListener('click', function () { newInput.value = ''; newInput.click(); });
        newInput.addEventListener('change', function () {
            if (!newInput.files || !newInput.files[0]) return;
            var file = newInput.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                openSubjectCropModal(e.target.result, file.name, opts.aspectRatio, function(base64, fname) {
                    croppedEl.value = base64;
                    if (nameEl) nameEl.value = fname;
                    if (previewWrap) previewWrap.style.display = 'block';
                    if (previewImg) previewImg.src = base64;
                });
            };
            reader.readAsDataURL(file);
        });
    }

    var _cropperInstance = null;
    var _cropCallback    = null;

    function openSubjectCropModal(src, fname, aspectRatio, cb) {
        _cropCallback = cb;
        var overlay = document.createElement('div');
        overlay.className = 'subject-crop-modal-overlay';
        overlay.id = 'subjectCropOverlay';
        overlay.innerHTML =
            '<div class="subject-crop-modal-box">' +
                '<div class="crop-img-wrap"><img id="subjectCropImg" src="' + src + '"/></div>' +
                '<div class="subject-crop-modal-actions">' +
                    '<button type="button" class="btn btn-secondary btn-sm" id="subjectCropSkip">Skip</button>' +
                    '<button type="button" class="btn btn-theme btn-sm" id="subjectCropDone">Crop & Use</button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(overlay);

        var img = document.getElementById('subjectCropImg');
        _cropperInstance = new Cropper(img, {
            aspectRatio: aspectRatio,
            viewMode: 1,
            autoCropArea: 1,
            minContainerHeight: 280,
            maxContainerHeight: 320,
        });

        document.getElementById('subjectCropDone').addEventListener('click', function () {
            var canvas = _cropperInstance.getCroppedCanvas();
            var base64 = canvas.toDataURL('image/jpeg', 0.85);
            closeSubjectCropModal();
            if (_cropCallback) _cropCallback(base64, fname);
        });

        document.getElementById('subjectCropSkip').addEventListener('click', function () {
            closeSubjectCropModal();
            if (_cropCallback) _cropCallback(src, fname);
        });
    }

    function closeSubjectCropModal() {
        if (_cropperInstance) { _cropperInstance.destroy(); _cropperInstance = null; }
        var overlay = document.getElementById('subjectCropOverlay');
        if (overlay) overlay.remove();
    }

    // Init Create
    initSubjectCrop({
        btnId: 'subject_create_image_btn',
        inputId: 'subject_create_image_input',
        croppedId: 'subject_create_image_cropped',
        nameId: 'subject_create_image_name',
        previewId: 'subject_create_image_preview',
        previewImgId: 'subject_create_image_preview_img',
        aspectRatio: NaN,
    });

    // Init Edit on modal open
    var editModalEl = document.getElementById('editModal');
    if (editModalEl) {
        editModalEl.addEventListener('shown.bs.modal', function () {
            initSubjectCrop({
                btnId: 'subject_edit_image_btn',
                inputId: 'subject_edit_image_input',
                croppedId: 'subject_edit_image_cropped',
                nameId: 'subject_edit_image_name',
                previewId: 'subject_edit_image_preview',
                previewImgId: 'subject_edit_image_preview_img',
                aspectRatio: NaN,
            });
        });

        // Restore scroll when edit modal closes (crop was inside it)
        editModalEl.addEventListener('hidden.bs.modal', function () {
            if (!document.getElementById('subjectCropOverlay')) {
                var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.classList.add('modal-open');
                document.body.style.paddingRight = scrollbarWidth + 'px';
            }
        });
    }

    // Reset create form
    var createForm = document.getElementById('create-form');
    if (createForm) {
        createForm.addEventListener('reset', function () {
            var prev = document.getElementById('subject_create_image_preview');
            if (prev) prev.style.display = 'none';
            var cr = document.getElementById('subject_create_image_cropped');
            if (cr) cr.value = '';
            var nm = document.getElementById('subject_create_image_name');
            if (nm) nm.value = '';
        });
    }
});
</script>
@endsection

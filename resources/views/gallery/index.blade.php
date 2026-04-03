@extends('layouts.master')

@section('title')
    {{ __('gallery') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('gallery') }}
            </h3>
        </div>

        <div class="row">
            @if (Auth::user()->can('gallery-create'))
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                {{ __('create') . ' ' . __('gallery') }}
                            </h4>
                            <form class="create-form pt-3" id="create-form" action="{{ route('gallery.store') }}" data-success-function="formSuccessFunction"
                                  method="POST" novalidate="novalidate" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                        {!! Form::text('title', null, ['required', 'placeholder' => __('title'), 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group col-sm-6 col-md-6">
                                        <label>{{ __('description') }}</label>
                                        {!! Form::textarea('description', null, [
                                            'rows' => '2',
                                            'placeholder' => __('description'),
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="form-group col-sm-6 col-md-6">
                                        <label>{{ __('thumbnail') }} <span class="text-danger">*</span> <span class="text-small text-info">( jpg,svg,jpeg,png )</span></label>
                                        <input type="hidden" name="thumbnail_cropped" id="gallery_create_thumb_cropped">
                                        <input type="file" id="gallery_create_thumb_input" required class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled=""
                                                   placeholder="{{ __('thumbnail') }}" required aria-label="" id="gallery_create_thumb_info"/>
                                            <span class="input-group-append">
                                                <button class="btn btn-theme" type="button" onclick="document.getElementById('gallery_create_thumb_input').click()">{{ __('upload') }}</button>
                                            </span>
                                        </div>
                                        <div id="gallery_create_thumb_preview" class="d-none mt-1" style="width:120px;">
                                            <img src="" class="img-fluid w-100" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 col-md-6">
                                        <label>{{ __('images') }} <span class="text-small text-info"> ({{ __('upload_multiple_images') }})</span></label>
                                        <input type="file" multiple id="uploadInput" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="{{ __('images') }}" aria-label="" id="uploadInputInfo"/>
                                            <span class="input-group-append">
                                                <button class="btn btn-theme" type="button" onclick="document.getElementById('uploadInput').click()">{{ __('upload') }}</button>
                                            </span>
                                        </div>
                                        <div id="selectedFiles" class="mt-3" style="max-height: 200px; overflow-y: auto;">
                                            <!-- Selected files will be listed here -->
                                        </div>
                                        {{-- cropped images hidden inputs injected here by JS --}}
                                        <div id="croppedImagesContainer"></div>
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">{{ __('youtube_links') }} <span class="text-small text-info">({{ __('please_use_commas_or_press_enter_to_add_multiple_links') }})</span></label>
                                        <input name="youtube_links" id="tags" class="form-control" value=""/>
                                    </div>

                                    <div class="form-group col-sm-12 col-md-3">
                                        <label for="session_year_id">{{ __('session_year') }}</label>
                                        <select name="session_year_id" class="form-control">
                                            @foreach ($sessionYears as $sessionYear)
                                                <option value="{{ $sessionYear->id }}"
                                                    {{ $sessionYear->default == 1 ? 'selected' : '' }}>
                                                    {{ $sessionYear->name }}
                                                </option>
                                            @endforeach
                                        </select>
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
            @endif
            @if (Auth::user()->can('gallery-list'))
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                {{ __('list') . ' ' . __('gallery') }}
                            </h4>
                            <div class="row" id="toolbar">
                                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3">
                                    <label for="filter_session_year_id"
                                           class="filter-menu">{{ __('session_year') }}</label>
                                    <select name="filter_session_year_id" id="filter_session_year_id" class="form-control">
                                        @foreach ($sessionYears as $sessionYear)
                                            <option value="{{ $sessionYear->id }}"
                                                {{ $sessionYear->default == 1 ? 'selected' : '' }}>
                                                {{ $sessionYear->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                           data-url="{{ route('gallery.show', 1) }}" data-click-to-select="true"
                                           data-side-pagination="server" data-pagination="true"
                                           data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                                           data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                                           data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                                           data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-show-export="true"
                                           data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all'
                                           data-export-options='{ "fileName": "gallery-list-<?= date('d-m-y') ?>
                                               ","ignoreColumn": ["operate"]}'
                                           data-query-params="galleryQueryParams">
                                        <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false"> {{ __('id') }} </th>
                                            <th scope="col" data-width="80" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-width="200" data-formatter="imageFormatter" data-field="thumbnail">{{ __('thumbnail') }} </th>
                                            <th scope="col" data-field="title">{{ __('title') }} </th>
                                            <th scope="col" data-field="description">{{ __('description') }}</th>
                                            @if (Auth::user()->can('gallery-edit') || Auth::user()->can('gallery-delete'))
                                                <th data-width="200" data-events="galleryEvents" data-width="150" scope="col" data-field="operate">{{ __('action') }}</th>
                                            @endif
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Edit Gallery --}}
        <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ __('edit') . ' ' . __('gallery') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <form id="formdata" class="edit-form" action="{{ url('gallery') }}" novalidate="novalidate">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <div class="row form-group">
                                <div class="col-sm-12 col-md-12">
                                    <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('title', null, [
                                        'required',
                                        'placeholder' => __('title'),
                                        'class' => 'form-control',
                                        'id' => 'edit-title',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12 col-md-12">
                                    <label>{{ __('description') }}</label>
                                    {!! Form::textarea('description', null, [
                                        'placeholder' => __('description'),
                                        'class' => 'form-control',
                                        'id' => 'edit-description',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-12 col-md-12">
                                    <label for="session_year_id">{{ __('session_year') }}</label>
                                    <select name="session_year_id" id="edit_session_year_id" class="form-control">
                                        @foreach ($sessionYears as $sessionYear)
                                            <option value="{{ $sessionYear->id }}">
                                                {{ $sessionYear->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col-sm-12 col-md-12">
                                    <label>{{ __('thumbnail') }} </label>
                                    <input type="hidden" name="thumbnail_cropped" id="gallery_edit_thumb_cropped">
                                    <input type="file" id="gallery_edit_thumb_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                               placeholder="{{ __('thumbnail') }}" aria-label="" id="gallery_edit_thumb_info"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('gallery_edit_thumb_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <img src="" id="edit-thumbnail" class="img-lg mt-2" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <input class="btn btn-theme" type="submit" value={{ __('submit') }}>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Gallery Crop Modal --}}
    <div class="modal" id="galleryCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image <span id="galleryCropCounter" class="text-muted" style="font-size:13px;"></span></h6>
                    <button type="button" class="close" id="galleryCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="gallery_crop_preview" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="galleryCropSkip">Skip</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="galleryCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="galleryCropDone">Crop & Use</button>
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
        var galleryCropper = null;
        var galleryCropTarget = null;

        // ── Multi-image crop queue ──────────────────────────────────────
        var imageQueue = [];        // raw File objects pending crop
        var croppedResults = [];    // { file: File|null, dataUrl: string|null, name: string }
        var currentQueueIndex = 0;

        function showGalleryCropModal() {
            var m = document.getElementById('galleryCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            if (!document.getElementById('galleryCropBackdrop')) {
                var bd = document.createElement('div');
                bd.id = 'galleryCropBackdrop';
                bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
                document.body.appendChild(bd);
            }
        }

        function hideGalleryCropModal() {
            var m = document.getElementById('galleryCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('galleryCropBackdrop');
            if (bd) bd.remove();
            if (galleryCropTarget === 'edit') {
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

        function openGalleryCrop(file, target) {
            galleryCropTarget = target;
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('gallery_crop_preview');
                if (galleryCropper) { galleryCropper.destroy(); galleryCropper = null; }
                img.src = e.target.result;
                showGalleryCropModal();
                setTimeout(function() {
                    galleryCropper = new Cropper(img, {
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

        // Open next image in queue
        function openNextInQueue() {
            if (currentQueueIndex >= imageQueue.length) {
                hideGalleryCropModal();
                renderCroppedPreview();
                return;
            }
            var file = imageQueue[currentQueueIndex];
            document.getElementById('galleryCropCounter').textContent =
                '(' + (currentQueueIndex + 1) + ' / ' + imageQueue.length + ')';
            galleryCropTarget = 'multi';
            var reader = new FileReader();
            reader.onload = function(e) {
                // store preview URL on the file object for later use
                file._previewUrl = e.target.result;
                var img = document.getElementById('gallery_crop_preview');
                if (galleryCropper) { galleryCropper.destroy(); galleryCropper = null; }
                img.src = e.target.result;
                showGalleryCropModal();
                setTimeout(function() {
                    galleryCropper = new Cropper(img, {
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

        // Render preview list + inject hidden inputs
        function renderCroppedPreview() {
            var container = document.getElementById('selectedFiles');
            var hiddenContainer = document.getElementById('croppedImagesContainer');
            container.innerHTML = '';
            hiddenContainer.innerHTML = '';

            var count = croppedResults.length;
            document.getElementById('uploadInputInfo').value =
                count + (count === 1 ? ' file selected' : ' files selected');

            // Build a DataTransfer for original (skipped) files → images[]
            var dt = new DataTransfer();

            croppedResults.forEach(function(item, index) {
                var div = document.createElement('div');
                div.className = 'selected-file d-flex align-items-center p-2 border-bottom';
                div.innerHTML = '<img src="' + item.previewUrl + '" alt="' + item.name + '" style="width:50px;height:50px;object-fit:cover;margin-right:10px;">' +
                    '<div class="flex-grow-1"><div class="font-weight-bold">' + item.name + '</div>' +
                    (item.cropped ? '<div class="text-success small">Cropped</div>' : '<div class="text-muted small">Original</div>') +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-danger" style="padding:2px 8px;line-height:1;" onclick="removeGalleryImage(' + index + ')">×</button>';
                container.appendChild(div);

                if (item.cropped) {
                    // cropped → hidden base64 input
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'images_cropped[]';
                    inp.value = item.dataUrl;
                    hiddenContainer.appendChild(inp);
                } else {
                    // skipped → add original file to DataTransfer
                    dt.items.add(item.file);
                }
            });

            // Inject original files via a hidden file input
            if (dt.files.length > 0) {
                var fileInp = document.createElement('input');
                fileInp.type = 'file';
                fileInp.name = 'images[]';
                fileInp.multiple = true;
                fileInp.style.display = 'none';
                fileInp.files = dt.files;
                hiddenContainer.appendChild(fileInp);
            }
        }

        function removeGalleryImage(index) {
            croppedResults.splice(index, 1);
            renderCroppedPreview();
        }

        document.addEventListener('DOMContentLoaded', function() {

            // ── Thumbnail crop (create) ──
            document.getElementById('gallery_create_thumb_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openGalleryCrop(this.files[0], 'create');
            });

            // ── Thumbnail crop (edit) ──
            document.getElementById('gallery_edit_thumb_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openGalleryCrop(this.files[0], 'edit');
            });

            // ── Multi-image selection ──
            document.getElementById('uploadInput').addEventListener('change', function() {
                if (!this.files || !this.files.length) return;
                imageQueue = Array.from(this.files);
                croppedResults = [];
                currentQueueIndex = 0;
                openNextInQueue();
                this.value = ''; // reset so same files can be re-selected
            });

            // ── Crop Done ──
            document.getElementById('galleryCropDone').addEventListener('click', function() {
                if (!galleryCropper) return;
                var canvas = galleryCropper.getCroppedCanvas();
                var dataUrl = canvas.toDataURL('image/jpeg', 0.9);

                if (galleryCropTarget === 'create') {
                    document.getElementById('gallery_create_thumb_cropped').value = dataUrl;
                    document.getElementById('gallery_create_thumb_info').value = 'thumbnail_cropped.jpg';
                    var prev = document.getElementById('gallery_create_thumb_preview');
                    prev.classList.remove('d-none');
                    prev.querySelector('img').src = dataUrl;
                    document.getElementById('gallery_create_thumb_input').value = '';
                    galleryCropper.destroy(); galleryCropper = null;
                    hideGalleryCropModal();
                } else if (galleryCropTarget === 'edit') {
                    document.getElementById('gallery_edit_thumb_cropped').value = dataUrl;
                    document.getElementById('gallery_edit_thumb_info').value = 'thumbnail_cropped.jpg';
                    document.getElementById('edit-thumbnail').src = dataUrl;
                    document.getElementById('gallery_edit_thumb_input').value = '';
                    galleryCropper.destroy(); galleryCropper = null;
                    hideGalleryCropModal();
                } else {
                    // multi-image queue
                    croppedResults.push({
                        dataUrl: dataUrl,
                        previewUrl: dataUrl,
                        name: imageQueue[currentQueueIndex].name,
                        cropped: true
                    });
                    galleryCropper.destroy(); galleryCropper = null;
                    currentQueueIndex++;
                    openNextInQueue();
                }
            });

            // ── Skip (keep original, no crop) ──
            document.getElementById('galleryCropSkip').addEventListener('click', function() {
                if (galleryCropTarget !== 'multi') return;
                if (galleryCropper) { galleryCropper.destroy(); galleryCropper = null; }
                var file = imageQueue[currentQueueIndex];
                croppedResults.push({
                    dataUrl: null,
                    previewUrl: file._previewUrl || '',
                    name: file.name,
                    file: file,
                    cropped: false
                });
                currentQueueIndex++;
                openNextInQueue();
            });

            // ── Cancel all ──
            document.getElementById('galleryCropClose').addEventListener('click', function() {
                if (galleryCropper) { galleryCropper.destroy(); galleryCropper = null; }
                if (galleryCropTarget === 'multi') {
                    imageQueue = []; croppedResults = []; currentQueueIndex = 0;
                    document.getElementById('selectedFiles').innerHTML = '';
                    document.getElementById('croppedImagesContainer').innerHTML = '';
                    document.getElementById('uploadInputInfo').value = '';
                } else if (galleryCropTarget === 'create') {
                    document.getElementById('gallery_create_thumb_input').value = '';
                } else {
                    document.getElementById('gallery_edit_thumb_input').value = '';
                }
                hideGalleryCropModal();
            });

            document.getElementById('galleryCropClose2').addEventListener('click', function() {
                if (galleryCropper) { galleryCropper.destroy(); galleryCropper = null; }
                if (galleryCropTarget === 'multi') {
                    imageQueue = []; croppedResults = []; currentQueueIndex = 0;
                    document.getElementById('selectedFiles').innerHTML = '';
                    document.getElementById('croppedImagesContainer').innerHTML = '';
                    document.getElementById('uploadInputInfo').value = '';
                } else if (galleryCropTarget === 'create') {
                    document.getElementById('gallery_create_thumb_input').value = '';
                } else {
                    document.getElementById('gallery_edit_thumb_input').value = '';
                }
                hideGalleryCropModal();
            });
        });

        function formSuccessFunction(response) {
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    </script>
@endsection

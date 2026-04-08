@extends('layouts.master')

@section('title')
    {{ __('notification') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_notification') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create_notification') }}
                        </h4>
                        <form id="create-form" class="pt-3" action="{{ url('notifications') }}" method="POST"
                              novalidate="novalidate" data-success-function="formSuccessFunction">
                            @csrf
                            <div class="row">

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('roles') }} <span class="text-danger">*</span></label><br>
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                {{ Form::radio('type', 'Roles', true, ['id' => 'roles_type', 'class' => 'form-check-input type']) }}
                                                {{ __('Roles') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                {{ Form::radio('type', 'OverDueFees', false, ['id' => 'over_due_fees_type', 'class' => 'form-check-input type']) }}
                                                {{ __('Over Due Fees') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-6 roles">
                                    <label for="">{{ __('roles') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('roles[]', $roles, null, ['class' => 'form-control select2-dropdown select2-hidden-accessible','multiple', 'id' => 'roles']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-6 over_due_fees_roles" style="display: none;">
                                    <label for="">{{ __('roles') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('roles[]', $over_due_fees_roles, null, ['class' => 'form-control select2-dropdown select2-hidden-accessible','multiple', 'id' => 'over_due_fees_roles']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-6">
                                    <label for="">{{ __('title') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('title', null, ['required','class' => 'form-control','placeholder' => __('title')]) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label for="">{{ __('message') }} <span class="text-danger">*</span></label>
                                    {!! Form::textarea('message', null, ['required','class' => 'form-control','placeholder' => __('message'), 'rows' => 3]) !!}
                                </div>

                                <textarea id="user_id" name="user_id" style="display: none"></textarea>

                                {{-- <textarea name="all_users" id="" cols="30" rows="10" hidden>{{ $all_users }}</textarea> --}}

                                <div class="form-group col-sm-6 col-md-6">
                                    <label>{{ __('image') }} </label>
                                    <input type="hidden" name="image_cropped" id="notif_image_cropped">
                                    <input type="file" id="notif_image_input" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" id="notif_image_info" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}"/>
                                        <span class="input-group-append">
                                            <button class="btn btn-theme" type="button" onclick="document.getElementById('notif_image_input').click()">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <div id="notif_image_preview" class="d-none mt-1" style="width:120px;">
                                        <img src="" class="img-fluid w-100" alt="">
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

            <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        
                        {{-- <div class="row" id="toolbar-user">
                            <div class="form-group col-sm-12 col-md-12">
                                <label class="filter-menu">{{ __('Class Section') }} <span class="text-danger">*</span></label>
                                
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                
                            </div>
                        </div> --}}
                        <table aria-describedby="mydesc" class='table' id='table_user_list' data-toggle="table"
                               data-url="{{ route('notifications.user.show') }}" data-click-to-select="true"
                               data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                               data-search="true" data-toolbar="#toolbar" data-show-columns="false" data-show-refresh="true"
                               data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                               data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                               data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-show-export="false" data-check-on-init="true" data-response-handler="responseHandler"
                               data-export-options='{ "fileName": "notification-list-<?= date('d-m-y') ?>","ignoreColumn":["operate"]}'
                               data-escape="true" data-query-params="NotificationUserqueryParams">
                            <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="full_name">{{ __('name') }}</th>
                                
                                {{-- <th scope="col" data-field="operate" data-escape="false">{{ __('action') }} --}}
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            
         
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('list_notification') }}
                        </h4>

                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                               data-url="{{ route('notifications.show', [1]) }}" data-click-to-select="true"
                               data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                               data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                               data-fixed-columns="false" data-fixed-number="2" data-fixed-right-number="1"
                               data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                               data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-show-export="true"
                               data-export-options='{ "fileName": "notification-list-<?= date('d-m-y') ?>","ignoreColumn":["operate"]}'
                               data-escape="true" data-query-params="queryParams">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="image" data-formatter="imageFormatter">{{ __('image') }}</th>
                                <th scope="col" data-field="title">{{ __('title') }}</th>
                                <th scope="col" data-field="message" data-events="tableDescriptionEvents" data-formatter="descriptionFormatter">{{ __('message') }}</th>
                                <th scope="col" data-visible="false" data-field="send_to">{{ __('type') }}</th>
                                <th scope="col" data-field="operate" data-escape="false">{{ __('action') }}
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Notification Crop Modal --}}
    <div class="modal" id="notifCropModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;display:none;">
        <div class="modal-dialog" role="document" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Image</h6>
                    <button type="button" class="close" id="notifCropClose"><span>&times;</span></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <img id="notif_crop_preview" src="" style="max-width:100%;max-height:320px;display:block;" alt="crop">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="notifCropClose2">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-theme btn-sm" id="notifCropDone">Crop & Use</button>
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
        var notifCropper = null;

        function showNotifCropModal() {
            var m = document.getElementById('notifCropModal');
            m.style.display = 'block';
            document.body.classList.add('modal-open');
            var bd = document.createElement('div');
            bd.id = 'notifCropBackdrop';
            bd.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1059;';
            document.body.appendChild(bd);
        }

        function hideNotifCropModal() {
            var m = document.getElementById('notifCropModal');
            m.style.display = 'none';
            var bd = document.getElementById('notifCropBackdrop');
            if (bd) bd.remove();
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        }

        function openNotifCrop(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('notif_crop_preview');
                if (notifCropper) { notifCropper.destroy(); notifCropper = null; }
                img.src = e.target.result;
                showNotifCropModal();
                setTimeout(function() {
                    notifCropper = new Cropper(img, {
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
            document.getElementById('notif_image_input').addEventListener('change', function() {
                if (this.files && this.files[0]) openNotifCrop(this.files[0]);
            });
            document.getElementById('notifCropDone').addEventListener('click', function() {
                if (!notifCropper) return;
                var canvas = notifCropper.getCroppedCanvas();
                var dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                document.getElementById('notif_image_cropped').value = dataUrl;
                document.getElementById('notif_image_info').value = 'image_cropped.jpg';
                var prev = document.getElementById('notif_image_preview');
                prev.classList.remove('d-none');
                prev.querySelector('img').src = dataUrl;
                document.getElementById('notif_image_input').value = '';
                notifCropper.destroy(); notifCropper = null;
                hideNotifCropModal();
            });
            document.getElementById('notifCropClose').addEventListener('click', function() {
                if (notifCropper) { notifCropper.destroy(); notifCropper = null; }
                document.getElementById('notif_image_input').value = '';
                hideNotifCropModal();
            });
            document.getElementById('notifCropClose2').addEventListener('click', function() {
                if (notifCropper) { notifCropper.destroy(); notifCropper = null; }
                document.getElementById('notif_image_input').value = '';
                hideNotifCropModal();
            });
        });

        $(document).ready(function () {
            $('.role-list').hide(500);
            $('.user-list').hide(500);
            $('.type').trigger('change');
        });
        function formSuccessFunction(response) {
            setTimeout(() => {
                // Reset selections
                selections = [];
                user_list = [];
                $('.roles').show();
                $('.over_due_fees_roles').hide();
                $('.type').trigger('change');
                $('#table_user_list').bootstrapTable('refresh');

                // reset form fields
                $('.form-control').val('');
                $('input[type="file"]').val(''); // Clear file input
            }, 500);
        }
        
        $('#reset').click(function (e) { 
            // e.preventDefault();
            $('.default-all').prop('checked', true);
            $('.type').trigger('change');
        });
        

        $('.type').change(function (e) {
            var selectedType = $('input[name="type"]:checked').val();
            e.preventDefault();
            $('.user_id').val('').trigger('change');

            $('.roles').hide();
            $('.over_due_fees_roles').hide();
            $('.user-list').hide();
            $('.role-list').hide();
            
            $('#table_user_list').bootstrapTable('uncheckAll');
            
            if (selectedType == 'Roles') {
                $('.roles').show();
                $('.role-list').show();

                $("#roles").prop("disabled", false); 
                $("#over_due_fees_roles").prop("disabled", true);

                // reset roles
                $("#roles").val('').trigger('change');

            } else if (selectedType == 'OverDueFees') {
                $('.over_due_fees_roles').show();
                $('.user-list').show();

                $("#roles").prop("disabled", true); 
                $("#over_due_fees_roles").prop("disabled", false);
                
                // reset roles
                $("#over_due_fees_roles").val('').trigger('change');
            }
            
        });

        $('#roles').change(function (e) { 
            e.preventDefault();
            $('#table_user_list').bootstrapTable('refresh');
        });

        $('#over_due_fees_roles').change(function (e) { 
            e.preventDefault();
            $('#table_user_list').bootstrapTable('refresh');
        });

        $('.type').change(function (e) {
            e.preventDefault();
            $('#table_user_list').bootstrapTable('refresh');
            
        });

        var $tableList = $('#table_user_list')
        var selections = []
        var user_list = [];

        function responseHandler(res) {
            $.each(res.rows, function (i, row) {
                row.state = $.inArray(row.id, selections) !== -1
            })
            return res
        }

        $(function () {
            $tableList.on('check.bs.table check-all.bs.table uncheck.bs.table uncheck-all.bs.table',
                function (e, rowsAfter, rowsBefore) {
                    user_list = [];
                    var rows = rowsAfter
                    if (e.type === 'uncheck-all') {
                        rows = rowsBefore
                    }
                    var ids = $.map(!$.isArray(rows) ? [rows] : rows, function (row) {
                        return row.id
                    })

                    var func = $.inArray(e.type, ['check', 'check-all']) > -1 ? 'union' : 'difference'
                    selections = window._[func](selections, ids)
                    selections.forEach(element => {
                        user_list.push(element);
                    });

                    $('textarea#user_id').val(user_list);
                })
        })

    </script>
@endsection

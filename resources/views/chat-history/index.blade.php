@extends('layouts.master')

@section('title')
    {{ __('Chat History') }}
@endsection
@section('css')
    <style>
        .chat-wrapper {
            height: 80vh;
            border: 1px solid #ddd;
        }

        .chat-sidebar {
            border-right: 1px solid #ddd;
            padding: 0;
        }

        .chat-header {
            background: linear-gradient(to right, #1FC285, #00BE78) !important;
            color: white;
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            flex-shrink: 0;
        }

        .chat-users {
            overflow-y: auto;
            height: calc(80vh - 50px);
        }

        .chat-user {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .chat-user:hover {
            background: #f5f5f5;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #25D366;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-right: 10px;
        }

        .chat-main {
            display: flex;
            flex-direction: column;
            padding: 0;
            height: 80vh;
            border-left: 1px solid #eee;
        }

        .chat-messages {
            flex: 1;
            background: #ECE5DD;
            overflow-y: auto;
            /* 👈 SCROLL ENABLED */
            padding: 15px;
            /* background: #f8f9fa; */
            scroll-behavior: smooth;
        }

        .message {
            max-width: 50%;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .message.sent {
            background: #DCF8C6;
            margin-left: auto;
        }

        .message.received {
            background: white;
            margin-right: auto;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #f7f7f7;
        }

        .chat-input input {
            flex: 1;
            margin-right: 10px;
        }

        .chat-user.active {
            background: #f1f5ff;
            border-left: 3px solid #4e73df;
        }

        .chat-header button {
            min-width: 32px;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Chat History
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            Chat History

                            <div class="row mb-3 mt-3">

                                <div class="col-md-4">
                                    <label class="filter-menu">{{ __('Select Role') }}</label>
                                    <select class="form-control" id="role">
                                        <option value="">{{ __('All') }}</option>
                                        <option value="Teacher">Teacher</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Guardian">Guardian</option>
                                        <option value="Student">Student</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="classSectionWrapper" style="display:none;">
                                    <label class="filter-menu">{{ __('Select Class Section') }}</label>
                                    <select class="form-control" id="class_section_id">
                                        <option value="">{{ __('All') }}</option>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-menu">{{ __('Select User') }}</label>
                                    <select class="form-control" id="user_id">
                                        <option value="">{{ __('All') }}</option>
                                    </select>
                                </div>
                            </div>
                        </h4>
                        <div class="row">

                            <div class="container-fluid">
                                <div class="row chat-wrapper">

                                    <!-- LEFT : USER LIST -->
                                    <div class="col-md-4 chat-sidebar">
                                        <div class="chat-header">
                                            <h5>Chats</h5>
                                        </div>

                                        <div class="chat-users">

                                        </div>
                                    </div>

                                    <!-- RIGHT : CHAT WINDOW -->
                                    <div class="col-md-8 chat-main d-flex flex-column">
                                        <div class="chat-header d-flex align-items-center justify-content-between">


                                            <!-- Left : User Name -->
                                            <h6 id="chatUserName" class="mb-0 text-center flex-grow-1">
                                                Select a chat
                                            </h6>

                                            <!-- center : spacer -->
                                            <div style="width:32px"></div>

                                            <!-- Rigt : Reload Button -->
                                            <button id="reloadChat" class="btn btn-sm btn-outline-secondary"
                                                title="Reload chat">
                                                <i class="fa fa-refresh"></i>
                                            </button>



                                        </div>

                                        <div class="chat-messages flex-grow-1" id="chatMessages">
                                            <div class="text-center text-muted mt-5">
                                                Select a teacher or staff to view chat
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#reloadChat').on('click', function() {

            const selectedUserId = $('#user_id').val();
            const activeChatUser = $('.chat-user.active').data('id');

            if (!selectedUserId || !activeChatUser) {
                toastr.info('Select a chat first');
                return;
            }

            $('#chatMessages').html('<p class="text-center mt-3">Reloading...</p>');

            renderChatMessages(selectedUserId, activeChatUser);
        });

        // highlight selected user
        $(document).on('click', '.chat-user', function() {
            $('.chat-user').removeClass('active');
            $(this).addClass('active');
        });

        $(document).ready(function() {
            $('#role').change(function() {

                let role = $(this).val();
                console.log(role);

                $('#user_id').html('<option value="">All</option>');
                $('#class_section_id').html('<option value="">Select Class Section</option>');
                $('#classSectionWrapper').hide();

                if (['Teacher', 'Student', 'Guardian'].includes(role)) {

                    $('#classSectionWrapper').show();

                    $.get("{{ url('get-class-sections') }}", function(sections) {
                        sections.forEach(section => {
                            $('#class_section_id').append(`
                                <option value="${section.id}">
                                    ${section.class.name} - ${section.section.name}
                                </option>
                            `);
                        });
                    });

                } else if (role) {
                    // Direct users for other roles (staff/admin)
                    loadUsersByRole(role);
                }
            });

            $('#class_section_id').change(function() {

                let classSectionId = $(this).val();
                let role = $('#role').val();

                $('#user_id').html('<option value="">All</option>');

                if (!classSectionId) return;

                $.get("{{ url('get-users-by-role-and-class') }}", {
                    role: role,
                    class_section_id: classSectionId
                }, function(users) {

                    users.forEach(user => {
                        $('#user_id').append(`
                            <option value="${user.id}">
                                ${user.name}
                            </option>
                        `);
                    });
                });
            });


            function loadUsersByRole(role) {

                $.get("{{ url('get-users-by-role') }}", {
                    role
                }, function(users) {

                    users.forEach(user => {
                        $('#user_id').append(`
                <option value="${user.id}">
                    ${user.name}
                </option>
            `);
                    });
                });
            }



            $('#user_id').on('change', function() {
                let userId = $(this).val();
                $('.chat-users').html('<p class="text-center mt-3">Loading...</p>');

                if (!userId) {
                    $('.chat-users').html('');
                    return;
                }

                $.get("{{ route('chat-history.users') }}", {
                    user_id: userId
                }, function(users) {

                    let html = '';

                    if (users.length === 0) {
                        html = '<p class="text-center mt-3 text-muted">No chat history</p>';
                    }

                    users.forEach(user => {
                        html += `
                    <div class="chat-user" data-id="${user.id}">
                        <div class="avatar">
                            ${user.first_name.charAt(0).toUpperCase()}
                        </div>
                        <div class="chat-user-info">
                            <h6>${user.first_name} ${user.last_name}</h6>
                            <small>${user.role}</small>
                        </div>
                    </div>
                `;
                    });

                    $('.chat-users').html(html);
                });
            });
        });

        $(document).on('click', '.chat-user', function() {

            let chatUserId = $(this).data('id');
            let selectedUserId = $('#user_id').val();
            let userName = $(this).find('h6').text();

            $('#chatUserName').text(userName);
            $('#chatMessages').html('<p class="text-center mt-3">Loading...</p>');

            renderChatMessages(selectedUserId, chatUserId);
        });

        function renderChatMessages(selectedUserId, chatUserId) {
            $.get("{{ route('chat-history.messages') }}", {
                user_id: selectedUserId,
                chat_user_id: chatUserId
            }, function(chats) {

                let html = '';
                let lastDate = '';

                if (!chats.length) {
                    $('#chatMessages').html(
                        '<p class="text-center mt-3 text-muted">No messages found</p>'
                    );
                    return;
                }

                chats.forEach(chat => {

                    if (!chat.message || !chat.message.length) return;

                    // ✅ LOOP messages (ARRAY)
                    chat.message.forEach(msg => {

                        let msgDateLabel = formatDayLabel(msg.created_at);

                        // 📅 Date separator
                        if (lastDate !== msgDateLabel) {
                            html += `
                                <div class="date-separator text-center mb-3 mt-2">
                                    <span>${msgDateLabel}</span>
                                </div>
                            `;
                            lastDate = msgDateLabel;
                        }

                        let cls = msg.sender_id == selectedUserId ? 'sent' : 'received';

                        html += `
                            <div class="message ${cls}">
                                <div class="text mb-1">${msg.message ?? ''}</div>
                        `;

                        // 📎 Attachments (CORRECT KEY)
                        if (msg.attachment && msg.attachment.length > 0) {
                            html += `<div class="attachments mt-2">`;

                            msg.attachment.forEach(file => {

                                // 🖼 Image preview
                                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(
                                        file.file_type)) {
                                    html += `
                                        <a href="${file.file}" target="_blank">
                                            <img src="${file.file}"
                                                class="img-thumbnail mb-1"
                                                style="max-width:150px;">
                                        </a>
                                    `;
                                }
                                // 📄 PDF preview
                                else if (file.file_type === 'pdf') {

                                    html += `
                                        <div class="pdf-preview mb-2">
                                            <a href="${file.file}" target="_blank"
                                            class="d-flex align-items-center text-decoration-none border rounded p-2">
                                                <i class="fa fa-file-pdf-o text-danger fa-2x mr-2"></i>
                                                <div>
                                                    <div class="font-weight-bold">PDF Document</div>
                                                    <small>Click to view</small>
                                                </div>
                                            </a>
                                        </div>
                                    `;

                                }
                                // 📄 Other files
                                else {
                                    html += `
                                        <a href="${file.file}" download
                                        class="btn btn-sm btn-outline-primary d-block mb-1">
                                            <i class="fa fa-download"></i>
                                            Download ${file.file_type.toUpperCase()}
                                        </a>
                                    `;
                                }
                            });

                            html += `</div>`;
                        }

                        html += `
                                <div class="time">${formatTime(msg.created_at)}</div>
                            </div>
                        `;
                    });
                });

                $('#chatMessages').html(html);
                $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
            });
        }

        function parseCustomDate(dateStr) {
            if (!dateStr) return null;

            // Expected: DD-MM-YYYY hh:mm AM/PM
            const parts = dateStr.match(/(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2}) (AM|PM)/);

            if (!parts) return null;

            let [, day, month, year, hour, minute, meridian] = parts;

            hour = parseInt(hour);
            minute = parseInt(minute);

            if (meridian === 'PM' && hour !== 12) hour += 12;
            if (meridian === 'AM' && hour === 12) hour = 0;

            return new Date(year, month - 1, day, hour, minute);
        }

        function formatTime(dateStr) {
            const date = parseCustomDate(dateStr);
            if (!date) return '';

            return date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatDayLabel(dateStr) {
            const msgDate = parseCustomDate(dateStr);
            if (!msgDate) return '';

            const today = new Date();
            const yesterday = new Date();
            yesterday.setDate(today.getDate() - 1);

            if (msgDate.toDateString() === today.toDateString()) {
                return 'Today';
            }

            if (msgDate.toDateString() === yesterday.toDateString()) {
                return 'Yesterday';
            }

            // 🔹 DD/MM/YYYY format
            const day = String(msgDate.getDate()).padStart(2, '0');
            const month = String(msgDate.getMonth() + 1).padStart(2, '0');
            const year = msgDate.getFullYear();

            return `${day}/${month}/${year}`;
        }
    </script>
@endsection

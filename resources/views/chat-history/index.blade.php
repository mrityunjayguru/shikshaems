@extends('layouts.master')

@section('title')
    {{ __('event') }}
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
            background: #0b911f;
            color: white;
            padding: 12px;
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
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            background: #ECE5DD;
            overflow-y: auto;
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

                                <div class="col-md-4">
                                    <label class="filter-menu">{{ __('Select User') }}</label>
                                    <select class="form-control" id="user_id">
                                        <option value="">{{ __('All') }}</option>
                                    </select>
                                </div>
                            </div>
                        </h4>
                        <div class="row">
                            {{-- <div class="col-12">
                                <table class="table" id="table_list" data-toggle="table"
                                    data-url="{{ route('chat-history.list') }}" data-side-pagination="server"
                                    data-pagination="true" data-search="true" data-query-params="chatQueryParams">

                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }} </th>
                                            <th scope="col" data-field="no"> {{ __('no.') }} </th>
                                            <th scope="col" data-field="date" data-width="150"> {{ __('date') }}
                                            </th>
                                            <th scope="col" data-field="title">{{ __('title') }} </th>
                                            <th scope="col" data-events="tableDescriptionEvents"
                                                data-formatter="descriptionFormatter" data-field="desc">
                                                {{ __('description') }}</th>
                                            @if (Auth::user()->can('holiday-edit') || Auth::user()->can('holiday-delete'))
                                                <th data-events="eventEvents" data-width="150" scope="col"
                                                    data-field="operate">{{ __('action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                </table>
                            </div> --}}
                            <div class="container-fluid">
                                <div class="row chat-wrapper">

                                    <!-- LEFT : USER LIST -->
                                    <div class="col-md-4 chat-sidebar">
                                        <div class="chat-header">
                                            <h5>Chats</h5>
                                        </div>

                                        <div class="chat-users">
                                            {{-- @foreach ($staff as $user)
                                                <div class="chat-user" data-id="{{ $user->id }}">
                                                    <div class="avatar">
                                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                                    </div>
                                                    <div class="chat-user-info">
                                                        <h6>{{ $user->full_name }}</h6>
                                                        <small>{{ ucfirst($user->role) }}</small>
                                                    </div>
                                                </div>
                                            @endforeach --}}
                                            {{-- <p class="text-center mt-3 text-muted">Select Teacher/Staff First</p> --}}
                                        </div>
                                    </div>

                                    <!-- RIGHT : CHAT WINDOW -->
                                    <div class="col-md-8 chat-main">
                                        <div class="chat-header">
                                            <h6 id="chatUserName">Select a chat</h6>
                                        </div>

                                        <div class="chat-messages" id="chatMessages">
                                            <div class="text-center text-muted mt-5">
                                                Select a teacher or staff to view chat
                                            </div>
                                        </div>

                                        <!-- MESSAGE INPUT -->
                                        {{-- <div class="chat-input">
                                            <input type="text" id="messageInput" class="form-control"
                                                placeholder="Type a message..." disabled>
                                            <button class="btn btn-success" id="sendBtn" disabled>
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                        </div> --}}
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
        $(document).ready(function() {
            $('#role').change(function() {
                let role = $(this).val();

                $('#user_id').html('<option value="">All</option>');

                if (role !== '') {
                    $.ajax({
                        url: "{{ url('get-users-by-role') }}",
                        type: "GET",
                        data: {
                            role: role
                        },
                        success: function(response) {
                            $.each(response, function(key, user) {
                                $('#user_id').append(
                                    `<option value="${user.id}">
                                        ${user.name}
                                    </option>`
                                );
                            });
                        }
                    });
                }
            });

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

        $(document).on('click', '.chat-user', function () {

            let chatUserId = $(this).data('id');
            let selectedUserId = $('#user_id').val();
            let userName = $(this).find('h6').text();

            $('#chatUserName').text(userName);
            $('#chatMessages').html('<p class="text-center mt-3">Loading...</p>');

            $.get("{{ route('chat-history.messages') }}", {
                user_id: selectedUserId,
                chat_user_id: chatUserId
            }, function (chats) {

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
                                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(file.file_type)) {
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
        });

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

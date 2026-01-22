<!-- partial:../../partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">

    <div class="sidebar-search pl-4 my-2">
        <input type="text" id="menu-search" placeholder="{{ __('Search menu...') }}"
            class=" menu-search border-theme form-control-sm form-conrol">
    </div>

    <div class="sidebar-search pl-4 pr-4 my-2">
        <input type="text" id="menu-search-mini" placeholder="{{ __('Search menu...') }}"
            class="menu-search d-lg-none border-theme form-conrol">
    </div>

    <ul class="nav">
        {{-- dashboard --}}
        <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2" style="min-width: 22px;">
                    <path d="M8.25 20.1667H13.75C18.3333 20.1667 20.1667 18.3334 20.1667 13.75V8.25004C20.1667 3.66671 18.3333 1.83337 13.75 1.83337H8.25C3.66667 1.83337 1.83333 3.66671 1.83333 8.25004V13.75C1.83333 18.3334 3.66667 20.1667 8.25 20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.16667 1.83337V20.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.16667 7.79163H20.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.16667 14.2084H20.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="menu-title">{{ __('dashboard') }}</span>
            </a>
        </li>
        {{-- Academics --}}
        @canany(['medium-list', 'section-list', 'subject-list', 'class-list', 'subject-list', 'promote-student-list',
        'transfer-student-list'])
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#academics-menu" aria-expanded="false"
                aria-controls="academics-menu">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.3392 1.97089L19.5892 5.27087C19.91 5.3992 20.1667 5.7842 20.1667 6.12336V9.1667C20.1667 9.67087 19.7542 10.0834 19.25 10.0834H2.75C2.24583 10.0834 1.83333 9.67087 1.83333 9.1667V6.12336C1.83333 5.7842 2.09001 5.3992 2.41084 5.27087L10.6608 1.97089C10.8442 1.89756 11.1558 1.89756 11.3392 1.97089Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20.1667 20.1667H1.83333V17.4167C1.83333 16.9125 2.24583 16.5 2.75 16.5H19.25C19.7542 16.5 20.1667 16.9125 20.1667 17.4167V20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.66667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.33333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14.6667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18.3333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M0.916666 20.1666H21.0833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 7.79163C11.7594 7.79163 12.375 7.17602 12.375 6.41663C12.375 5.65723 11.7594 5.04163 11 5.04163C10.2406 5.04163 9.625 5.65723 9.625 6.41663C9.625 7.17602 10.2406 7.79163 11 7.79163Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="menu-title">{{ __('academics') }}</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="academics-menu">
                <ul class="nav flex-column sub-menu">
                    @can('medium-list')
                    <li class="nav-item"><a href="{{ route('mediums.index') }}" class="nav-link"> {{ __('medium') }}
                        </a></li>
                    @endcan

                    @can('section-list')
                    <li class="nav-item"><a href="{{ route('section.index') }}" class="nav-link"> {{ __('section') }}
                        </a></li>
                    @endcan

                    @can('subject-list')
                    <li class="nav-item"><a href="{{ route('subjects.index') }}" class="nav-link"> {{ __('subject') }}
                        </a></li>
                    @endcan

                    @can('semester-list')
                    <li class="nav-item"><a href="{{ route('semester.index') }}" class="nav-link">
                            {{ __('Semester') }} </a></li>
                    @endcan

                    @can('stream-list')
                    <li class="nav-item"><a class="nav-link" href="{{ route('stream.index') }}"> {{ __('Stream') }}
                        </a></li>
                    @endcan

                    @can('shift-list')
                    <li class="nav-item"><a class="nav-link" href="{{ route('shift.index') }}"> {{ __('Shift') }}
                        </a></li>
                    @endcan

                    @can('class-list')
                    <li class="nav-item"><a href="{{ route('class.index') }}" class="nav-link"> {{ __('Class') }}
                        </a></li>
                    <li class="nav-item"><a href="{{ route('class.subject.index') }}" class="nav-link">
                            {{ __('Class Subject') }} </a></li>
                    @endcan

                    @can('class-group-list')
                    <li class="nav-item"><a href="{{ route('class-group.index') }}" class="nav-link">
                            {{ __('class_group') }} </a></li>
                    @endcan



                    @can('class-section-list')
                    <li class="nav-item"><a href="{{ route('class-section.index') }}"
                            class="nav-link">{{ __('Class Section & Teachers') }} </a></li>
                    @endcan

                    @can('assign-elective-subject-list')
                    <li class="nav-item"><a href="{{ route('assign.elective.subject.index') }}"
                            class="nav-link">{{ __('Assign Elective Subject') }} </a></li>
                    @endcan

                    @canany('promote-student-create', 'transfer-student-create')
                    <li class="nav-item"><a href="{{ route('promote-student.index') }}"
                            class="nav-link text-wrap">{{ __('Transfer & Promote Students') }}</a></li>
                    @endcan

                    @can('student-list')
                    <li class="nav-item"><a href="{{ route('students.roll-number.index') }}"
                            class="nav-link">{{ __('assign') }} {{ __('roll_no') }}</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcanany

        {{-- Custom Form Fields --}}
        @role('School Admin')
        @canany(['form-fields-list', 'form-fields-create', 'form-fields-edit', 'form-fields-delete'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('form-fields.index') }}">
                <i class="fa fa-list-alt menu-icon"></i>
                <span class="menu-title"> {{ __('custom_fields') }} </span>
            </a>
        </li>
        @endcan
        @endrole


        {{-- Class Section For Teacher --}}
        @role('Teacher')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('class-section.index') }}">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.3392 1.97089L19.5892 5.27087C19.91 5.3992 20.1667 5.7842 20.1667 6.12336V9.1667C20.1667 9.67087 19.7542 10.0834 19.25 10.0834H2.75C2.24583 10.0834 1.83333 9.67087 1.83333 9.1667V6.12336C1.83333 5.7842 2.09001 5.3992 2.41084 5.27087L10.6608 1.97089C10.8442 1.89756 11.1558 1.89756 11.3392 1.97089Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20.1667 20.1667H1.83333V17.4167C1.83333 16.9125 2.24583 16.5 2.75 16.5H19.25C19.7542 16.5 20.1667 16.9125 20.1667 17.4167V20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.66667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.33333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14.6667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18.3333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M0.916666 20.1666H21.0833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 7.79163C11.7594 7.79163 12.375 7.17602 12.375 6.41663C12.375 5.65723 11.7594 5.04163 11 5.04163C10.2406 5.04163 9.625 5.65723 9.625 6.41663C9.625 7.17602 10.2406 7.79163 11 7.79163Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="menu-title"> {{ __('Class Section') }} </span>
            </a>
        </li>
        @endrole

        {{-- student --}}
        @canany(['student-create', 'student-list', 'student-reset-password', 'class-teacher', 'form-fields-list',
        'form-fields-create', 'form-fields-edit', 'form-fields-delete', 'guardian-create'])
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#student-menu" aria-expanded="false"
                aria-controls="academics-menu">
                <i class="fa fa-graduation-cap menu-icon"></i>
                <span class="menu-title">{{ __('students') }}</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="student-menu">
                <ul class="nav flex-column sub-menu">
                    {{-- Student Addmission Form Manage --}}
                    {{-- @canany(['form-fields-list', 'form-fields-create', 'form-fields-edit', 'form-fields-delete'])
                            <li class="nav-item">
                                <a href="{{ route('form-fields.index') }}" class="nav-link">{{ __('admission_form_fields') }}</i></a>
        </li>
        @endcan --}}
        @can('student-create')
        <li class="nav-item"><a href="{{ route('students.create') }}"
                class="nav-link">{{ __('student_admission') }}</a></li>
        @endcan
        @can('student-create')
        <li class="nav-item"><a href="{{ route('online-registration.index') }}" class="nav-link"
                data-access="@hasFeatureAccess('Website Management')">{{ __('admission_inquiries') }}</a></li>
        @endcan
        @canany(['student-list', 'class-teacher'])
        <li class="nav-item"><a href="{{ route('students.index') }}"
                class="nav-link">{{ __('student_details') }}</a></li>
        @endcanany

        @can('student-reset-password')
        <li class="nav-item"><a href="{{ route('students.reset-password.index') }}"
                class="nav-link">{{ __('students') . ' ' . __('reset_password') }}</a></li>
        @endcan

        @can('student-create')
        <li class="nav-item"><a href="{{ route('students.create-bulk-data') }}"
                class="nav-link">{{ __('add_bulk_data') }}</a></li>
        @endcan

        @can('student-edit')
        <li class="nav-item"><a href="{{ route('students.upload-profile') }}"
                class="nav-link">{{ __('upload_profile_images') }}</a></li>
        @endcan
        <li class="nav-item">
            <a href="{{ route('students.category.index') }}" class="nav-link">
                {{ __('Student Categories') }} </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('students.house.index') }}" class="nav-link"> {{ __('Student House') }}
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('students.birthdays.index') }}" class="nav-link"> {{ __('birthdays') }}
            </a>
        </li>
        {{-- parents --}}
        @can('guardian-create')
        <li class="nav-item">
            <a href="{{ route('guardian.index') }}" class="nav-link"> {{ __('Guardian') }} </a>
        </li>
        @endcan
    </ul>
    </div>
    </li>
    @endcanany

    {{-- teacher --}}
    @can('teacher-create')
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#teacher-menu" aria-expanded="false"
            aria-controls="academics-menu">
            <i class="fa fa-user menu-icon"></i>
            <span class="menu-title">{{ __('teacher') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="teacher-menu">
            <ul class="nav flex-column sub-menu">
                {{-- Teacher Registration --}}
                <li class="nav-item">
                    <a href="{{ route('teachers.index') }}" class="nav-link">
                        <span class="menu-title">{{ __('manage_teacher') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teachers.create-bulk-upload') }}" class="nav-link">
                        <span class="menu-title">{{ __('bulk upload') }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </li>
    @endcan


    {{-- student diary --}}
    @can(['student-diary-list'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#student-diary-menu" aria-expanded="false"
            aria-controls="academics-menu">
            <i class="fa fa-envelope-square menu-icon"></i>
            <span class="menu-title">{{ __('student_diary') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="student-diary-menu">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a href="{{ route('diary-categories.index') }}" class="nav-link">
                        <span class="menu-title">{{ __('diary_category') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('diary.index') }}" class="nav-link">
                        <span class="menu-title">{{ __('manage_diaries') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endcan


    {{-- timetable --}}
    @if (Auth::user()->hasRole('Teacher'))
    <li class="nav-item">
        <a href="{{ route('timetable.teacher.show', Auth::user()->id) }}" class="nav-link"
            data-access="@hasFeatureAccess('Timetable Management')">
            <i class="fa fa-calendar menu-icon"></i>
            <span class="menu-title">{{ __('timetable') }}</span>
        </a>
    </li>
    @else
    @canany(['timetable-create', 'timetable-list'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#timetable-menu" aria-expanded="false"
            aria-controls="timetable-menu" data-access="@hasFeatureAccess('Timetable Management')">
            <i class="fa fa-calendar menu-icon"></i>
            <span class="menu-title">{{ __('timetable') }}</span>
            <i class="menu-arrow"></i>
        </a>

        <div class="collapse" id="timetable-menu">
            <ul class="nav flex-column sub-menu">
                @can('timetable-create')
                <li class="nav-item">
                    <a href="{{ route('timetable.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Timetable Management')">{{ __('create_timetable') }} </a>
                </li>
                @endcan

                @can('timetable-list')
                <li class="nav-item">
                    <a href="{{ route('timetable.teacher.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Timetable Management')">
                        {{ __('teacher_timetable') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany
    @endif

    {{-- Holiday --}}
    @canany(['holiday-create', 'holiday-list'])
    <li class="nav-item">
        @can('holiday-list')
        <a href="{{ route('holiday.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Holiday Management')">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4167 7.33337C18.9354 7.33337 20.1667 6.10216 20.1667 4.58337C20.1667 3.06459 18.9354 1.83337 17.4167 1.83337C15.8979 1.83337 14.6667 3.06459 14.6667 4.58337C14.6667 6.10216 15.8979 7.33337 17.4167 7.33337Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.41667 11.9166H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.41667 15.5834H14.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.8333 1.83337H8.25C3.66667 1.83337 1.83333 3.66671 1.83333 8.25004V13.75C1.83333 18.3334 3.66667 20.1667 8.25 20.1667H13.75C18.3333 20.1667 20.1667 18.3334 20.1667 13.75V9.16671" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('holiday_list') }}</span>
        </a>
        @endcan
    </li>
    @endcanany
    <li class="nav-item">
        {{-- @can('holiday-list') --}}
        <a href="{{ route('event.index') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4167 7.33337C18.9354 7.33337 20.1667 6.10216 20.1667 4.58337C20.1667 3.06459 18.9354 1.83337 17.4167 1.83337C15.8979 1.83337 14.6667 3.06459 14.6667 4.58337C14.6667 6.10216 15.8979 7.33337 17.4167 7.33337Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.41667 11.9166H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.41667 15.5834H14.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.8333 1.83337H8.25C3.66667 1.83337 1.83333 3.66671 1.83333 8.25004V13.75C1.83333 18.3334 3.66667 20.1667 8.25 20.1667H13.75C18.3333 20.1667 20.1667 18.3334 20.1667 13.75V9.16671" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('event_list') }}</span>
        </a>
        {{-- @endcan --}}
    </li>
    {{-- subject lesson --}}
    @canany(['lesson-list', 'lesson-create', 'lesson-edit', 'lesson-delete', 'topic-list', 'topic-create',
    'topic-edit', 'topic-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#subject-lesson-menu" aria-expanded="false"
            aria-controls="subject-lesson-menu" data-access="@hasFeatureAccess('Lesson Management')">
            <i class="fa fa-book menu-icon"></i>
            <span class="menu-title">{{ __('subject_lesson') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="subject-lesson-menu">
            <ul class="nav flex-column sub-menu">
                @canany(['lesson-list', 'lesson-create', 'lesson-edit', 'lesson-delete'])
                <li class="nav-item">
                    <a href="{{ url('lesson') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Lesson Management')">
                        {{ __('create_lesson') }}</a>
                </li>
                @endcanany

                @canany(['topic-list', 'topic-create', 'topic-edit', 'topic-delete'])
                <li class="nav-item">
                    <a href="{{ url('lesson-topic') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Lesson Management')">
                        {{ __('create_topic') }}</a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcanany

    {{-- student assignment --}}
    @canany(['assignment-create', 'assignment-submission'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#student-assignment-menu" aria-expanded="false"
            aria-controls="student-assignment-menu" data-access="@hasFeatureAccess('Assignment Management')">
            <i class="fa fa-tasks menu-icon"></i>
            <span class="menu-title">{{ __('student_assignment') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="student-assignment-menu">
            <ul class="nav flex-column sub-menu">
                @can('assignment-create')
                <li class="nav-item">
                    <a href="{{ route('assignment.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Assignment Management')">
                        {{ __('create_assignment') }}
                    </a>
                </li>
                @endcan
                @can('assignment-submission')
                <li class="nav-item">
                    <a href="{{ route('assignment.submission') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Assignment Management')">
                        {{ __('assignment_submission') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany

    {{-- Slider --}}
    @can('slider-create')
    <li class="nav-item">
        <a href="{{ route('sliders.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Slider Management')">
            <i class="fa fa-list menu-icon"></i>
            <span class="menu-title">{{ __('sliders') }}</span>
        </a>
    </li>
    @endcan

    @canany(['notification-create', 'notification-list', 'notification-delete'])
    <li class="nav-item">
        <a href="{{ route('notifications.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Announcement Management')">
            <i class="fa fa-bell menu-icon"></i>
            <span class="menu-title">{{ __('notification') }}</span>
        </a>
    </li>
    @endcanany

    {{-- Attendance --}}
    @canany(['class-teacher', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#attendance-menu" data-access="@hasFeatureAccess('Attendance Management')"
            aria-expanded="false" aria-controls="attendance-menu">
            <i class="fa fa-check menu-icon"></i>
            <span class="menu-title">{{ __('attendance') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="attendance-menu">
            <ul class="nav flex-column sub-menu">
                @canany(['class-teacher', 'attendance-create'])
                <li class="nav-item">
                    <a href="{{ route('attendance.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Attendance Management')">
                        {{ __('add_attendance') }}
                    </a>
                </li>
                @endcan

                {{-- view attendance --}}
                @canany(['class-teacher', 'attendance-list'])
                <li class="nav-item">
                    <a href="{{ route('attendance.view') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Attendance Management')">
                        {{ __('view_attendance') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance.month') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Attendance Management')">
                        {{ __('month_wise') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany

    {{-- staff attendance --}}
    @if (!Auth::user()->hasRole('School Admin') && Auth::user()->school_id)
    <li class="nav-item">
        <a href="{{ route('staff-attendance.your-index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Attendance Management')">
            <i class="fa fa-calendar-check-o menu-icon"></i>
            <span class="menu-title">{{ __('my_attendance') }}</span>
        </a>
    </li>
    @endif

    {{-- Staff Attendance --}}
    {{-- @canany(['staff-attendance-list', 'staff-attendance-create', 'staff-attendance-edit', 'staff-attendance-delete'])
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#staff-attendance-menu"
                    data-access="@hasFeatureAccess('Staff Attendance Management')" aria-expanded="false" aria-controls="staff-attendance-menu">
                    <i class="fa fa-users menu-icon"></i>
                    <span class="menu-title">{{ __('Staff Attendance') }}</span>
    <i class="menu-arrow"></i>
    </a>
    <div class="collapse" id="staff-attendance-menu">
        <ul class="nav flex-column sub-menu">
            @canany(['staff-attendance-create'])
            <li class="nav-item">
                <a href="{{ route('staff-attendance.index') }}" class="nav-link"
                    data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Attendance Management')">
                    {{ __('add_staff_attendance') }}
                </a>
            </li>
            @endcan


            @canany(['staff-attendance-list'])
            <li class="nav-item">
                <a href="{{ route('staff-attendance.view') }}" class="nav-link"
                    data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Attendance Management')">
                    {{ __('view_staff_attendance') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('staff-attendance.month') }}" class="nav-link"
                    data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Attendance Management')">
                    {{ __('month_wise') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    </li>
    @endcanany --}}

    {{-- announceent --}}
    @can('announcement-list')
    <li class="nav-item">
        <a href="{{ route('announcement.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Announcement Management')">
            <i class="fa fa-bullhorn menu-icon"></i>
            <span class="menu-title">{{ __('announcement') }}</span>
        </a>
    </li>
    @endcan

    {{-- exam --}}
    @canany(['exam-create', 'exam-upload-marks', 'grade-create', 'exam-result', 'view-exam-marks'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#exam-menu" aria-expanded="false"
            aria-controls="exam-menu" data-access="@hasFeatureAccess('Exam Management')">
            <i class="fa fa-book menu-icon"></i>
            <span class="menu-title">{{ __('Offline Exam') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="exam-menu">
            <ul class="nav flex-column sub-menu">
                @can('exam-create')
                <li class="nav-item">
                    <a href="{{ route('exams.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('manage_exam') }}
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('exams.timetable') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('timetable') }}
                    </a>
                </li>

                @can('view-exam-marks')
                <li class="nav-item">
                    <a href="{{ route('exam.view-marks') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('track_exam_marks') }}
                    </a>
                </li>
                @endcan

                @can('exam-upload-marks')
                <li class="nav-item">
                    <a href="{{ route('exams.upload-marks') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('upload_exam_marks') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('exam.bulk-upload-marks') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('bulk_upload_exam_marks') }}
                    </a>
                </li>
                @endcan
                @can('exam-result')
                <li class="nav-item">
                    <a href="{{ route('exams.get-result') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('Exam Result') }}
                    </a>
                </li>
                @endcan

                @can('grade-create')
                <li class="nav-item">
                    <a href="{{ route('exam.grade.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('exam_grade') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcan

    {{-- Online Exam --}}
    @canany(['online-exam-create', 'online-exam-list', 'online-exam-edit', 'online-exam-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#online-exam-menu" aria-expanded="false"
            aria-controls="online-exam-menu" data-access="@hasFeatureAccess('Exam Management')">
            <i class="fa fa-laptop menu-icon"></i>
            <span class="menu-title">{{ __('online_exam') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="online-exam-menu">
            <ul class="nav flex-column sub-menu">
                @can('online-exam-list')
                <li class="nav-item">

                    <a href="{{ route('online-exam.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('manage_online_exam') }}
                    </a>
                </li>
                @endcan
                @can('online-exam-create')
                <li class="nav-item">
                    <a href="{{ route('online-exam-question.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('manage_questions') }}
                    </a>
                </li>
                @endcan
                @can('online-exam-create')
                <li class="nav-item">
                    <a href="{{ route('online-exam-question.add-bulk-questions') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
                        {{ __('add_bulk_questions') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany

    {{-- Fees --}}

    @canany(['fees-list', 'fees-type-list', 'fees-classes-list', 'fees-paid'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#fees-menu" aria-expanded="false"
            aria-controls="fees-menu" data-access="@hasFeatureAccess('Fees Management')">
            <i class="fa fa-dollar menu-icon"></i>
            <span class="menu-title">{{ __('Fees') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="fees-menu">
            <ul class="nav flex-column sub-menu">
                @can('fees-type-list')
                <li class="nav-item">
                    <a href="{{ route('fees-type.index') }}" class="nav-link" data-access="@hasFeatureAccess('Fees Management')">
                        {{ __('Fees Type') }}
                    </a>
                </li>
                @endcan
                @can('fees-list')
                <li class="nav-item">
                    <a href="{{ route('fees.index') }}" class="nav-link" data-access="@hasFeatureAccess('Fees Management')">
                        {{ __('Manage Fee') }}</a>
                </li>
                @endcan
                @can('fees-paid')
                <li class="nav-item">
                    <a href="{{ route('fees.paid.index') }}" class="nav-link" data-access="@hasFeatureAccess('Fees Management')">
                        {{ __('Student Fees') }}
                    </a>
                </li>
                @endcan
                @can('fees-paid')
                <li class="nav-item">
                    <a href="{{ route('fees.optional') }}" class="nav-link" data-access="@hasFeatureAccess('Fees Management')">
                        {{ __('Optional Fee') }}</a>
                </li>
                @endcan
                @can('fees-paid')
                <li class="nav-item">
                    <a href="{{ route('fees.transactions.log.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Fees Management')"> {{ __('Fees Transaction Logs') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcan

    {{-- Transportation Module --}}
    @canany(['route-list', 'pickup-points-list', 'vehicles-list', 'RouteVehicle-list', 'driver-helper-list',
    'transportationRequests-list', 'transportationexpense-list'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#transportation-menu" aria-expanded="false"
            aria-controls="transportation-menu" data-access="@hasFeatureAccess('Transportation Module')">
            <i class="fa fa-bus menu-icon"></i>
            <span class="menu-title">{{ __('Transportations') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="transportation-menu">
            <ul class="nav flex-column sub-menu">
                @can('vehicles-list')
                <li class="nav-item">
                    <a href="{{ route('vehicles.index') }}" class="nav-link" data-access="@hasFeatureAccess('Transportation Module')">
                        {{ __('vehicles') }}</a>
                </li>
                @endcan
                @role('School Admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('code.index') }}"> {{ __('code') }}
                    </a>
                </li>
                @endrole
                @can('vehicles-list')
                <li class="nav-item">
                    <a href="{{ route('proximity.index') }}" class="nav-link" data-access="@hasFeatureAccess('Transportation Module')">
                        {{ __('set_proximity') }}</a>
                </li>
                @endcan
                @canany(['pickup-points-list'])
                <li class="nav-item">
                    <a href="{{ route('pickup-points.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Transportation Module')"> {{ __('pickup_points') }}</a>
                </li>
                @endcanany
                @can('route-list')
                <li class="nav-item">
                    <a href="{{ route('routes.index') }}" class="nav-link" data-access="@hasFeatureAccess('Transportation Module')">
                        {{ __('manage_routes') }}</a>
                </li>
                @endcan
                @can('RouteVehicle-list')
                <li class="nav-item">
                    <a href="{{ route('route-vehicle.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Transportation Module')"> {{ __('manage_route_vehicles') }}</a>
                </li>
                @endcan
                @can('driver-helper-list')
                <li class="nav-item">
                    <a href="{{ route('driver-helper.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Transportation Module')"> {{ __('manage_driver_helper') }}</a>
                </li>
                @endcan
                @can('transportationRequests-list')
                <li class="nav-item">
                    <a href="{{ route('transportation-requests.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Transportation Module')"> {{ __('transportation_requests') }}</a>
                </li>
                @endcan
                @can('transportationexpense-list')
                <li class="nav-item">
                    <a href="{{ route('transportation-expense.index') }}" class="nav-link"
                        data-access="@hasFeatureAccess('Transportation Module')"> {{ __('transportation_expense') }}</a>
                </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('vehicles.track-now') }}" class="nav-link" target="_blank">
                        {{ __('track_now') }}</a>
                </li>
            </ul>
        </div>
    </li>
    @endcan

    {{-- Leave --}}
    @canany(['leave-list', 'leave-create', 'leave-edit', 'leave-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#staff-leave-menu" data-access="@hasFeatureAccess('Staff Leave Management')"
            aria-expanded="false" aria-controls="staff-leave-menu">
            <i class="fa fa-plane menu-icon"></i>
            <span class="menu-title">{{ __('leave') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="staff-leave-menu">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a href="{{ route('leave.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Leave Management')">
                        {{ __('apply_leave') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('leave.report') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Staff Leave Management')">
                        {{ __('leave_report') }}
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endcanany

    {{-- report --}}
    {{-- @role('School Admin') --}}
    @if ((Auth::user()->school_id && Auth::user()->staff) || Auth::user()->hasRole('School Admin'))
    @canany(['reports-student', 'reports-exam', 'report-list'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#report-menu" aria-expanded="false"
            aria-controls="report-menu">
            <i class="fa fa-file-text menu-icon"></i>
            <span class="menu-title">{{ __('Report') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="report-menu">
            <ul class="nav flex-column sub-menu">
                @can('reports-student')
                <li class="nav-item">
                    <a href="{{ route('reports.student.student-reports') }}" class="nav-link">
                        {{ __('Student Reports') }}
                    </a>
                </li>
                @endcan
                @can('reports-exam')
                <li class="nav-item">
                    <a href="{{ route('reports.exam.exam-reports') }}" class="nav-link">
                        {{ __('Exam Reports') }}
                    </a>
                </li>
                @endcan
                @can('reports-expense')
                <li class="nav-item">
                    <a href="{{ route('reports.expense.list') }}" class="nav-link">
                        {{ __('expense_report') }}
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany
    @endif
    {{-- @endrole --}}

    @if (Auth::user()->school_id && Auth::user()->staff)
    <li class="nav-item">
        <a href="{{ route('payroll.slip.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Expense Management')">
            <i class="fa fa-money menu-icon"></i>
            <span class="menu-title">{{ __('payroll') }} {{ __('slips') }}</span>
        </a>
    </li>
    @endif

    {{-- Schools --}}
    @canany(['schools-list', 'schools-create', 'schools-edit', 'schools-delete', 'school-custom-field-list',
    'school-custom-field-create', 'school-custom-field-edit', 'school-custom-field-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#school-menu" aria-expanded="false"
            aria-controls="school-menu">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.3392 1.97089L19.5892 5.27087C19.91 5.3992 20.1667 5.7842 20.1667 6.12336V9.1667C20.1667 9.67087 19.7542 10.0834 19.25 10.0834H2.75C2.24583 10.0834 1.83333 9.67087 1.83333 9.1667V6.12336C1.83333 5.7842 2.09001 5.3992 2.41084 5.27087L10.6608 1.97089C10.8442 1.89756 11.1558 1.89756 11.3392 1.97089Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 20.1667H1.83333V17.4167C1.83333 16.9125 2.24583 16.5 2.75 16.5H19.25C19.7542 16.5 20.1667 16.9125 20.1667 17.4167V20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.66667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6667 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18.3333 16.5V10.0834" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M0.916666 20.1666H21.0833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 7.79163C11.7594 7.79163 12.375 7.17602 12.375 6.41663C12.375 5.65723 11.7594 5.04163 11 5.04163C10.2406 5.04163 9.625 5.65723 9.625 6.41663C9.625 7.17602 10.2406 7.79163 11 7.79163Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('schools') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="school-menu">
            <ul class="nav flex-column sub-menu">
                @canany(['school-custom-field-list', 'school-custom-field-create', 'school-custom-field-edit',
                'school-custom-field-delete'])
                <li class="nav-item">
                    <a href="{{ route('school-custom-fields.index') }}" class="nav-link">
                        {{ __('school_register_form_fields') }}
                    </a>
                </li>
                @if (isset($systemSettings['school_inquiry']) && $systemSettings['school_inquiry'] == 1)
                <li class="nav-item">
                    <a href="{{ route('school-inquiry.index') }}" class="nav-link">
                        {{ __('school_inquires') }}
                    </a>
                </li>
                @endif
                @endcanany
                @canany(['schools-list', 'schools-create', 'schools-edit', 'schools-delete'])
                <li class="nav-item">
                    <a href="{{ route('schools.index') }}" class="nav-link">
                        {{ __('schools_details') }}
                    </a>
                </li>
                @endcanany
                <li class="nav-item">
                    <a href="{{ route('vehicle-type.index') }}" class="nav-link">
                        {{ __('manage_vehicle_type') }}
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endcanany

    {{-- package --}}
    @canany(['package-list', 'package-create', 'package-edit', 'package-delete'])
    <li class="nav-item">
        <a href="{{ route('package.index') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2 viewBox=" 0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.90583 6.81995L11 11.5041L19.0392 6.84745" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 19.8092V11.495" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.1025 2.27331L4.2075 4.98664C3.09833 5.60081 2.19083 7.14081 2.19083 8.40581V13.585C2.19083 14.85 3.09833 16.39 4.2075 17.0041L9.1025 19.7266C10.1475 20.3041 11.8617 20.3041 12.9067 19.7266L17.8017 17.0041C18.9108 16.39 19.8183 14.85 19.8183 13.585V8.40581C19.8183 7.14081 18.9108 5.60081 17.8017 4.98664L12.9067 2.26414C11.8525 1.68664 10.1475 1.68664 9.1025 2.27331Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="menu-title">{{ __('package') }}</span>
        </a>
    </li>
    @endcan
    {{-- Addons --}}
    @canany(['addons-list', 'addons-create', 'addons-edit', 'addons-delete'])
    <li class="nav-item">
        <a href="{{ route('addons.index') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.0833 16.5C21.0833 17.1875 20.8908 17.8384 20.5517 18.3884C20.3592 18.7184 20.1117 19.0117 19.8275 19.25C19.1858 19.8275 18.3425 20.1667 17.4167 20.1667C16.2983 20.1667 15.2992 19.6626 14.6392 18.8742C14.6208 18.8467 14.5933 18.8284 14.575 18.8009C14.465 18.6725 14.3642 18.535 14.2817 18.3884C13.9425 17.8384 13.75 17.1875 13.75 16.5C13.75 15.345 14.2817 14.3092 15.125 13.64C15.7575 13.1359 16.555 12.8334 17.4167 12.8334C18.3333 12.8334 19.1583 13.1633 19.8 13.7225C19.91 13.805 20.0108 13.9059 20.1025 14.0067C20.7075 14.6667 21.0833 15.5375 21.0833 16.5Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18.7825 16.4816H16.0508" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.4167 15.1433V17.8841" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.90583 6.82007L11 11.5042L19.0392 6.84754" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 19.8092V11.495" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M19.8092 8.40588V13.5942C19.8092 13.6401 19.8092 13.6767 19.8 13.7225C19.1583 13.1634 18.3333 12.8334 17.4167 12.8334C16.555 12.8334 15.7575 13.1359 15.125 13.6401C14.2817 14.3092 13.75 15.3451 13.75 16.5001C13.75 17.1876 13.9425 17.8384 14.2817 18.3884C14.3642 18.5351 14.465 18.6726 14.575 18.8009L12.8975 19.7267C11.8525 20.3134 10.1475 20.3134 9.10249 19.7267L4.2075 17.0134C3.09833 16.3992 2.19083 14.8592 2.19083 13.5942V8.40588C2.19083 7.14088 3.09833 5.60089 4.2075 4.98673L9.10249 2.27337C10.1475 1.68671 11.8525 1.68671 12.8975 2.27337L17.7925 4.98673C18.9017 5.60089 19.8092 7.14088 19.8092 8.40588Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="menu-title">{{ __('addons') }}</span>
        </a>
    </li>
    @endcan

    {{-- Features list --}}
    @canany(['addons-list', 'addons-create', 'addons-edit', 'addons-delete', 'package-list', 'package-create',
    'package-edit', 'package-delete'])
    <li class="nav-item">
        <a href="{{ url('features') }}" class="nav-link">
            <svg width="22" height="22" viewBox="0 0 22 22" class="mr-2" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33333 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6667 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.6833 19.6167C18.3034 19.6167 19.6167 18.3034 19.6167 16.6833C19.6167 15.0633 18.3034 13.75 16.6833 13.75C15.0633 13.75 13.75 15.0633 13.75 16.6833C13.75 18.3034 15.0633 19.6167 16.6833 19.6167Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 20.1667L19.25 19.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.20833 8.33252H18.7917" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.2558 20.1667H7.33333C4.125 20.1667 2.75 18.3334 2.75 15.5834V7.79171C2.75 5.04171 4.125 3.20837 7.33333 3.20837H14.6667C17.875 3.20837 19.25 5.04171 19.25 7.79171V11.9167" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.9959 12.5583H11.0041" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60312 12.5583H7.61135" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60312 15.3083H7.61135" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('features') }}</span>
        </a>
    </li>
    @endcan

    {{-- subscription-view --}}
    @can('subscription-view')
    <li class="nav-item">
        <a href="{{ url('subscriptions/report') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 5.09668H20.1667" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.035 1.83337H18.1317C19.7633 1.83337 20.1667 2.23671 20.1667 3.85004V7.61754C20.1667 9.23087 19.7633 9.63421 18.1317 9.63421H13.035C11.4033 9.63421 11 9.23087 11 7.61754V3.85004C11 2.23671 11.4033 1.83337 13.035 1.83337Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 15.6383H11" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.86833 12.375H8.965C10.5967 12.375 11 12.7783 11 14.3917V18.1592C11 19.7725 10.5967 20.1758 8.965 20.1758H3.86833C2.23667 20.1758 1.83333 19.7725 1.83333 18.1592V14.3917C1.83333 12.7783 2.23667 12.375 3.86833 12.375Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 13.75C20.1667 17.2975 17.2975 20.1667 13.75 20.1667L14.7125 18.5625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 8.25004C1.83333 4.70254 4.7025 1.83337 8.25 1.83337L7.28751 3.43754" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('subscription') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('subscriptions/transactions') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.5833 18.7917H6.41667C3.66667 18.7917 1.83333 17.4167 1.83333 14.2084V7.79171C1.83333 4.58337 3.66667 3.20837 6.41667 3.20837H15.5833C18.3333 3.20837 20.1667 4.58337 20.1667 7.79171V14.2084C20.1667 17.4167 18.3333 18.7917 15.5833 18.7917Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 13.75C12.5188 13.75 13.75 12.5188 13.75 11C13.75 9.48122 12.5188 8.25 11 8.25C9.48122 8.25 8.25 9.48122 8.25 11C8.25 12.5188 9.48122 13.75 11 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 8.24996H2.75C5.5 8.24996 6.41667 7.33329 6.41667 4.58329V3.66663" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 8.24996H19.25C16.5 8.24996 15.5833 7.33329 15.5833 4.58329V3.66663" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 13.75H2.75C5.5 13.75 6.41667 14.6667 6.41667 17.4167V18.3333" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 13.75H19.25C16.5 13.75 15.5833 14.6667 15.5833 17.4167V18.3333" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('subscription_transaction') }}</span>
        </a>
    </li>
    @endcan


    {{-- Expense --}}
    @canany(['expense-category-create', 'expense-category-list', 'expense-category-edit', 'expense-category-delete',
    'expense-create', 'expense-list', 'expense-edit', 'expense-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#expense-menu" aria-expanded="false"
            aria-controls="expense-menu" data-access="@hasFeatureAccess('Expense Management')">
            <i class="fa fa-money menu-icon"></i>
            <span class="menu-title">{{ __('expense') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="expense-menu">
            <ul class="nav flex-column sub-menu">
                @canany(['expense-category-create', 'expense-category-list', 'expense-category-edit',
                'expense-category-delete'])
                <li class="nav-item">
                    <a href="{{ route('expense-category.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Expense Management')">{{ __('manage_category') }} </a>
                </li>
                @endcanany

                @canany(['expense-create', 'expense-list', 'expense-edit', 'expense-delete'])
                <li class="nav-item">
                    <a href="{{ route('expense.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Expense Management')">
                        {{ __('manage_expense') }}
                    </a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcanany

    {{-- Payroll --}}
    @canany(['payroll-create', 'payroll-list', 'payroll-edit', 'payroll-delete', 'payroll-settings-list',
    'payroll-settings-create', 'payroll-settings-edit', 'payroll-settings-delete'])
    <li class="nav-item">
        <a href="#payroll-menu" class="nav-link" data-toggle="collapse"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Expense Management')">
            <i class="fa fa-credit-card-alt menu-icon"></i>
            <span class="menu-title">{{ __('payroll') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="payroll-menu">
            <ul class="nav flex-column sub-menu">
                @canany(['payroll-create', 'payroll-edit', 'payroll-list'])
                <li class="nav-item">
                    <a href="{{ route('payroll.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Expense Management')">{{ __('manage_payroll') }} </a>
                </li>
                @endcanany

                @canany(['payroll-settings-list', 'payroll-settings-create', 'payroll-settings-edit',
                'payroll-settings-delete'])
                <li class="nav-item">
                    <a href="{{ route('payroll-setting.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Expense Management')">
                        {{ __('payroll_setting') }}
                    </a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcanany

    {{-- session-year --}}
    {{-- @can('session-year-create')
            <li class="nav-item">
                <a href="{{ route('session-year.index') }}" class="nav-link">
    <i class="fa fa-calendar-o menu-icon"></i>
    <span class="menu-title">{{ __('Session Years') }}</span>
    </a>
    </li>
    @endcan --}}

    {{-- gallery --}}
    @canany(['gallery-create', 'gallery-list', 'gallery-edit', 'gallery-delete'])
    <li class="nav-item">
        <a href="{{ route('gallery.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('School Gallery Management')">
            <i class="fa fa-picture-o menu-icon"></i>
            <span class="menu-title">{{ __('gallery') }}</span>
        </a>
    </li>
    @endcan

    {{-- Certificate --}}
    @canany(['certificate-create', 'certificate-list', 'certificate-edit', 'certificate-delete', 'student-list',
    'class-teacher', 'id-card-settings'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#certificate-menu" aria-expanded="false"
            aria-controls="certificate-menu" data-access="@hasFeatureAccess('ID Card - Certificate Generation')">
            <i class="fa fa-trophy menu-icon"></i>
            <span class="menu-title">{{ __('certificate_id_card') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="certificate-menu">
            <ul class="nav flex-column sub-menu">

                @canany(['certificate-create', 'certificate-list', 'certificate-edit', 'certificate-delete'])
                <li class="nav-item">
                    <a href="{{ url('certificate-template') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('ID Card - Certificate Generation')">
                        {{ __('certificate_template') }}
                    </a>
                </li>
                @endcanany

                @canany(['certificate-list'])
                <li class="nav-item">
                    <a href="{{ url('certificate') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('ID Card - Certificate Generation')">
                        {{ __('student_certificate') }}
                    </a>
                </li>
                @endcanany

                @canany(['certificate-list'])
                <li class="nav-item">
                    <a href="{{ url('certificate/staff-certificate') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('ID Card - Certificate Generation')">
                        {{ __('staff_certificate') }}
                    </a>
                </li>
                @endcanany

                @can('id-card-settings')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('id-card-settings') }}"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('ID Card - Certificate Generation')">{{ __('id_card_settings') }}</a>
                </li>
                @endcan

                @canany(['student-list', 'class-teacher'])
                <li class="nav-item"><a href="{{ route('students.generate-id-card-index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('ID Card - Certificate Generation')">{{ __('student_id_card') }}</a></li>
                @endcanany

                @can('staff-list')
                <li class="nav-item">
                    <a href="{{ route('staff.id-card') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('ID Card - Certificate Generation')">{{ __('staff_id_card') }}</a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcanany

    @if (Auth::user()->school_id)
    @canany(['role-list', 'role-create', 'role-edit', 'role-delete', 'staff-list', 'staff-create', 'staff-edit',
    'staff-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#staff-management" aria-expanded="false"
            aria-controls="staff-management-menu" data-access="@hasFeatureAccess('Staff Management')">
            <i class="fa fa-user-secret menu-icon"></i>
            <span class="menu-title">{{ __('Staff Management') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="staff-management">
            <ul class="nav flex-column sub-menu">
                @canany(['role-list', 'role-create', 'role-edit', 'role-delete'])
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Management')">{{ __('Role & Permission') }}</a>
                </li>
                @endcanany
                @canany(['staff-list', 'staff-create', 'staff-edit', 'staff-delete'])
                <li class="nav-item">
                    <a href="{{ route('staff.index') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Management')">{{ __('staff') }}</a>
                </li>
                @endcanany
                @canany(['staff-list', 'staff-create', 'staff-edit', 'staff-delete'])
                <li class="nav-item">
                    <a href="{{ route('staff.create-bulk-upload') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Management')">{{ __('bulk upload') }}</a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcan

    {{-- Staff Leave Management --}}
    @canany(['approve-leave'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#staff-leave-management" aria-expanded="false"
            aria-controls="staff-leave-management-menu" data-access="@hasFeatureAccess('Staff Leave Management')">
            <i class="fa fa-plane menu-icon"></i>
            <span class="menu-title">{{ __('Staff Leave') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="staff-leave-management">
            <ul class="nav flex-column sub-menu">

                @can('approve-leave')
                <li class="nav-item">
                    <a href="{{ route('leave.request') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Leave Management')">{{ __('staff') }} {{ __('leave') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('leave/report') }}" class="nav-link"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Leave Management')">{{ __('leave_report') }}</a>
                </li>
                @endcan
            </ul>
        </div>
    </li>
    @endcan
    @else
    @canany(['role-list', 'role-create', 'role-edit', 'role-delete', 'staff-list', 'staff-create', 'staff-edit',
    'staff-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#staff-management" aria-expanded="false"
            aria-controls="staff-management-menu">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.39667 9.96421C8.305 9.95504 8.195 9.95504 8.09417 9.96421C5.9125 9.89087 4.18 8.10337 4.18 5.90337C4.18 3.65754 5.995 1.83337 8.25 1.83337C10.4958 1.83337 12.32 3.65754 12.32 5.90337C12.3108 8.10337 10.5783 9.89087 8.39667 9.96421Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M15.0425 3.66663C16.8208 3.66663 18.2508 5.10579 18.2508 6.87496C18.2508 8.60746 16.8758 10.0191 15.1617 10.0833C15.0883 10.0741 15.0058 10.0741 14.9233 10.0833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.81333 13.3466C1.595 14.8316 1.595 17.2516 3.81333 18.7275C6.33417 20.4141 10.4683 20.4141 12.9892 18.7275C15.2075 17.2425 15.2075 14.8225 12.9892 13.3466C10.4775 11.6691 6.34333 11.6691 3.81333 13.3466Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.8117 18.3334C17.4717 18.1959 18.095 17.93 18.6083 17.5359C20.0383 16.4634 20.0383 14.6942 18.6083 13.6217C18.1042 13.2367 17.49 12.98 16.8392 12.8334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('Staff Management') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="staff-management">
            <ul class="nav flex-column sub-menu">
                @canany(['role-list', 'role-create', 'role-edit', 'role-delete'])
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link">{{ __('Role & Permission') }}</a>
                </li>
                @endcanany
                @canany(['staff-list', 'staff-create', 'staff-edit', 'staff-delete'])
                <li class="nav-item">
                    <a href="{{ route('staff.index') }}" class="nav-link">{{ __('staff') }}</a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcan
    @endif

    @canany(['custom-school-email'])
    <li class="nav-item">
        <a href="{{ route('schools.send.mail') }}" class="nav-link">

            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.83333 7.79171C1.83333 4.58337 3.66667 3.20837 6.41667 3.20837H15.5833C18.3333 3.20837 20.1667 4.58337 20.1667 7.79171V14.2084C20.1667 17.4167 18.3333 18.7917 15.5833 18.7917H6.41667" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M15.5833 8.25L12.7142 10.5417C11.77 11.2933 10.2208 11.2933 9.27666 10.5417L6.41667 8.25" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 15.125H7.33333" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 11.4584H4.58333" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('email_schools') }}</span>
        </a>
    </li>
    @endcan

    @role(['School Admin', 'Teacher'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#student-leave-management" aria-expanded="false"
            aria-controls="student-leave-management-menu" {{-- data-access="@hasFeatureAccess('Student Leave Management') --}} ">
                                            <i class=" fa fa-plane menu-icon"></i>
            <span class="menu-title">{{ __('Student Leave') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="student-leave-management">
            <ul class="nav flex-column sub-menu">

                {{-- @can('approve-leave') --}}
                <li class="nav-item">
                    <a href="{{ route('student-leave.index') }}" class="nav-link"
                        {{-- data-name="{{ Auth::user()->getRoleNames()[0] }}" --}}
                        {{-- data-access="@hasFeatureAccess('Student Leave Management') --}}>{{ __('Student') }} {{ __('leave') }}</a>
                </li>
                {{-- <li class="nav-item">
                                <a href="{{ url('leave/report') }}" class="nav-link"
                data-name="{{ Auth::user()->getRoleNames()[0] }}"
                data-access="@hasFeatureAccess('Staff Leave Management')">{{ __('leave_report') }}</a>
    </li> --}}
    {{-- @endcan --}}
    </ul>
    </div>
    </li>
    @endrole
    {{-- Subscription Plans & Addons --}}
    @role('School Admin')
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#subscription" aria-expanded="false"
            aria-controls="subscription-menu">
            <img src="{{ asset('assets/icons/admin-icons/addons.svg') }}" alt="addons" class="w-5 h-5 svg-theme-stroke mr-2">
            <span class="menu-title">{{ __('subscription') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="subscription">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('subscriptions.history') }}">{{ __('subscription') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('subscriptions.index') }}">{{ __('plans') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('addons.plan') }}">{{ __('addons') }}</a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Support --}}
    <li class="nav-item">
        <a href="{{ url('staff/support') }}" class="nav-link">
            <i class="fa fa-question menu-icon"></i>
            <span class="menu-title">{{ __('support') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('features') }}" class="nav-link">
            <i class="fa fa-list-ul menu-icon"></i>
            <span class="menu-title">{{ __('features') }}</span>
        </a>
    </li>

    @endrole

    {{-- Contact Inquiry --}}
    @canany(['contact-inquiry-list'])
    <li class="nav-item">
        <a href="{{ url('contact-inquiry') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.07081 1.83337H11.2292C11.9075 1.83337 12.4666 2.39255 12.4666 3.07088V4.42753C12.4666 4.92253 12.155 5.53671 11.8525 5.84838L9.19419 8.19504C8.82753 8.50671 8.57999 9.12087 8.57999 9.61587V12.2742C8.57999 12.6409 8.3325 13.1359 8.02083 13.3284L7.15916 13.8875C6.3525 14.3825 5.24331 13.8234 5.24331 12.8334V9.56087C5.24331 9.13003 4.99583 8.57088 4.74833 8.25922L2.40166 5.78421C2.09 5.47254 1.84251 4.92253 1.84251 4.5467V3.12588C1.83334 2.39254 2.39248 1.83337 3.07081 1.83337Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 11V13.75C1.83333 18.3334 3.66667 20.1667 8.25 20.1667H13.75C18.3333 20.1667 20.1667 18.3334 20.1667 13.75V8.25002C20.1667 5.39002 19.4516 3.59335 17.7925 2.65835C17.325 2.39251 16.39 2.19084 15.5375 2.05334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.9167 11.9166H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.0833 15.5834H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('Contact Inquiry') }}</span>
        </a>
    </li>
    @endcanany
    {{-- Super admin web settings --}}
    @can('web-settings')
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#web_settings" aria-expanded="false"
            aria-controls="web_settings-menu">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.1667 11C20.1667 5.94004 16.06 1.83337 11 1.83337C5.94 1.83337 1.83333 5.94004 1.83333 11C1.83333 16.06 5.94 20.1667 11 20.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33334 2.75H8.25001C6.46251 8.10333 6.46251 13.8967 8.25001 19.25H7.33334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.75 2.75C14.6392 5.42667 15.0883 8.21333 15.0883 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 14.6667V13.75C5.42667 14.6392 8.21333 15.0884 11 15.0884" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 8.25005C8.10333 6.46255 13.8967 6.46255 19.25 8.25005" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.6092 14.4284L14.3642 17.6734C14.2358 17.8017 14.1167 18.04 14.0892 18.2142L13.915 19.4517C13.8508 19.9008 14.1625 20.2125 14.6117 20.1483L15.8492 19.9742C16.0233 19.9467 16.2708 19.8275 16.39 19.6992L19.635 16.4542C20.1942 15.895 20.46 15.2442 19.635 14.4192C18.8192 13.6033 18.1683 13.8692 17.6092 14.4284Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.1417 14.8959C17.4167 15.8859 18.1867 16.6558 19.1767 16.9308" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('web_settings') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="web_settings">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('web-settings.index') }}">{{ __('general_settings') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('web-settings.feature.sections') }}">{{ __('feature_sections') }}</a>
                </li>

                @canany(['faqs-create', 'faqs-list', 'faqs-edit', 'faqs-delete'])
                <li class="nav-item">

                    <a class="nav-link" href="{{ route('faqs.index') }}">{{ __('faqs') }}</a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcan

    {{-- School web page setttings --}}
    @can('school-web-settings')
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#web_settings" aria-expanded="false"
            aria-controls="web_settings-menu" data-access="@hasFeatureAccess('Website Management')">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.1667 11C20.1667 5.94004 16.06 1.83337 11 1.83337C5.94 1.83337 1.83333 5.94004 1.83333 11C1.83333 16.06 5.94 20.1667 11 20.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33334 2.75H8.25001C6.46251 8.10333 6.46251 13.8967 8.25001 19.25H7.33334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.75 2.75C14.6392 5.42667 15.0883 8.21333 15.0883 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 14.6667V13.75C5.42667 14.6392 8.21333 15.0884 11 15.0884" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 8.25005C8.10333 6.46255 13.8967 6.46255 19.25 8.25005" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.6092 14.4284L14.3642 17.6734C14.2358 17.8017 14.1167 18.04 14.0892 18.2142L13.915 19.4517C13.8508 19.9008 14.1625 20.2125 14.6117 20.1483L15.8492 19.9742C16.0233 19.9467 16.2708 19.8275 16.39 19.6992L19.635 16.4542C20.1942 15.895 20.46 15.2442 19.635 14.4192C18.8192 13.6033 18.1683 13.8692 17.6092 14.4284Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.1417 14.8959C17.4167 15.8859 18.1867 16.6558 19.1767 16.9308" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('web_settings') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="web_settings">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school.web-settings.index') }}"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Website Management')">{{ __('content') }}</a>
                </li>

                @canany(['faqs-create', 'faqs-list', 'faqs-edit', 'faqs-delete'])
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('faqs.index') }}"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Website Management')">{{ __('faqs') }}</a>
                </li>
                @endcanany
            </ul>
        </div>
    </li>
    @endcan




    {{-- settings --}}
    @canany(['app-settings', 'language-list', 'school-setting-manage', 'system-setting-manage', 'fcm-setting-manage', 'email-setting-create', 'privacy-policy', 'contact-us', 'about-us', 'guidance-create', 'guidance-list', 'guidance-edit', 'guidance-delete', 'email-template'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#settings-menu" aria-expanded="false"
            aria-controls="settings-menu">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.25 20.1667H13.75C18.3333 20.1667 20.1667 18.3334 20.1667 13.75V8.25004C20.1667 3.66671 18.3333 1.83337 13.75 1.83337H8.25C3.66667 1.83337 1.83333 3.66671 1.83333 8.25004V13.75C1.83333 18.3334 3.66667 20.1667 8.25 20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.2725 16.9583V13.3833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.2725 6.82913V5.04163" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.2725 11.5959C15.5888 11.5959 16.6558 10.5288 16.6558 9.21256C16.6558 7.89628 15.5888 6.82922 14.2725 6.82922C12.9562 6.82922 11.8892 7.89628 11.8892 9.21256C11.8892 10.5288 12.9562 11.5959 14.2725 11.5959Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.7275 16.9583V15.1708" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.7275 8.61663V5.04163" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.7275 15.1708C9.04378 15.1708 10.1108 14.1038 10.1108 12.7875C10.1108 11.4712 9.04378 10.4042 7.7275 10.4042C6.41122 10.4042 5.34417 11.4712 5.34417 12.7875C5.34417 14.1038 6.41122 15.1708 7.7275 15.1708Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('system_settings') }}</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="settings-menu">
            <ul class="nav flex-column sub-menu">
                @can('app-settings')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('system-settings.app') }}">{{ __('app_settings') }}</a>
                </li>
                @endcan
                @can('school-setting-manage')
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('school-settings.index') }}">{{ __('general_settings') }}</a>
                </li>

                {{-- session-year.index --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('session-year.index') }}">{{ __('session_year') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('leave-master.index') }}"
                        data-name="{{ Auth::user()->getRoleNames()[0] }}"
                        data-access="@hasFeatureAccess('Staff Leave Management')">{{ __('leave') }} {{ __('settings') }}</a>
                </li>
                @endcan

                @can('system-setting-manage')
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('system-settings.index') }}">{{ __('general_settings') }}</a>
                </li>
                @endcan

                @can('subscription-settings')
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('system-settings.subscription-settings') }}">{{ __('subscription_settings') }}</a>
                </li>
                @endcan

                {{-- @can('front-site-setting')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('system-settings.front-site-settings') }}">{{ __('front_site_settings') }}</a>
    </li>
    @endcan --}}
    @canany(['guidance-create', 'guidance-list', 'guidance-edit', 'guidance-delete'])
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guidances.index') }}">{{ __('guidance') }}</a>
    </li>
    @endcanany

    @can('language-list')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('language') }}">
            {{ __('language_settings') }}</a>
    </li>
    @endcan
    @can('fcm-setting-manage')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('system-settings.fcm') }}">
            {{ __('notification_settings') }}</a>
    </li>
    @endcan

    {{-- @can('fees-config')
                                        <li class="nav-item">
                                            <a href="{{ route('fees.config.index') }}" class="nav-link" data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Fees Management')">
    {{ __('Fees Settings') }}</a>
    </li>
    @endcan --}}

    @can('school-setting-manage')
    <li class="nav-item">
        <a href="{{ route('school-settings.online-exam.index') }}" class="nav-link text-wrap"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Exam Management')">
            {{ __('online_exam_terms_condition') }}
        </a>
    </li>
    @endcan

    @can('email-setting-create')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.email.index') }}">{{ __('email_configuration') }}</a>
    </li>
    @endcan

    {{-- Super admin panel --}}
    @can('email-setting-create')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.email.template') }}">{{ __('email_template') }}</a>
    </li>
    @endcan

    {{-- School admin panel --}}
    @can('email-template')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('school-settings.email.template') }}">{{ __('email_template') }}</a>
    </li>
    @endcan

    {{-- Payment Configuration Menu For Superadmin --}}
    @hasanyrole(['Super Admin', 'School Admin'])
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.payment.index') }}">{{ __('Payment Settings') }}</a>
    </li>
    @endrole

    @can('school-setting-manage')
    <li class="nav-item">
        <a class="nav-link" data-access="@hasFeatureAccess('Website Management')"
            href="{{ route('school-settings.third-party') }}">{{ __('Third-Party APIs') }}</a>
    </li>
    @endcan

    @can('system-setting-manage')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.third-party') }}">{{ __('Third-Party APIs') }}</a>
    </li>
    @endcan

    {{-- @can('database-backup')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('database-backup') }}">{{ __('database_backup') }}</a>
    </li>
    @endcan --}}



    @can('contact-us')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('system-settings.contact-us') }}">
            {{ __('contact_us') }}</a>
    </li>
    @endcan
    @can('about-us')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('system-settings.about-us') }}"> {{ __('about_us') }}
        </a>
    </li>
    @endcan

    @hasrole('School Admin')

    {{-- Privacy Policy --}}
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('school-settings.privacy-policy') }}">{{ __('privacy_policy') }}</a>
    </li>

    {{-- Terms & Conditions --}}
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('school-settings.terms-condition') }}">{{ __('terms_condition') }}</a>
    </li>

    {{-- Refund Cancellation --}}

    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('school-settings.refund-cancellation') }}">{{ __('refund_cancellation') }}</a>
    </li>

    @endrole

    @can('privacy-policy')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.privacy-policy') }}">{{ __('privacy_policy') }}</a>
    </li>
    @endcan

    @can('terms-condition')
    <li class="nav-item">
        <a class="nav-link"
            href="{{ route('system-settings.terms-condition') }}">{{ __('terms_condition') }}</a>
    </li>
    @endcan

    </ul>
    </div>
    </li>
    @endcanany

    @if (Auth::user()->hasRole(['Super Admin']))
    <li class="nav-item">
        <a class="nav-link" href="https://wrteam-in.github.io/eSchool-SaaS-Doc/" target="_blank">
            <img src="{{ asset('assets/icons/admin-icons/documentation.svg') }}" alt="documentation" class="w-5 h-5 svg-theme-stroke mr-2">
            <span class="menu-title">{{ __('Documentation') }}</span>
        </a>
    </li>
    @endif
    @if (Auth::user()->hasRole(['Super Admin', 'School Admin']) || Auth::user()->hasPermissionTo('database-backup'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('database-backup.index') }}">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.71 9.1667H4.29917C2.9425 9.1667 1.84249 8.05754 1.84249 6.71004V4.2992C1.84249 2.94254 2.95167 1.84253 4.29917 1.84253H17.71C19.0667 1.84253 20.1667 2.9517 20.1667 4.2992V6.71004C20.1667 8.05754 19.0575 9.1667 17.71 9.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.71 20.1667H4.29917C2.9425 20.1667 1.84249 19.0575 1.84249 17.71V15.2992C1.84249 13.9425 2.95167 12.8425 4.29917 12.8425H17.71C19.0667 12.8425 20.1667 13.9517 20.1667 15.2992V17.71C20.1667 19.0575 19.0575 20.1667 17.71 20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.5 4.58337V6.41671" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.16667 4.58337V6.41671" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.5 15.5834V17.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.16667 15.5834V17.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.8333 5.5H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.8333 16.5H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('database_backup') }}</span>
        </a>
    </li>
    @endif
    @if (Auth::user()->hasRole('Super Admin'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('system-update.index') }}">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 13.75C12.5188 13.75 13.75 12.5188 13.75 11C13.75 9.48122 12.5188 8.25 11 8.25C9.48122 8.25 8.25 9.48122 8.25 11C8.25 12.5188 9.48122 13.75 11 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 11.8066V10.1933C1.83333 9.23998 2.6125 8.45165 3.575 8.45165C5.23417 8.45165 5.9125 7.27832 5.07833 5.83915C4.60167 5.01415 4.88583 3.94165 5.72 3.46498L7.30583 2.55748C8.03 2.12665 8.965 2.38332 9.39583 3.10748L9.49667 3.28165C10.3217 4.72082 11.6783 4.72082 12.5125 3.28165L12.6133 3.10748C13.0442 2.38332 13.9792 2.12665 14.7033 2.55748L16.2892 3.46498C17.1233 3.94165 17.4075 5.01415 16.9308 5.83915C16.0967 7.27832 16.775 8.45165 18.4342 8.45165C19.3875 8.45165 20.1758 9.23082 20.1758 10.1933V11.8066C20.1758 12.76 19.3967 13.5483 18.4342 13.5483C16.775 13.5483 16.0967 14.7216 16.9308 16.1608C17.4075 16.995 17.1233 18.0583 16.2892 18.535L14.7033 19.4425C13.9792 19.8733 13.0442 19.6166 12.6133 18.8925L12.5125 18.7183C11.6875 17.2791 10.3308 17.2791 9.49667 18.7183L9.39583 18.8925C8.965 19.6166 8.03 19.8733 7.30583 19.4425L5.72 18.535C4.88583 18.0583 4.60167 16.9858 5.07833 16.1608C5.9125 14.7216 5.23417 13.5483 3.575 13.5483C2.6125 13.5483 1.83333 12.76 1.83333 11.8066Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('system_update') }}</span>
        </a>
    </li>
    @endif
    @if (Auth::user()->hasRole(['School Admin']))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('chat-history') }}">
            <i class="fa fa-comments-o menu-icon"></i>
            <span class="menu-title">{{ __('Chat History') }}</span>
        </a>
    </li>
    @endif
    </ul>
</nav>
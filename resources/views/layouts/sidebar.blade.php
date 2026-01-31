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
                        
                        {{-- @can('syllabus-list') --}}
                            <li class="nav-item"><a href="{{ route('syllabus.index') }}" class="nav-link"> {{ __('Syllabus') }}
                                </a></li>
                        {{-- @endcan --}}
                                
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
                <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4166 7.33337C18.9354 7.33337 20.1666 6.10216 20.1666 4.58337C20.1666 3.06459 18.9354 1.83337 17.4166 1.83337C15.8978 1.83337 14.6666 3.06459 14.6666 4.58337C14.6666 6.10216 15.8978 7.33337 17.4166 7.33337Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6.41663 11.9166H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6.41663 15.5834H14.6666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.8334 1.83337H8.25004C3.66671 1.83337 1.83337 3.66671 1.83337 8.25004V13.75C1.83337 18.3334 3.66671 20.1667 8.25004 20.1667H13.75C18.3334 20.1667 20.1667 18.3334 20.1667 13.75V9.16671" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="menu-title"> {{ __('custom_fields') }} </span>
            </a>
        </li>
        @endcan
        @endrole


        {{-- Class Section For Teacher --}}
        @role('Teacher')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('class-section.index') }}">
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
                <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 11C13.5313 11 15.5833 8.94801 15.5833 6.41671C15.5833 3.8854 13.5313 1.83337 11 1.83337C8.46865 1.83337 6.41663 3.8854 6.41663 6.41671C6.41663 8.94801 8.46865 11 11 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17.6092 14.4284L14.3641 17.6734C14.2358 17.8017 14.1167 18.04 14.0892 18.2142L13.915 19.4517C13.8508 19.9008 14.1625 20.2125 14.6117 20.1483L15.8491 19.9742C16.0233 19.9467 16.2708 19.8275 16.39 19.6992L19.635 16.4542C20.1941 15.895 20.46 15.2442 19.635 14.4192C18.8191 13.6033 18.1683 13.8692 17.6092 14.4284Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17.1417 14.8959C17.4167 15.8859 18.1867 16.6558 19.1767 16.9308" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.12585 20.1667C3.12585 16.6192 6.65505 13.75 11 13.75C11.9534 13.75 12.87 13.8875 13.7225 14.1442" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

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
            <svg width="22" height="22" class="mr-2" Box="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.3233 7.86507V14.135C19.3233 15.1617 18.7733 16.1151 17.8841 16.6376L12.4391 19.7817C11.55 20.2951 10.45 20.2951 9.55165 19.7817L4.10665 16.6376C3.21748 16.1242 2.66748 15.1709 2.66748 14.135V7.86507C2.66748 6.83841 3.21748 5.88503 4.10665 5.36253L9.55165 2.21837C10.4408 1.70504 11.5408 1.70504 12.4391 2.21837L17.8841 5.36253C18.7733 5.88503 19.3233 6.82924 19.3233 7.86507Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.0001 10.0833C12.1797 10.0833 13.1359 9.12703 13.1359 7.94744C13.1359 6.76785 12.1797 5.81165 11.0001 5.81165C9.8205 5.81165 8.86426 6.76785 8.86426 7.94744C8.86426 9.12703 9.8205 10.0833 11.0001 10.0833Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6667 15.2716C14.6667 13.6216 13.0259 12.2833 11 12.2833C8.97421 12.2833 7.33337 13.6216 7.33337 15.2716" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.25004 20.1667H13.75C18.3334 20.1667 20.1667 18.3334 20.1667 13.75V8.25004C20.1667 3.66671 18.3334 1.83337 13.75 1.83337H8.25004C3.66671 1.83337 1.83337 3.66671 1.83337 8.25004V13.75C1.83337 18.3334 3.66671 20.1667 8.25004 20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.8483 13.9975V6.9483C16.8483 6.24247 16.28 5.72914 15.5833 5.78414H15.5466C14.3183 5.88498 12.4575 6.51749 11.4125 7.16832L11.3116 7.23249C11.1466 7.33333 10.8624 7.33333 10.6883 7.23249L10.5416 7.14082C9.50578 6.48999 7.64495 5.8758 6.41662 5.77497C5.71995 5.71997 5.15161 6.24249 5.15161 6.93915V13.9975C5.15161 14.5566 5.60993 15.0883 6.1691 15.1525L6.33409 15.18C7.59909 15.345 9.56081 15.9958 10.6791 16.61L10.7066 16.6192C10.8624 16.7108 11.1191 16.7108 11.2658 16.6192C12.3841 15.9958 14.3549 15.3541 15.6291 15.18L15.8216 15.1525C16.39 15.0883 16.8483 14.5658 16.8483 13.9975Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 7.42493V16.1883" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33337 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6666 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.6833 19.6167C18.3034 19.6167 19.6167 18.3034 19.6167 16.6833C19.6167 15.0633 18.3034 13.75 16.6833 13.75C15.0633 13.75 13.75 15.0633 13.75 16.6833C13.75 18.3034 15.0633 19.6167 16.6833 19.6167Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 20.1667L19.25 19.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.20837 8.33252H18.7917" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.2558 20.1667H7.33333C4.125 20.1667 2.75 18.3334 2.75 15.5834V7.79171C2.75 5.04171 4.125 3.20837 7.33333 3.20837H14.6667C17.875 3.20837 19.25 5.04171 19.25 7.79171V11.9167" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.9959 12.5583H11.0041" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 12.5583H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 15.3083H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="menu-title">{{ __('timetable') }}</span>
        </a>
    </li>
    @else
    @canany(['timetable-create', 'timetable-list'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#timetable-menu" aria-expanded="false"
            aria-controls="timetable-menu" data-access="@hasFeatureAccess('Timetable Management')">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33337 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6666 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.6833 19.6167C18.3034 19.6167 19.6167 18.3034 19.6167 16.6833C19.6167 15.0633 18.3034 13.75 16.6833 13.75C15.0633 13.75 13.75 15.0633 13.75 16.6833C13.75 18.3034 15.0633 19.6167 16.6833 19.6167Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 20.1667L19.25 19.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.20837 8.33252H18.7917" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.2558 20.1667H7.33333C4.125 20.1667 2.75 18.3334 2.75 15.5834V7.79171C2.75 5.04171 4.125 3.20837 7.33333 3.20837H14.6667C17.875 3.20837 19.25 5.04171 19.25 7.79171V11.9167" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.9959 12.5583H11.0041" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 12.5583H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 15.3083H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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
                <path d="M7.33337 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6666 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6667 3.20837C17.7192 3.37337 19.25 4.53754 19.25 8.84587V14.5109C19.25 18.2875 18.3333 20.1759 13.75 20.1759H8.25C3.66667 20.1759 2.75 18.2875 2.75 14.5109V8.84587C2.75 4.53754 4.28083 3.38254 7.33333 3.20837H14.6667Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M19.0208 16.1333H2.97913" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 7.5625C9.87254 7.5625 8.91921 8.17667 8.91921 9.36833C8.91921 9.93667 9.18504 10.3675 9.58837 10.6425C9.02921 10.9725 8.70837 11.5042 8.70837 12.1275C8.70837 13.2642 9.57921 13.97 11 13.97C12.4117 13.97 13.2917 13.2642 13.2917 12.1275C13.2917 11.5042 12.9709 10.9633 12.4025 10.6425C12.815 10.3583 13.0717 9.93667 13.0717 9.36833C13.0717 8.17667 12.1275 7.5625 11 7.5625ZM11 10.1658C10.5234 10.1658 10.175 9.88167 10.175 9.4325C10.175 8.97417 10.5234 8.70833 11 8.70833C11.4767 8.70833 11.825 8.97417 11.825 9.4325C11.825 9.88167 11.4767 10.1658 11 10.1658ZM11 12.8333C10.395 12.8333 9.95504 12.5308 9.95504 11.9808C9.95504 11.4308 10.395 11.1375 11 11.1375C11.605 11.1375 12.045 11.44 12.045 11.9808C12.045 12.5308 11.605 12.8333 11 12.8333Z" fill="currentColor" />
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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.20831 16.4999V6.41659C3.20831 2.74992 4.12498 1.83325 7.79165 1.83325H14.2083C17.875 1.83325 18.7916 2.74992 18.7916 6.41659V15.5833C18.7916 15.7116 18.7916 15.8399 18.7825 15.9683" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.82081 13.75H18.7916V16.9583C18.7916 18.7275 17.3525 20.1667 15.5833 20.1667H6.41665C4.64748 20.1667 3.20831 18.7275 3.20831 16.9583V16.3625C3.20831 14.9233 4.38165 13.75 5.82081 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33331 6.41675H14.6666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33331 9.625H11.9166" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.90503 10.1016V14.6574C3.90503 16.3258 3.90503 16.3258 5.4817 17.3891L9.81753 19.8916C10.4684 20.2674 11.5317 20.2674 12.1825 19.8916L16.5184 17.3891C18.095 16.3258 18.095 16.3258 18.095 14.6574V10.1016C18.095 8.43328 18.095 8.43328 16.5184 7.36995L12.1825 4.86745C11.5317 4.49161 10.4684 4.49161 9.81753 4.86745L5.4817 7.36995C3.90503 8.43328 3.90503 8.43328 3.90503 10.1016Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.0417 6.99421V4.58337C16.0417 2.75004 15.125 1.83337 13.2917 1.83337H8.70837C6.87504 1.83337 5.95837 2.75004 5.95837 4.58337V6.93004" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.5775 10.0741L12.1 10.89C12.1825 11.0183 12.3658 11.1466 12.5033 11.1833L13.4383 11.4216C14.0158 11.5683 14.1717 12.0633 13.7958 12.5216L13.1817 13.2641C13.09 13.3833 13.0167 13.5941 13.0258 13.7408L13.0808 14.7033C13.1175 15.2991 12.6958 15.6016 12.1458 15.3816L11.2475 15.0241C11.11 14.9691 10.8808 14.9691 10.7433 15.0241L9.845 15.3816C9.295 15.6016 8.87334 15.29 8.91 14.7033L8.965 13.7408C8.97417 13.5941 8.90084 13.3741 8.80917 13.2641L8.195 12.5216C7.81917 12.0633 7.975 11.5683 8.5525 11.4216L9.4875 11.1833C9.63417 11.1466 9.8175 11.0091 9.89084 10.89L10.4133 10.0741C10.7433 9.57913 11.2567 9.57913 11.5775 10.0741Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.9258 2.67671L17.3341 5.07838C18.8925 5.76588 18.8925 6.90254 17.3341 7.59004L11.9258 9.99171C11.3116 10.2667 10.3033 10.2667 9.68914 9.99171L4.28081 7.59004C2.72248 6.90254 2.72248 5.76588 4.28081 5.07838L9.68914 2.67671C10.3033 2.40171 11.3116 2.40171 11.9258 2.67671Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 10.0834C2.75 10.8534 3.3275 11.7425 4.03333 12.0542L10.2575 14.8225C10.7342 15.0334 11.275 15.0334 11.7425 14.8225L17.9667 12.0542C18.6725 11.7425 19.25 10.8534 19.25 10.0834" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 14.6666C2.75 15.5191 3.25417 16.2891 4.03333 16.6375L10.2575 19.4058C10.7342 19.6166 11.275 19.6166 11.7425 19.4058L17.9667 16.6375C18.7458 16.2891 19.25 15.5191 19.25 14.6666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('sliders') }}</span>
        </a>
    </li>
    @endcan

    @canany(['notification-create', 'notification-list', 'notification-delete'])
    <li class="nav-item">
        <a href="{{ route('notifications.index') }}" class="nav-link"
            data-name="{{ Auth::user()->getRoleNames()[0] }}" data-access="@hasFeatureAccess('Announcement Management')">
            <svg width="22" height="22" viewBox="0 0 22 22" class="mr-2" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 5.90332V8.95582" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                <path d="M11.0184 1.83337C7.64503 1.83337 4.91336 4.56504 4.91336 7.93837V9.86337C4.91336 10.4867 4.6567 11.4217 4.33586 11.9534L3.1717 13.8967C2.4567 15.0975 2.95169 16.4359 4.27169 16.8759C8.65336 18.3334 13.3925 18.3334 17.7742 16.8759C19.0117 16.4634 19.5434 15.015 18.8742 13.8967L17.71 11.9534C17.3892 11.4217 17.1325 10.4775 17.1325 9.86337V7.93837C17.1234 4.58337 14.3734 1.83337 11.0184 1.83337Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                <path d="M14.0525 17.2517C14.0525 18.9292 12.6775 20.3042 11 20.3042C10.1658 20.3042 9.39584 19.9559 8.84584 19.4059C8.29584 18.8559 7.94751 18.0859 7.94751 17.2517" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" />
            </svg>

            <span class="menu-title">{{ __('notification') }}</span>
        </a>
    </li>
    @endcanany

    {{-- Attendance --}}
    @canany(['class-teacher', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-delete'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#attendance-menu" data-access="@hasFeatureAccess('Attendance Management')"
            aria-expanded="false" aria-controls="attendance-menu">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33337 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6666 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.20837 8.33252H18.7917" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.6092 14.4558L14.3642 17.7008C14.2358 17.8291 14.1167 18.0675 14.0892 18.2416L13.915 19.4791C13.8508 19.9283 14.1625 20.24 14.6117 20.1758L15.8492 20.0017C16.0233 19.9742 16.2708 19.855 16.39 19.7266L19.635 16.4817C20.1942 15.9225 20.46 15.2717 19.635 14.4467C18.8192 13.6308 18.1683 13.8966 17.6092 14.4558Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.1417 14.9233C17.4167 15.9133 18.1867 16.6833 19.1767 16.9583" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 20.1667H7.33333C4.125 20.1667 2.75 18.3334 2.75 15.5834V7.79171C2.75 5.04171 4.125 3.20837 7.33333 3.20837H14.6667C17.875 3.20837 19.25 5.04171 19.25 7.79171V11" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.9959 12.5583H11.0041" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 12.5583H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 15.3083H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33337 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.6666 1.83337V4.58337" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3.20837 8.33252H18.7917" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.6092 14.4558L14.3642 17.7008C14.2358 17.8291 14.1167 18.0675 14.0892 18.2416L13.915 19.4791C13.8508 19.9283 14.1625 20.24 14.6117 20.1758L15.8492 20.0017C16.0233 19.9742 16.2708 19.855 16.39 19.7266L19.635 16.4817C20.1942 15.9225 20.46 15.2717 19.635 14.4467C18.8192 13.6308 18.1683 13.8966 17.6092 14.4558Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.1417 14.9233C17.4167 15.9133 18.1867 16.6833 19.1767 16.9583" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 20.1667H7.33333C4.125 20.1667 2.75 18.3334 2.75 15.5834V7.79171C2.75 5.04171 4.125 3.20837 7.33333 3.20837H14.6667C17.875 3.20837 19.25 5.04171 19.25 7.79171V11" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.9959 12.5583H11.0041" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 12.5583H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.60308 15.3083H7.61131" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 17.4166C14.0342 17.4166 16.5 14.9508 16.5 11.9166V7.33325C16.5 4.29909 14.0342 1.83325 11 1.83325C7.96583 1.83325 5.5 4.29909 5.5 7.33325V11.9166C5.5 14.9508 7.96583 17.4166 11 17.4166Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 10.0833V11.9166C2.75 16.4724 6.44417 20.1666 11 20.1666C15.5558 20.1666 19.25 16.4724 19.25 11.9166V10.0833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.35083 6.85679C9.9825 6.26095 11.7608 6.26095 13.3925 6.85679" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.19415 9.60676C10.2942 9.30426 11.4583 9.30426 12.5583 9.60676" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('announcement') }}</span>
        </a>
    </li>
    @endcan

    {{-- exam --}}
    @canany(['exam-create', 'exam-upload-marks', 'grade-create', 'exam-result', 'view-exam-marks'])
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#exam-menu" aria-expanded="false"
            aria-controls="exam-menu" data-access="@hasFeatureAccess('Exam Management')">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.25 6.41659V15.5833C19.25 18.3333 17.875 20.1666 14.6667 20.1666H7.33333C4.125 20.1666 2.75 18.3333 2.75 15.5833V6.41659C2.75 3.66659 4.125 1.83325 7.33333 1.83325H14.6667C17.875 1.83325 19.25 3.66659 19.25 6.41659Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.2917 4.125V5.95833C13.2917 6.96667 14.1167 7.79167 15.125 7.79167H16.9583" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33334 11.9167H11" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.33334 15.5833H14.6667" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.8333 1.83325H8.25001C3.66668 1.83325 1.83334 3.66659 1.83334 8.24992V13.7499C1.83334 18.3333 3.66668 20.1666 8.25001 20.1666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 9.1665V11.9165" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 9.16658H16.5C13.75 9.16658 12.8333 8.24992 12.8333 5.49992V1.83325L20.1667 9.16658Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.6133 16.7383C10.4592 16.8941 10.4592 20.0108 12.6133 20.1666H17.71C18.3242 20.1666 18.9292 19.9374 19.3783 19.5249C20.8908 18.2049 20.0842 15.5649 18.095 15.3174C17.38 11.0183 11.165 12.6499 12.6317 16.7474" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.70834 12.6041C8.70834 13.4933 9.39586 14.2083 10.2392 14.2083H11.9625C12.6958 14.2083 13.2917 13.585 13.2917 12.8058C13.2917 11.9716 12.925 11.6691 12.3842 11.4766L9.62501 10.5141C9.08418 10.3216 8.71752 10.0283 8.71752 9.18497C8.71752 8.41497 9.31334 7.78247 10.0467 7.78247H11.77C12.6133 7.78247 13.3009 8.49747 13.3009 9.38664" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 6.875V15.125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 10.9999C20.1667 16.0599 16.06 20.1666 11 20.1666C5.94001 20.1666 1.83334 16.0599 1.83334 10.9999C1.83334 5.93992 5.94001 1.83325 11 1.83325" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M15.5833 2.75V6.41667H19.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 1.83325L15.5833 6.41659" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.4917 20.1666H6.50833C4.95 20.1666 3.66666 18.8924 3.66666 17.3249V4.67492C3.66666 3.11659 4.94083 1.83325 6.50833 1.83325H15.4917C17.05 1.83325 18.3333 3.10742 18.3333 4.67492V17.3249C18.3333 18.8924 17.0592 20.1666 15.4917 20.1666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.9583 11.9166H5.04166C4.28082 11.9166 3.66666 11.3024 3.66666 10.5416V8.70825C3.66666 7.94742 4.28082 7.33325 5.04166 7.33325H16.9583C17.7192 7.33325 18.3333 7.94742 18.3333 8.70825V10.5416C18.3333 11.3024 17.7192 11.9166 16.9583 11.9166Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.78664 16.2249H7.79488" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.2033 16.2249H14.2115" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.70834 4.58325H13.2917" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.03833 17.4166L10.5783 16.115C10.8075 15.9225 11.1925 15.9225 11.4308 16.115L12.9617 17.4166C13.3192 17.6 13.7592 17.4166 13.8875 17.0316L14.1808 16.1516C14.2542 15.9408 14.1808 15.6291 14.025 15.4733L12.5217 13.9608C12.4117 13.8508 12.3292 13.64 12.3292 13.4933V11.7975C12.3292 11.4125 12.6133 11.2291 12.9708 11.3758L16.0417 12.6958C16.5458 12.9158 16.9675 12.6408 16.9675 12.0908V11.2383C16.9675 10.7983 16.6375 10.285 16.225 10.1108L12.6133 8.55247C12.4575 8.4883 12.3383 8.2958 12.3383 8.1308V6.2333C12.3383 5.60997 11.88 4.87664 11.33 4.59247C11.1283 4.49164 10.8992 4.49164 10.6975 4.59247C10.1383 4.86747 9.68916 5.60997 9.68916 6.2333V8.1308C9.68916 8.2958 9.56083 8.4883 9.41416 8.55247L5.8025 10.1108C5.39916 10.285 5.06 10.7983 5.06 11.2383V12.0908C5.06 12.6408 5.4725 12.9158 5.98583 12.6958L9.05666 11.3758C9.405 11.22 9.69833 11.4125 9.69833 11.7975V13.4933C9.69833 13.6491 9.60666 13.86 9.50583 13.9608L7.975 15.4641C7.81916 15.62 7.74583 15.9316 7.81916 16.1425L8.1125 17.0225C8.24083 17.4166 8.67166 17.6 9.03833 17.4166Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.25001 20.1666H13.75C18.3333 20.1666 20.1667 18.3333 20.1667 13.7499V8.24992C20.1667 3.66659 18.3333 1.83325 13.75 1.83325H8.25001C3.66668 1.83325 1.83334 3.66659 1.83334 8.24992V13.7499C1.83334 18.3333 3.66668 20.1666 8.25001 20.1666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.9258 2.67671L17.3342 5.07838C18.8925 5.76588 18.8925 6.90254 17.3342 7.59004L11.9258 9.99171C11.3117 10.2667 10.3033 10.2667 9.68917 9.99171L4.28084 7.59004C2.72251 6.90254 2.72251 5.76588 4.28084 5.07838L9.68917 2.67671C10.3033 2.40171 11.3117 2.40171 11.9258 2.67671Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 10.0833C2.75 10.8533 3.3275 11.7424 4.03333 12.0541L10.2575 14.8224C10.7342 15.0333 11.275 15.0333 11.7425 14.8224L17.9667 12.0541C18.6725 11.7424 19.25 10.8533 19.25 10.0833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.75 14.6667C2.75 15.5192 3.25417 16.2892 4.03333 16.6376L10.2575 19.4059C10.7342 19.6167 11.275 19.6167 11.7425 19.4059L17.9667 16.6376C18.7458 16.2892 19.25 15.5192 19.25 14.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.1667 5.49992V7.71825C20.1667 9.16658 19.25 10.0833 17.8017 10.0833H14.6667V3.67575C14.6667 2.65825 15.5009 1.83325 16.5184 1.83325C17.5175 1.84242 18.4342 2.24575 19.0942 2.90575C19.7542 3.57492 20.1667 4.49159 20.1667 5.49992Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83331 6.41658V19.2499C1.83331 20.0107 2.69498 20.4416 3.29998 19.9833L4.86748 18.8099C5.23415 18.5349 5.74748 18.5716 6.07748 18.9016L7.59915 20.4324C7.95665 20.7899 8.54331 20.7899 8.90081 20.4324L10.4408 18.8924C10.7616 18.5716 11.275 18.5349 11.6325 18.8099L13.2 19.9833C13.805 20.4324 14.6666 20.0016 14.6666 19.2499V3.66659C14.6666 2.65825 15.4916 1.83325 16.5 1.83325H6.41665H5.49998C2.74998 1.83325 1.83331 3.47409 1.83331 5.49992V6.41658Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.25 11.9258H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.25 8.25928H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.49597 11.9167H5.50421" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.49597 8.25H5.50421" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.70834 12.6041C8.70834 13.4933 9.39586 14.2083 10.2392 14.2083H11.9625C12.6958 14.2083 13.2917 13.585 13.2917 12.8058C13.2917 11.9716 12.925 11.6691 12.3842 11.4766L9.62501 10.5141C9.08418 10.3216 8.71752 10.0283 8.71752 9.18497C8.71752 8.41497 9.31334 7.78247 10.0467 7.78247H11.77C12.6133 7.78247 13.3009 8.49747 13.3009 9.38664" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 6.875V15.125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 10.9999C20.1667 16.0599 16.06 20.1666 11 20.1666C5.94001 20.1666 1.83334 16.0599 1.83334 10.9999C1.83334 5.93992 5.94001 1.83325 11 1.83325" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 5.49992V1.83325H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M15.5833 6.41659L20.1667 1.83325" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.82977 7.79175H10.5381" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.49646 15.125H7.32979" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.62146 15.125H13.2881" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1631 11.0274V14.7674C20.1631 17.9849 19.3473 18.7916 16.0931 18.7916H5.89977C2.64561 18.7916 1.82977 17.9849 1.82977 14.7674V7.23242C1.82977 4.01492 2.64561 3.20825 5.89977 3.20825H13.2881" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.4865 3.78595L14.0856 7.18678C13.9573 7.31512 13.829 7.57178 13.8015 7.75512L13.6181 9.05678C13.5539 9.52428 13.8839 9.85428 14.3514 9.79012L15.6531 9.60678C15.8365 9.57928 16.0931 9.45095 16.2215 9.32262L19.6223 5.92178C20.2089 5.33512 20.4839 4.65678 19.6223 3.79512C18.7514 2.92428 18.0731 3.19928 17.4865 3.78595Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.0006 4.27173C17.2939 5.30756 18.1006 6.11423 19.1273 6.3984" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.25001 20.1666H13.75C18.3333 20.1666 20.1667 18.3333 20.1667 13.7499V8.24992C20.1667 3.66659 18.3333 1.83325 13.75 1.83325H8.25001C3.66668 1.83325 1.83334 3.66659 1.83334 8.24992V13.7499C1.83334 18.3333 3.66668 20.1666 8.25001 20.1666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.24999 9.16667C9.26251 9.16667 10.0833 8.34586 10.0833 7.33333C10.0833 6.32081 9.26251 5.5 8.24999 5.5C7.23747 5.5 6.41666 6.32081 6.41666 7.33333C6.41666 8.34586 7.23747 9.16667 8.24999 9.16667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.44751 17.3709L6.96668 14.3368C7.69084 13.8509 8.73584 13.9059 9.38668 14.4651L9.68918 14.7309C10.4042 15.3451 11.5592 15.3451 12.2742 14.7309L16.0875 11.4584C16.8025 10.8443 17.9575 10.8443 18.6725 11.4584L20.1667 12.7418" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.855 9.56997L18.9567 13.4016C18.1867 16.7108 16.665 18.0491 13.805 17.7741C13.3467 17.7375 12.8517 17.655 12.32 17.5266L10.78 17.16C6.95749 16.2525 5.77499 14.3641 6.67333 10.5325L7.57166 6.69163C7.75499 5.91247 7.97499 5.23413 8.24999 4.67497C9.32249 2.45663 11.1467 1.8608 14.2083 2.58497L15.7392 2.94247C19.58 3.8408 20.7533 5.7383 19.855 9.56997Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.805 17.7742C13.2367 18.1592 12.5217 18.48 11.6508 18.7642L10.2025 19.2409C6.56334 20.4142 4.64751 19.4334 3.46501 15.7942L2.29167 12.1734C1.11834 8.53422 2.09001 6.60922 5.72917 5.43588L7.17751 4.95922C7.55334 4.84005 7.91084 4.73922 8.25001 4.67505C7.97501 5.23422 7.75501 5.91255 7.57167 6.69172L6.67334 10.5325C5.77501 14.3642 6.95751 16.2525 10.78 17.16L12.32 17.5267C12.8517 17.655 13.3467 17.7375 13.805 17.7742Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.5867 7.81909L16.0325 8.94659" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.6883 11.3667L13.3467 12.045" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="24" height="22" class="mr-2" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6.56326C17.94 6.55409 17.87 6.55409 17.81 6.56326C16.43 6.51742 15.33 5.48159 15.33 4.19825C15.33 2.88742 16.48 1.83325 17.91 1.83325C19.34 1.83325 20.49 2.89659 20.49 4.19825C20.48 5.48159 19.38 6.51742 18 6.56326Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.97 13.2365C18.34 13.4473 19.85 13.2273 20.91 12.5765C22.32 11.7148 22.32 10.3032 20.91 9.44149C19.84 8.79066 18.31 8.57065 16.94 8.79065" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.97001 6.56326C6.03001 6.55409 6.10001 6.55409 6.16001 6.56326C7.54001 6.51742 8.64001 5.48159 8.64001 4.19825C8.64001 2.88742 7.49001 1.83325 6.06001 1.83325C4.63001 1.83325 3.48001 2.89659 3.48001 4.19825C3.49001 5.48159 4.59001 6.51742 5.97001 6.56326Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7 13.2365C5.63 13.4473 4.12 13.2273 3.06 12.5765C1.65 11.7148 1.65 10.3032 3.06 9.44149C4.13 8.79066 5.66 8.57065 7.03 8.79065" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 13.4109C11.94 13.4017 11.87 13.4017 11.81 13.4109C10.43 13.3651 9.33002 12.3292 9.33002 11.0459C9.33002 9.73508 10.48 8.68091 11.91 8.68091C13.34 8.68091 14.49 9.74424 14.49 11.0459C14.48 12.3292 13.38 13.3742 12 13.4109Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.09 16.2983C7.68 17.16 7.68 18.5717 9.09 19.4333C10.69 20.4142 13.31 20.4142 14.91 19.4333C16.32 18.5717 16.32 17.16 14.91 16.2983C13.32 15.3267 10.69 15.3267 9.09 16.2983Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.03833 17.4166L10.5783 16.115C10.8075 15.9225 11.1925 15.9225 11.4308 16.115L12.9617 17.4166C13.3192 17.6 13.7592 17.4166 13.8875 17.0316L14.1808 16.1516C14.2542 15.9408 14.1808 15.6291 14.025 15.4733L12.5217 13.9608C12.4117 13.8508 12.3292 13.64 12.3292 13.4933V11.7975C12.3292 11.4125 12.6133 11.2291 12.9708 11.3758L16.0417 12.6958C16.5458 12.9158 16.9675 12.6408 16.9675 12.0908V11.2383C16.9675 10.7983 16.6375 10.285 16.225 10.1108L12.6133 8.55247C12.4575 8.4883 12.3383 8.2958 12.3383 8.1308V6.2333C12.3383 5.60997 11.88 4.87664 11.33 4.59247C11.1283 4.49164 10.8992 4.49164 10.6975 4.59247C10.1383 4.86747 9.68916 5.60997 9.68916 6.2333V8.1308C9.68916 8.2958 9.56083 8.4883 9.41416 8.55247L5.8025 10.1108C5.39916 10.285 5.06 10.7983 5.06 11.2383V12.0908C5.06 12.6408 5.4725 12.9158 5.98583 12.6958L9.05666 11.3758C9.405 11.22 9.69833 11.4125 9.69833 11.7975V13.4933C9.69833 13.6491 9.60666 13.86 9.50583 13.9608L7.975 15.4641C7.81916 15.62 7.74583 15.9316 7.81916 16.1425L8.1125 17.0225C8.24083 17.4166 8.67166 17.6 9.03833 17.4166Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.25001 20.1666H13.75C18.3333 20.1666 20.1667 18.3333 20.1667 13.7499V8.24992C20.1667 3.66659 18.3333 1.83325 13.75 1.83325H8.25001C3.66668 1.83325 1.83334 3.66659 1.83334 8.24992V13.7499C1.83334 18.3333 3.66668 20.1666 8.25001 20.1666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            </svg>
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
                <svg width=" 22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8.77249 11.4216L5.97665 14.2175" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M6.00418 11.4492L8.80001 14.2451" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12.4025 12.8333H12.4125" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M16.0142 12.8333H16.0242" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M14.2083 14.6499V14.6299" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M14.2083 11.0383V11.0183" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M8.25001 20.1667H13.75C18.3333 20.1667 20.1667 18.3333 20.1667 13.75V11.9167C20.1667 7.33333 18.3333 5.5 13.75 5.5H8.25001C3.66668 5.5 1.83334 7.33333 1.83334 11.9167V13.75C1.83334 18.3333 3.66668 20.1667 8.25001 20.1667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M11.9258 1.83325L11.9167 2.75909C11.9075 3.26325 11.5042 3.66659 11 3.66659H10.9725C10.4683 3.66659 10.065 4.07909 10.065 4.58325C10.065 5.08742 10.4775 5.49992 10.9817 5.49992H11.8983" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

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
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 13.75C12.5188 13.75 13.75 12.5188 13.75 11C13.75 9.48122 12.5188 8.25 11 8.25C9.48122 8.25 8.25 9.48122 8.25 11C8.25 12.5188 9.48122 13.75 11 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 11.8066V10.1933C1.83333 9.23998 2.6125 8.45165 3.575 8.45165C5.23417 8.45165 5.9125 7.27832 5.07833 5.83915C4.60167 5.01415 4.88583 3.94165 5.72 3.46498L7.30583 2.55748C8.03 2.12665 8.965 2.38332 9.39583 3.10748L9.49667 3.28165C10.3217 4.72082 11.6783 4.72082 12.5125 3.28165L12.6133 3.10748C13.0442 2.38332 13.9792 2.12665 14.7033 2.55748L16.2892 3.46498C17.1233 3.94165 17.4075 5.01415 16.9308 5.83915C16.0967 7.27832 16.775 8.45165 18.4342 8.45165C19.3875 8.45165 20.1758 9.23082 20.1758 10.1933V11.8066C20.1758 12.76 19.3967 13.5483 18.4342 13.5483C16.775 13.5483 16.0967 14.7216 16.9308 16.1608C17.4075 16.995 17.1233 18.0583 16.2892 18.535L14.7033 19.4425C13.9792 19.8733 13.0442 19.6166 12.6133 18.8925L12.5125 18.7183C11.6875 17.2791 10.3308 17.2791 9.49667 18.7183L9.39583 18.8925C8.965 19.6166 8.03 19.8733 7.30583 19.4425L5.72 18.535C4.88583 18.0583 4.60167 16.9858 5.07833 16.1608C5.9125 14.7216 5.23417 13.5483 3.575 13.5483C2.6125 13.5483 1.83333 12.76 1.83333 11.8066Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('support') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('parent-support.index') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 13.75C12.5188 13.75 13.75 12.5188 13.75 11C13.75 9.48122 12.5188 8.25 11 8.25C9.48122 8.25 8.25 9.48122 8.25 11C8.25 12.5188 9.48122 13.75 11 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.83333 11.8066V10.1933C1.83333 9.23998 2.6125 8.45165 3.575 8.45165C5.23417 8.45165 5.9125 7.27832 5.07833 5.83915C4.60167 5.01415 4.88583 3.94165 5.72 3.46498L7.30583 2.55748C8.03 2.12665 8.965 2.38332 9.39583 3.10748L9.49667 3.28165C10.3217 4.72082 11.6783 4.72082 12.5125 3.28165L12.6133 3.10748C13.0442 2.38332 13.9792 2.12665 14.7033 2.55748L16.2892 3.46498C17.1233 3.94165 17.4075 5.01415 16.9308 5.83915C16.0967 7.27832 16.775 8.45165 18.4342 8.45165C19.3875 8.45165 20.1758 9.23082 20.1758 10.1933V11.8066C20.1758 12.76 19.3967 13.5483 18.4342 13.5483C16.775 13.5483 16.0967 14.7216 16.9308 16.1608C17.4075 16.995 17.1233 18.0583 16.2892 18.535L14.7033 19.4425C13.9792 19.8733 13.0442 19.6166 12.6133 18.8925L12.5125 18.7183C11.6875 17.2791 10.3308 17.2791 9.49667 18.7183L9.39583 18.8925C8.965 19.6166 8.03 19.8733 7.30583 19.4425L5.72 18.535C4.88583 18.0583 4.60167 16.9858 5.07833 16.1608C5.9125 14.7216 5.23417 13.5483 3.575 13.5483C2.6125 13.5483 1.83333 12.76 1.83333 11.8066Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('Support Tickets') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('features') }}" class="nav-link">
            <svg width="22" height="22" class="mr-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.3333 11.1834V12.7418C18.3333 15.6293 16.6833 16.8668 14.2083 16.8668H5.95831C3.48331 16.8668 1.83331 15.6293 1.83331 12.7418V7.79175C1.83331 4.90425 3.48331 3.66675 5.95831 3.66675H8.43331C8.31415 4.01508 8.24998 4.40008 8.24998 4.81258V8.3876C8.24998 9.27677 8.54331 10.0284 9.06581 10.5509C9.58831 11.0734 10.34 11.3668 11.2291 11.3668V12.641C11.2291 13.1085 11.7608 13.3925 12.155 13.1359L14.8041 11.3668H17.1875C17.6 11.3668 17.985 11.3025 18.3333 11.1834Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20.1667 4.81242V8.38744C20.1667 9.75328 19.47 10.7799 18.3333 11.1832C17.985 11.3024 17.6 11.3666 17.1875 11.3666H14.8042L12.155 13.1357C11.7608 13.3924 11.2292 13.1083 11.2292 12.6408V11.3666C10.34 11.3666 9.58833 11.0733 9.06583 10.5508C8.54333 10.0283 8.25 9.27661 8.25 8.38744V4.81242C8.25 4.39992 8.31417 4.01492 8.43333 3.66659C8.83667 2.52992 9.86333 1.83325 11.2292 1.83325H17.1875C18.975 1.83325 20.1667 3.02492 20.1667 4.81242Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.78333 20.1667H13.3833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.0833 16.8667V20.1667" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.9542 6.64583H16.9625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14.3877 6.64583H14.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.8208 6.64583H11.829" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span class="menu-title">{{ __('Chat History') }}</span>
        </a>
    </li>
    @endif
    </ul>
</nav>
<div class="app-menu navbar-menu">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        @if (Auth::user()->hasRole('teacher'))
            <a href="{{ route('teacher-dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="22">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="40" style="vertical-align: middle;">

                </span>
            </a>
        @elseif(Auth::user()->hasRole('deputy-director'))
            <a href="{{ route('dd-dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="22">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="40" style="vertical-align: middle;">

                </span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="50" style="vertical-align: middle;">

                </span>
            </a>
        @endif



        <!-- Light Logo-->
        @if (Auth::user()->hasRole('teacher'))
            <a href="{{ route('teacher-dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="50" style="vertical-align: middle;">

                </span>
            </a>
        @elseif(Auth::user()->hasRole('deputy-director'))
            <a href="{{ route('dd-dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="50" style="vertical-align: middle;">


                </span>

            </a>
        @else
            <a href="{{ route('dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                </span>
                <span class="logo-lg" style="display: inline-block; vertical-align: middle;">

                    <img src="{{ asset('Group.png') }}" alt="" height="50" style="vertical-align: middle;">
                </span>
            </a>
        @endif
        <button type="button" class="p-0 btn btn-sm fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <!-- <li class="menu-title"><span data-key="t-menu">Menu</span></li> -->

                {{-- @role('network_associate')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarNWA" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-account-sync"></i> <span data-key="t-dashboards">Network Associates</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarNWA">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('network-associates.profile', auth()->user()->id) }}" class="nav-link"
                                    data-key="t-analytics"> Profile </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole --}}

                @permission('view-staff-timing')
                    <li class="nav-item">
                        <a href="javascript:void(0);" data-url="{{ route('view-branch-schedule') }}"
                            class="nav-link show-modal" data-key="t-analytics" data-target="#branchScheduleModal">
                            <i class="ri-git-branch-line"></i><span data-key="t-dashboards">Staff Timings</span>
                        </a>
                    </li>
                @endpermission
                @permission('view-franchise-application')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCrm" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-git-branch-line"></i>
                            <span data-key="t-dashboards">Franchise Onboarding</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCrm">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('franchise-applications.index') }}" class="nav-link"
                                        data-key="t-analytics"> Franchise Application </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission(['list-booklist', 'list-scheme-of-work', 'list-lesson-plan', 'view-lessonplan-calendar',
                    'list-academic-calendar', 'list-class-timetable', 'list-school-manuals', 'list-infinity-teacher-guide'])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarAcademics" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarAcademics">
                            <i class="fas fa-graduation-cap"></i>
                            <span data-key="t-dashboards">Academics</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarAcademics">
                            <ul class="nav nav-sm flex-column">
                                @permission('list-lesson-plan')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="{{ route('lesson-plans.index') }}">Lesson
                                            Plan</a>
                                        {{-- @if (get_lesson_plan_hierarchy(get_set_NWABranchId(), 0, 0, 0, 'academic_year')->isEmpty())
                                <a class="nav-link menu-link" href="{{route('lesson-plans.index')}}">Lesson Plan</a>
                                @else
                                <a class="nav-link menu-link" href="#lessonPlanMultilevel" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="lessonPlanMultilevel">
                                    <span data-key="t-multi-level">Lesson Plan</span>
                                </a>
                                @endif
                                <div class="collapse menu-dropdown" id="lessonPlanMultilevel">
                                    <ul class="nav nav-sm flex-column">
                                        @foreach (get_lesson_plan_hierarchy(get_set_NWABranchId(), 0, 0, 0, 'academic_year') as $academic_year_index => $academic_year)
                                        <li class="nav-item">
                                            <a href="#academic_year{{$academic_year_index}}" class="nav-link"
                                                data-bs-toggle="collapse" role="button" aria-expanded="false"
                                                aria-controls="academic_year{{$academic_year_index}}">
                                                {{$academic_year['title']}}
                                            </a>
                                            <div class="collapse menu-dropdown"
                                                id="academic_year{{$academic_year_index}}">
                                                <ul class="nav nav-sm flex-column">
                                                    @foreach (get_lesson_plan_hierarchy(get_set_NWABranchId(), $academic_year['id'], 0, 0, 'class') as $class_index => $class)
                                                    <li class="nav-item">
                                                        <a href="#class{{$class_index}}" class="nav-link"
                                                            data-bs-toggle="collapse" role="button"
                                                            aria-expanded="false"
                                                            aria-controls="class{{$class_index}}">{{$class['com_class']['class_name']}}
                                                        </a>
                                                        <div class="collapse menu-dropdown" id="class{{$class_index}}">
                                                            <ul class="nav nav-sm flex-column">
                                                                @foreach (get_lesson_plan_hierarchy($class->branch_id, $class->academic_year_id, $class->com_class_id, 0, 'subject') as $subject_index => $subject)
                                                                <li class="nav-item">
                                                                    <a href="{{route('lesson-plans.index')}}?id={{$subject['id']}}"
                                                                        class="nav-link">
                                                                        {{$subject['subject']['subject_name']}}
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div> --}}
                                    </li>
                                @endpermission
                                @permission('view-lessonplan-calendar')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="{{ route('lesson-plans.calendar') }}">DLP
                                            Calendar</a>
                                    </li>
                                @endpermission
                                @permission('list-booklist')
                                    <li class="nav-item">
                                        <a href="{{ route('booklist.index') }}" class="nav-link">Booklist </a>
                                    </li>
                                @endpermission
                                @permission('list-scheme-of-work')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="{{ route('scheme_of_work.index') }}">Scheme of
                                            Work</a>
                                    </li>
                                @endpermission
                                @permission('list-scheme-of-work')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.admission-test.index') }}"
                                            class="nav-link">Admission Tests </a>
                                    </li>
                                @endpermission

                                @role('network_associate')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="javascript:void(0)">Grade Book</a>
                                    </li>
                                @endrole
                                @permission('list-certificates')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.certificates.index') }}"
                                            class="nav-link">Certificates </a>
                                    </li>
                                @endpermission
                                @permission('list-academic-calendar')
                                    <li class="nav-item">
                                        <a href="{{ route('academic-calendar.index') }}" class="nav-link">Calendar </a>
                                    </li>
                                @endpermission
                                @permission('list-class-timetable')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.class-timetable.index') }}"
                                            class="nav-link">Class
                                            Timetable</a>
                                    </li>
                                @endpermission
                                @permission('list-school-manuals')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link"
                                            href="{{ route('general-document.school-manual.index') }}">School Manuals</a>
                                    </li>
                                @endpermission
                                @permission('list-infinity-teacher-guide')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.infinity-teacher-guide.index') }}"
                                            class="nav-link">Infinity Teacher Guide</a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission('list-homework-diary')
                    @if (getBranch(Auth()->User()->id) == 3)
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="{{ route('homeWorkDiary.index') }}">
                                <i class="mdi mdi-home-account"></i>
                                <span data-key="t-homework">Home Work Diary</span>
                            </a>
                        </li>
                    @endif
                @endpermission
                @permission(['list-syllabus', 'list-datesheet', 'list-assessment-paper'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sideBarAssessment" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarLeaves">
                            <i class="mdi mdi-file-document"></i>
                            <span data-key="t-leaves">Assessment</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sideBarAssessment">
                            <ul class="nav nav-sm flex-column">
                                @permission('list-syllabus')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.syllabus.index') }}"
                                            class="nav-link">Syllabus</a>
                                    </li>
                                @endpermission
                                @permission('list-datesheet')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.subject-teacher-timetable.index') }}"
                                            class="nav-link">Datesheet</a>
                                    </li>
                                @endpermission
                                @permission('list-assessment-paper')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link"
                                            href="{{ route('general-document.assessment-paper.index') }}">Specified
                                            Assessment</a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission('list-attachments')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('general-document.index') }}">
                            <i class="ri-attachment-fill"></i>
                            <span data-key="t-dashboards">Documents</span>
                        </a>
                    </li>
                @endpermission
                @permission(['assessment-permission', 'assessment-settings-permission',
                    'settings-assessment-level-permission', 'settings-grading-criteria-permission',
                    'settings-skill-permission', 'settings-general-behaviour-permission',
                    'settings-subject-marks-setup-permission', 'assessment-entry-permission',
                    'assessment-grade-book-permission', 'assessment-student-behaviour-skill-permission',
                    'subject-remarks-permission'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sideBarAssessmentGradebook" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarLeaves">
                            <i class="mdi mdi-book-account"></i>
                            <span data-key="t-leaves">Assessment / GradeBook</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sideBarAssessmentGradebook">
                            <ul class="nav nav-sm flex-column">
                                @permission('assessment-settings-permission')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="#assessment_setting" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="calendarItems">
                                            <span data-key="t-multi-level">Setting</span>
                                        </a>
                                        <div class="collapse menu-dropdown" id="assessment_setting">
                                            <ul class="nav nav-sm flex-column">
                                                @permission('settings-assessment-level-permission')
                                                    <li class="nav-item">
                                                        <a href="{{ route('assessment-level.index') }}" class="nav-link"
                                                            data-key="t-projects">Assessment Level</a>
                                                    </li>
                                                @endpermission
                                                @permission('settings-grading-criteria-permission')
                                                    <li class="nav-item">
                                                        <a href="{{ route('grading-criteria.index') }}" class="nav-link"
                                                            data-key="t-projects">Grading Criteria</a>
                                                    </li>
                                                @endpermission
                                                @permission('settings-skill-permission')
                                                    <li class="nav-item">
                                                        <a href="{{ route('skill.index') }}" class="nav-link"
                                                            data-key="t-projects">Skills</a>
                                                    </li>
                                                    @permission('settings-general-behaviour-permission')
                                                    @endpermission
                                                    <li class="nav-item">
                                                        <a href="{{ route('general-behaviour.index') }}" class="nav-link"
                                                            data-key="t-projects">General Behaviour</a>
                                                    </li>
                                                @endpermission
                                                @permission('settings-subject-marks-setup-permission')
                                                    <li class="nav-item">
                                                        <a href="{{ route('subject-marks-setup.index') }}" class="nav-link"
                                                            data-key="t-projects">Subject Marks Setup</a>
                                                    </li>
                                                @endpermission

                                            </ul>
                                        </div>
                                    </li>
                                @endpermission
                                @permission('assessment-student-behaviour-skill-permission')
                                    <li class="nav-item">
                                        <a href="{{ route('student-behaviour-skill.index') }}" class="nav-link">
                                            <span data-key="t-dashboards">Skill Behaviour Entry</span>
                                        </a>
                                    </li>
                                @endpermission
                                @permission('assessment-entry-permission')

                                    <li class="nav-item">
                                        <a href="{{ route('assessment-entry.index') }}" class="nav-link">
                                            <span data-key="t-dashboards">Assessment Entry</span>
                                        </a>
                                    </li>
                                @endpermission
                                @permission('assessment-grade-book-permission')
                                    <li class="nav-item">
                                        <a href="{{ route('grade-book.index') }}" class="nav-link">
                                            <span data-key="t-dashboards">Grade Book</span>
                                        </a>
                                    </li>
                                @endpermission
                                @permission('subject-remarks-permission')
                                    <li class="nav-item">
                                        <a href="{{ route('subject-remarks.index') }}" class="nav-link">
                                            <span data-key="t-dashboards">Subject Remarks</span>
                                        </a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission
                @if (auth()->user()->hasRole('network_associate|accountant') || auth()->user()->hasPermission('list-branch'))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-git-branch-line"></i>
                            <span data-key="t-dashboards">Branch Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                @role('network_associate|accountant')
                                    {{-- <li class="nav-item">
                                <a href="{{ route('branches.edit', get_set_NWABranchId()) . '?tab=staff' }}"
                                    class="nav-link" data-key="t-analytics">
                                    Staff/Classes </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('branch-setup.branch-class-sections', 'student') }}" class="nav-link"
                                    data-key="t-analytics">Class Students</a>
                            </li> --}}
                                    <li class="nav-item">
                                        <a href="{{ route('branch-setup.branch-class-sections', 'teacher') }}"
                                            class="nav-link" data-key="t-analytics">
                                            Assign Classes
                                        </a>
                                    </li>
                                @endpermission
                                @permission('list-branch')
                                    <li class="nav-item">
                                        <a href="{{ route('branches.index') }}" class="nav-link"
                                            data-key="t-analytics">Branches
                                        </a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endif
                @permission('list-admission-inquiry')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarInquiries" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarInquiries">
                            <i class="ri-user-settings-line"></i> <span data-key="t-admissionInquiry">Admission
                                Inquiries</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarInquiries">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admission-query.index') }}" class="nav-link"
                                        data-key="t-admissionInquiry">Inquiries List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission('view-student|add-student')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarStudents" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarStudents">
                            <i class="ri-account-box-fill"></i>
                            <span data-key="t-dashboards">Student</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarStudents">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">

                                    @permission('add-student')
                                        <a href="{{ route('students.create') }}?tab=personal" class="nav-link"
                                            data-key="t-analytics">New Admissions</a>
                                    @endpermission

                                    @permission('view-student')
                                        <a href="{{ route('students.index') }}" class="nav-link"
                                            data-key="t-analytics">Students
                                            List </a>
                                    @endpermission
                                    <a href="{{ route('import.previous.data') }}" class="nav-link"
                                        data-key="t-analytics">Import Previous Data</a>
                                    @permission('beams-challans')
                                        <a href="{{ route('beams-challans.index') }}" class="nav-link"
                                            data-key="t-analytics">Beams Challans</a>
                                    @endpermission

                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission('add-fee-structure')

                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('fee_structure.index') }}">
                            <i class="mdi mdi-account-tie-voice"></i>
                            <span data-key="t-dashboards">Fee Structure</span>
                        </a>
                    </li>
                @endpermission
                @permission(['student-transfer-case', 'student-transfer-cases-list', 'student-transfer-case-create'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarStudentsTransferCase" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarStudentsTransferCase">
                            <i class="ri-account-box-fill"></i>
                            <span data-key="t-dashboards">Student Transfer Case</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarStudentsTransferCase">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('student-transfer-case.create') }}" class="nav-link"
                                        data-key="t-analytics">Create Transfer</a>
                                    <a href="{{ route('student-transfer-case.index') }}" class="nav-link"
                                        data-key="t-students-level"> Transfer List
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission(['promotion-request', 'student-promotion-request', 'individual-student-promotion',
                    'bulk-student-promotion'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarStudentsPromotionRequests" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarStudentsPromotionRequests">
                            <i class="ri-account-box-fill"></i>
                            <span data-key="t-dashboards">Student Promotions</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarStudentsPromotionRequests">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('promotion-requests.index') }}" class="nav-link"
                                        data-key="t-students-level"> Requests
                                    </a>
                                    <a href="{{ route('promotion-requests.create') }}" class="nav-link"
                                        data-key="t-students-level"> Bulk Promotions
                                    </a>
                                    <a href="{{ route('promotion-requests.individual') }}" class="nav-link"
                                        data-key="t-students-level"> Individual Promotion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @permission(['student-withdrawal', 'student-withdrawal-requests', 'student-withdrawal-create',
                    'student-withdrawal-list', 'student-auto-withdrawal'])
                    @permission('student-withdrawal')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarStudentWithdrawal" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarStudentWithdrawal">
                                <i class="ri-account-box-fill"></i>
                                <span data-key="t-dashboards">Student Withdrawal</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarStudentWithdrawal">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        @permission('student-withdrawal-requests')
                                            <a href="{{ route('students-withdrawal-requests') }}" class="nav-link"
                                                data-key="t-analytics">Withdrawal Requests</a>
                                        @endpermission
                                        @permission('student-withdrawal-create')
                                            <a href="{{ route('student-withdrawal.create') }}" class="nav-link"
                                                data-key="t-analytics">Create Withdrawal</a>
                                        @endpermission
                                        @permission('student-withdrawal-list')
                                            <a href="{{ route('student-withdrawal.index') }}" class="nav-link"
                                                data-key="t-analytics">Withdrawal List</a>
                                        @endpermission
                                        @permission('student-auto-withdrawal')
                                            <a href="{{ route('auto-withdrawal') }}" class="nav-link"
                                                data-key="t-analytics">Auto
                                                Withdrawal</a>
                                        @endpermission
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endpermission
                @endpermission

                @permission('academic-year-working-days')
                    <li class="nav-item">
                        <a href="{{ route('academic-year-working-days.index') }}" class="nav-link menu-link collapsed">
                            <i class="ri-attachment-fill"></i>
                            <span data-key="t-dashboards">Manage Working Days</span>
                        </a>
                    </li>
                @endpermission

                @permission('list-generate-invoice')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('bulk-invoices') }}">
                            <i class="ri-currency-fill"></i>
                            <span data-key="t-dashboards">Generate Invoice / Print</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('bulk-mark-as-paid') }}">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span data-key="t-dashboards">Bulk Paid Challans</span>
                        </a>
                    </li>
                @endpermission

                {{-- @permission('list-preview-invoice')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('preview-invoices') }}">
                            <i class="ri-currency-fill"></i>
                            <span data-key="t-dashboards">Preview Invoices</span>
                        </a>
                    </li>
                @endpermission --}}

                @if (auth()->user()->hasRole('network_associate') || auth()->user()->hasPermission('list-employees'))
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarEmployee" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarEmployee">
                            <i class="mdi mdi-account-tie"></i>
                            <span data-key="t-dashboards">Employee</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarEmployee">
                            <ul class="nav nav-sm flex-column">
                                @permission('list-employees')
                                    <li class="nav-item">
                                        <a href="{{ route('employees.create') }}?tab=basic_info" class="nav-link"
                                            data-key="t-employee"> Add New Employee </a>
                                        <a href="{{ route('employees.index') }}" class="nav-link" data-key="t-employee">
                                            Employees List </a>
                                    </li>
                                @endpermission

                                <!-- Payroll submenu START -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('payrolls*') ? '' : 'collapsed' }}"
                                        href="#sidebarPayroll" data-bs-toggle="collapse" role="button"
                                        aria-expanded="{{ Request::is('payrolls*') ? 'true' : 'false' }}"
                                        aria-controls="sidebarPayroll">
                                        <span data-key="t-dashboards">Payroll</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ Request::is('payrolls*') ? 'show' : '' }}"
                                        id="sidebarPayroll">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('payrolls.index') }}" 
                                                   class="nav-link {{ Request::is('payrolls') && !Request::is('payrolls/*') ? 'active' : '' }}">
                                                   <i class="ri-list-check"></i> Payroll List
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('payrolls.create') }}" 
                                                   class="nav-link {{ Request::is('payrolls/create') ? 'active' : '' }}">
                                                   <i class="ri-add-circle-line"></i> Create Payroll
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('payrolls.salary.index') }}" 
                                                   class="nav-link {{ Request::is('payrolls/salary*') ? 'active' : '' }}">
                                                   <i class="ri-money-dollar-circle-line"></i> Salary Management
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <!-- Payroll submenu END -->

                                @role('network_associate')
                                    <li class="nav-item">
                                        <a href="{{ route('show-leave-requests', Auth::user()->id) }}" class="nav-link"
                                            data-key="t-leaves">
                                            Leaves Requests </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('designationLeaveQuota.index') }}" class="nav-link"
                                            data-key="t-fee-level"> Designation Leave Quotas
                                        </a>
                                    </li>
                                @endrole
                            </ul>
                        </div>
                    </li>
                @endif

                @permission('list-branding-marketing')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('branding-marketing.index') }}">
                            <i class="mdi mdi-badge-account"></i>
                            <span data-key="t-dashboards">Branding / Marketing</span>
                        </a>
                    </li>
                @endpermission
                @permission('list-support-staff-uniform')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed"
                            href="{{ route('general-document.support-staff-uniform.index') }}">
                            <i class="mdi mdi-tshirt-crew"></i>
                            <span data-key="t-dashboards">Support Staff Uniform</span>
                        </a>
                    </li>
                @endpermission
                @role('teacher|accountant|parent-relation-officer|school_head|pr|head_of_qa|quality_assurance|legal-consultant|ho-marketing|marketing|academic_head|subject_coordinator')

                    @permission('apply-online-leave')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#employeeLeaves" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarLeaves">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-leaves">Leave</span>
                            </a>
                            <div class="collapse menu-dropdown" id="employeeLeaves">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('leave.application', Auth::user()->employee->id) }}"
                                            class="nav-link" data-key="t-leaves">
                                            Apply Online </a>
                                        <a href="{{ route('leave.application.applied-list', Auth::user()->employee->id) }}"
                                            class="nav-link" data-key="t-leaves">
                                            Applied List </a>
                                        <a href="{{ route('my-leave-qouta', Auth::user()->employee->designation->id) }}"
                                            class="nav-link" data-key="t-leaves">
                                            Leaves Quota </a>
                                        <a href="{{ route('show-leave-requests', Auth::user()->id) }}" class="nav-link"
                                            data-key="t-leaves">
                                            Leaves Requests </a>

                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endpermission
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#employeeAttendance" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarAttendance">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-leaves">Attendance</span>
                        </a>
                        <div class="collapse menu-dropdown" id="employeeAttendance">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('my-attendance') }}" class="nav-link" data-key="t-attendance">View
                                    </a>
                                </li>
                                @permission('mark-employees-attendance')
                                    <li class="nav-item">
                                        <a href="{{ route('attendance-sheet') }}" class="nav-link"
                                            data-key="t-attendance">Attendance Sheet </a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endrole
                @role('super_admin|network_associate')
                    <!-- <li class="nav-item">
                                                                                                                                                                                                                                                                                                                        <a class="nav-link menu-link" href="#sidebarEmployee" data-bs-toggle="collapse" role="button"
                                                                                                                                                                                                                                                                                                                            aria-expanded="false" aria-controls="sidebarEmployee">
                                                                                                                                                                                                                                                                                                                            <i class="ri-dashboard-2-line"></i>
                                                                                                                                                                                                                                                                                                                            <span data-key="t-dashboards">Employee</span>
                                                                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                                                                        <div class="collapse menu-dropdown" id="sidebarEmployee">
                                                                                                                                                                                                                                                                                                                            <ul class="nav nav-sm flex-column">
                                                                                                                                                                                                                                                                                                                                <li class="nav-item">
                                                                                                                                                                                                                                                                                                                                    <a href="{{ route('employees.create') }}?tab=basic_info" class="nav-link"
                                                                                                                                                                                                                                                                                                                                        data-key="t-analytics"> Staff Basic Info </a>
                                                                                                                                                                                                                                                                                                                                </li>
                                                                                                                                                                                                                                                                                                                            </ul>
                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                    </li> -->
                @endrole




                {{-- <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarFee" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarFee">
                        <i class="ri-currency-fill"></i>
                        <span data-key="t-dashboards">Fee</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarRoyalty" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarRoyalty">
                        <i><img width="17px" src="{{ asset('theme/icons/awesome-crown.png') }}" /></i>
                        <span data-key="t-dashboards">Royalty</span>
                    </a>
                </li> --}}

                @permission(['list-support', 'create-support'])
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('support-query.index') }}">
                            <i class="mdi mdi-account-tie-voice"></i>
                            <span data-key="t-dashboards">Support</span>
                        </a>
                    </li>
                @endpermission

                @permission('announcement')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('system-notifications.index') }}">
                            <i class="fas fa-volume-up"></i>

                            <span data-key="t-dashboards">Announcement</span>
                        </a>
                    </li>

                @endpermission

                @permission('teacher-evaluation')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('teacher_evaluation.index') }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span data-key="t-dashboards">Teacher Effectiveness</span>
                        </a>
                    </li>
                @endpermission
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="{{ route('timetables.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span data-key="t-dashboards">Teacher TimeTable</span>
                    </a>
                </li>
                @permission('teacher-evaluation')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarEvaluation" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarEvaluation">
                            <i class="mdi mdi-account-tie"></i>
                            <span data-key="t-dashboards">Teacher Evaluation</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarEvaluation">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('question-dimensions.index') }}?tab=basic_info" class="nav-link"
                                        data-key="t-employee"> Teacher Effectiveness Criteria </a>

                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="{{ route('question-dimensions.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span data-key="t-dashboards">Teacher Effectiveness Criteria</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="{{ route('answer-dimensions.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span data-key="t-dashboards">Answers Effectiveness Criteria</span>
                    </a>
                </li>


                <li class="nav-item">
                    @if (auth()->user()->id !== 14)
                        <a class="nav-link menu-link collapsed" href="#sidebarReports" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarReports">
                            <i class="ri-file-chart-line"></i>
                            <span data-key="t-dashboards">Reports</span>
                        </a>
                    @endif

                    <div class="collapse menu-dropdown" id="sidebarReports">
                        <ul class="nav nav-sm flex-column">
                            {{-- @permission('royalty-computation-report')
                                <li class="nav-item">
                                    <a href="{{ route('reports-computation') }}" class="nav-link"
                                        data-key="t-projects">Royalty Computation</a>
                                </li>
                            @endpermission --}}
                            @permission('unpaid-students-list')
                                <li class="nav-item">
                                    <a href="{{ route('unpaid-students-list') }}" class="nav-link"
                                        data-key="t-projects">Student Recoverables</a>
                                </li>
                            @endpermission
                            @permission('paid-students-list')
                                <li class="nav-item">
                                    <a href="{{ route('paid-students-list') }}" class="nav-link"
                                        data-key="t-projects">Fee
                                        Paid</a>
                                </li>
                            @endpermission
                            @permission('students-relation-list')
                                <li class="nav-item">
                                    <a href="{{ route('students-relation-list') }}" class="nav-link"
                                        data-key="t-projects">Students Relation</a>
                                </li>
                            @endpermission
                            @permission('students-transferred')
                                <li class="nav-item">
                                    <a href="{{ route('transfer-in-out') }}" class="nav-link"
                                        data-key="t-projects">Transfer
                                        In/Out</a>
                                </li>
                            @endpermission
                            @permission('branch-report')
                                <li class="nav-item">
                                    <a href="{{ route('branches-report') }}" class="nav-link" data-key="t-projects">UCS
                                        Branchs</a>
                                </li>
                            @endpermission
                            @permission('invoice-statuses')
                                <li class="nav-item">
                                    <a href="{{ route('invoice-status-report') }}" class="nav-link"
                                        data-key="t-projects">Invoice Statuses</a>
                                </li>
                            @endpermission
                            @permission('student-promotion-report')
                                <li class="nav-item">
                                    <a href="{{ route('student-promotion-report') }}" class="nav-link"
                                        data-key="t-projects">Students Promotions</a>
                                </li>
                            @endpermission

                        </ul>
                    </div>
                </li>

                @permission('list-admission-queries')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('admission-query.index') }}">
                            <i class="ri-user-settings-line"></i>
                            <span data-key="t-dashboards">Admission Queries</span>
                        </a>
                    </li>
                @endpermission
                @permission('list-visits')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarVisits" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarVisits">
                            <i class="mdi mdi-account-tie-voice"></i>
                            <span data-key="t-dashboards">Visits</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarVisits">
                            <ul class="nav nav-sm flex-column">
                                <a href="{{ route('visitDetail.index', ['req' => 'my']) }}" class="nav-link"
                                    data-key="t-visits">
                                    Requests Sent
                                </a>
                                <a href="{{ route('visitDetail.index', ['req' => 'me']) }}" class="nav-link"
                                    data-key="t-visits">
                                    Requests Received
                                </a>
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission('setting-fee|setting-cities|setting-towns')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMultilevel">
                            <i class="ri-settings-2-line"></i>
                            <span data-key="t-multi-level">Setting</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMultilevel">
                            <ul class="nav nav-sm flex-column">
                                @permission('setting-fee')
                                    <li class="nav-item">
                                        <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse" role="button"
                                            aria-expanded="false" aria-controls="sidebarAccount" data-key="t-fee-level"> Fee
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarAccount">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-concessions.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Concessions </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-concessions-type.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Concessions Type </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-charges.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Charges </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-charges-type.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Charges Type </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-packages.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Packages </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-period.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Periods </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('fee-tier.index') }}" class="nav-link"
                                                        data-key="t-fee-level"> Fee Tier </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endpermission
                                @permission('setting-cities')
                                    <li class="nav-item">
                                        <a href="{{ route('cities.index') }}" class="nav-link" data-key="t-crypto">Cities
                                        </a>
                                    </li>
                                @endpermission
                                @permission('setting-towns')
                                    <li class="nav-item">
                                        <a href="{{ route('towns.index') }}" class="nav-link" data-key="t-projects">Towns
                                        </a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission
                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarRolePermission" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarRolePermission">
                            <i class="ri-user-settings-line"></i>
                            <span data-key="t-dashboards">Roles & Responsibility</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarRolePermission">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link" data-key="t-analytics">
                                        Users </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('roles-permission-assignment-list') }}" class="nav-link"
                                        data-key="t-analytics"> Roles Assignment </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-analytics">
                                        Roles </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('permissions.index') }}" class="nav-link" data-key="t-analytics">
                                        Permissions </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole
                @permission(['student-withdrawal-requests', 'guardian-info-update', 'parent-queries'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#mobileAppQueries" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarLeaves">
                            <i class="mdi mdi-file-document"></i>
                            <span data-key="t-leaves">Mobile App Queries</span>
                        </a>
                        <div class="collapse menu-dropdown" id="mobileAppQueries">
                            <ul class="nav nav-sm flex-column">
                                @permission('student-withdrawal-requests')
                                    <li class="nav-item">
                                        <a href="{{ route('students-withdrawal-requests') }}" class="nav-link"
                                            data-key="t-analytics">Withdrawal Requests</a>
                                    </li>
                                @endpermission
                                @permission('guardian-info-update')
                                    <li class="nav-item">
                                        <a href="{{ route('guardian-info-update.index') }}" class="nav-link"
                                            data-key="t-projects">
                                            Guardian Info Update </a>
                                    </li>
                                @endpermission
                                @permission('parent-queries')
                                    <li class="nav-item">
                                        <a href="{{ route('parent-queries.index') }}" class="nav-link"
                                            data-key="t-projects">Parent
                                            Queries</a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission(['employment-letter-request', 'employment-letter-approval'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('employment-letter-requests*') ? '' : 'collapsed' }}"
                            href="#sidebarEmploymentLetter" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('employment-letter-requests*') ? 'true' : 'false' }}"
                            aria-controls="sidebarEmploymentLetter">
                            <i class="fas fa-file-alt"></i>
                            <span data-key="t-dashboards">Employment Letter</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('employment-letter-requests*') ? 'show' : '' }}"
                            id="sidebarEmploymentLetter">
                            <ul class="nav nav-sm flex-column">
                                @permission('employment-letter-request')
                                    <li class="nav-item">
                                        <a href="{{ route('employment-letter-requests.create') }}"
                                            class="nav-link {{ Request::is('employment-letter-requests/create') ? 'active' : '' }}"
                                            data-key="t-analytics">Request Letter</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employment-letter-requests.index') }}"
                                            class="nav-link {{ Request::is('employment-letter-requests') && !Request::is('employment-letter-requests/create') ? 'active' : '' }}"
                                            data-key="t-analytics">My Requests</a>
                                    </li>
                                @endpermission
                                @permission('employment-letter-approval')
                                    <li class="nav-item">
                                        <a href="{{ route('employment-letter-requests.approval') }}"
                                            class="nav-link {{ Request::is('employment-letter-requests/approval') ? 'active' : '' }}"
                                            data-key="t-analytics">Approve Requests</a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission(['exit-interview-feedback', 'exit-interview-review'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('exit-interview-feedbacks*') ? '' : 'collapsed' }}"
                            href="#sidebarExitInterview" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('exit-interview-feedbacks*') ? 'true' : 'false' }}"
                            aria-controls="sidebarExitInterview">
                            <i class="fas fa-comments"></i>
                            <span data-key="t-dashboards">Exit Interview</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('exit-interview-feedbacks*') ? 'show' : '' }}"
                            id="sidebarExitInterview">
                            <ul class="nav nav-sm flex-column">
                                @permission('exit-interview-feedback')
                                    <li class="nav-item">
                                        <a href="{{ route('exit-interview-feedbacks.create') }}"
                                            class="nav-link {{ Request::is('exit-interview-feedbacks/create') ? 'active' : '' }}"
                                            data-key="t-analytics">New Feedback</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('exit-interview-feedbacks.index') }}"
                                            class="nav-link {{ Request::is('exit-interview-feedbacks') && !Request::is('exit-interview-feedbacks/create') && !Request::is('exit-interview-feedbacks/review') ? 'active' : '' }}"
                                            data-key="t-analytics">My Feedbacks</a>
                                    </li>
                                @endpermission
                                @permission('exit-interview-review')
                                    <li class="nav-item">
                                        <a href="{{ route('exit-interview-feedbacks.review') }}"
                                            class="nav-link {{ Request::is('exit-interview-feedbacks/review') ? 'active' : '' }}"
                                            data-key="t-analytics">Review Feedbacks</a>
                                    </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission('system-notifications')
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('system-notifications.index') }}">
                            <i class="ri-attachment-fill"></i>
                            <span data-key="t-dashboards">System Notifications</span>
                        </a>
                    </li>
                @endpermission
                @role('human_resource')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('working-day', 'working-shift', 'official-leave-day') ? '' : 'collapsed' }}"
                        href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ Request::is('working-day', 'working-shift', 'official-leave-day') ? 'true' : 'false' }}"
                        aria-controls="sidebarSettings">
                        <i class="ri-settings-2-line"></i>
                        <span data-key="t-settings">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Request::is('working-day', 'working-shift', 'official-leave-day') ? 'show' : '' }}"
                        id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('working-day.index') }}"
                                    class="nav-link {{ Request::is('working-day') ? 'active' : '' }}"
                                    data-key="t-shift-level">Working Days</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('working-shift.index') }}"
                                    class="nav-link {{ Request::is('working-shift') ? 'active' : '' }}"
                                    data-key="t-shift-level">Schedule Shifts</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('official-leave-day.index') }}"
                                    class="nav-link {{ Request::is('official-leave-day') ? 'active' : '' }}"
                                    data-key="t-shift-level">National Holidays</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole
            </ul>
        </div>
    </div>
</div>
<style>
    .side-nav {
        position: fixed;
        top: 0;
        right: -250px;
        /* Initially hide the side-nav to the right */
        height: 100%;
        width: 250px;
        background-color: #ffffff;
        color: #000000;
        padding: 20px;
        transition: right 0.3s;
        /* Add transition for smooth animation */
    }

    .side-nav ul {
        list-style: none;
        padding: 0;
    }

    .side-nav li {
        margin-bottom: 10px;
    }

    .side-nav a {
        text-decoration: none;
        color: #000000;
    }

    .side-nav .close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .nav-link.active {
        color: #007AFF !important;

    }

    .nav-link:hover {
        color: #007AFF !important;
    }

    [data-layout=vertical][data-sidebar-size=sm] .logo span.logo-lg {
        display: none !important;
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .nav-link:hover {
        color: #007AFF !important;
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .nav-link:hover:before {
        background-color: #007AFF !important;
    }
</style>

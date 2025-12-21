<div class="app-menu navbar-menu">
    <x-nav.nav-styles />

    <x-nav.nav-brand />

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <x-nav.menu-search />
            <ul class="navbar-nav" id="navbar-nav">
                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is(['network-associates', 'branches', 'student/class-sections', 'teacher/class-sections']) ? 'active' : '' }}"
                            href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                            {{ Request::is(['network-associates', 'branches', 'student/class-sections', 'teacher/class-sections'])
                                ? 'aria-expanded="true"'
                                : 'aria-expanded="false"' }}
                            aria-controls="sidebarDashboards">
                            <i class="ri-git-branch-line"></i> <span data-key="t-dashboards">Branch Setup</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is(['network-associates', 'branches', 'student/class-sections', 'teacher/class-sections']) ? 'show' : '' }}"
                            id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{ route('branches.index') }}"
                                        class="nav-link  {{ Request::is('branches') ? 'active' : '' }}"
                                        data-key="t-analytics">
                                        Branches </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('branch-setup.branch-class-sections', 'student') }}"
                                        class="nav-link  {{ Request::is('student/class-sections') ? 'active' : '' }}"
                                        data-key="t-analytics">
                                        Class Students
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('branch-setup.branch-class-sections', 'teacher') }}"
                                        class="nav-link {{ Request::is('teacher/class-sections') ? 'active' : '' }}"
                                        data-key="t-analytics">
                                        Class Teachers
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole
                @permission('list-admission-inquiry')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('admission-query') ? 'active' : '' }}"
                            href="#sidebarInquiries" data-bs-toggle="collapse" role="button"
                            {{ Request::is('admission-query') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}
                            aria-controls="sidebarInquiries">
                            <i class="ri-user-settings-line"></i> <span data-key="t-admissionInquiry">Admission
                                Inquiries</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('admission-query') ? 'show' : '' }}"
                            id="sidebarInquiries">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admission-query.index') }}"
                                        class="nav-link {{ Request::is('admission-query') ? 'active' : '' }}"
                                        data-key="t-admissionInquiry">Inquiries List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
                @role('super_admin|network_associate')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('students', 'beams-challans', 'eca') ? 'active' : '' }}"
                            href="#sidebarStudents" data-bs-toggle="collapse" role="button"
                            {{ Request::is('students', 'beams-challans', 'eca') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}
                            aria-controls="sidebarStudents">
                            <i class="ri-account-box-fill"></i> <span data-key="t-dashboards">Student</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('students', 'beams-challans', 'eca') ? 'show' : '' }}"
                            id="sidebarStudents">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}?tab=personal"
                                        class="nav-link {{ Request::is('students/create') ? 'active' : '' }}"
                                        data-key="t-analytics">New Admissions</a>
                                    <a href="{{ route('students.index') }}"
                                        class="nav-link {{ Request::is('students') ? 'active' : '' }}"
                                        data-key="t-analytics">Students List</a>
                                    <a href="{{ route('import.previous.data') }}"
                                        class="nav-link {{ Request::is('students/import-previous-data') ? 'active' : '' }}"
                                        data-key="t-analytics">Import Previous Data</a>
                                    @permission('beams-challans')
                                        <a href="{{ route('beams-challans.index') }}"
                                            class="nav-link {{ Request::is('beams-challans') ? 'active' : '' }}"
                                            data-key="t-analytics">Challans</a>
                                    @endpermission
                                    @permission('view-eca')
                                        <a href="{{ route('eca.index') }}"
                                            class="nav-link {{ Request::is('eca') ? 'active' : '' }}"
                                            data-key="t-analytics">ECA</a>
                                    @endpermission
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole
                @permission(['student-transfer-case', 'student-transfer-cases-list', 'student-transfer-case-create'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('student-transfer-case') ? 'active' : '' }}"
                            href="#sidebarStudentsTransferCase" data-bs-toggle="collapse" role="button"
                            {{ Request::is('student-transfer-case') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}
                            aria-controls="sidebarStudentsTransferCase">
                            <i class="ri-account-box-fill"></i> <span data-key="t-dashboards">Student Transfer Case</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('student-transfer-case') ? 'show' : '' }}"
                            id="sidebarStudentsTransferCase">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('student-transfer-case.create') }}"
                                        class="nav-link {{ Request::is('student-transfer-case/create') ? 'active' : '' }}"
                                        data-key="t-analytics">Create Transfer</a>
                                    <a href="{{ route('student-transfer-case.index') }}"
                                        class="nav-link {{ Request::is('student-transfer-case') ? 'active' : '' }}"
                                        data-key="t-students-level">Transfer List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission(['promotion-request', 'student-promotion-request', 'individual-student-promotion',
                    'bulk-student-promotion'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('promotion-requests') ? 'active' : '' }}"
                            href="#sidebarStudentsPromotionRequests" data-bs-toggle="collapse" role="button"
                            {{ Request::is('promotion-requests') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}
                            aria-controls="sidebarStudentsPromotionRequests">
                            <i class="ri-account-box-fill"></i> <span data-key="t-dashboards">Student Promotions</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('promotion-requests') ? 'show' : '' }}"
                            id="sidebarStudentsPromotionRequests">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('promotion-requests.index') }}"
                                        class="nav-link {{ Request::is('promotion-requests') ? 'active' : '' }}"
                                        data-key="t-students-level">Requests</a>
                                    <a href="{{ route('promotion-requests.create') }}"
                                        class="nav-link {{ Request::is('promotion-requests/create') ? 'active' : '' }}"
                                        data-key="t-students-level">Bulk Promotions</a>
                                    <a href="{{ route('promotion-requests.individual') }}"
                                        class="nav-link {{ Request::is('promotion-requests-individual') ? 'active' : '' }}"
                                        data-key="t-students-level">Individual Promotion</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission(['student-withdrawal', 'student-withdrawal-create', 'student-withdrawal-list',
                    'student-auto-withdrawal'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('student-withdrawal', 'auto-withdrawal') ? '' : 'collapsed' }}"
                            href="#sidebarStudentWithdrawal" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('student-withdrawal', 'auto-withdrawal') ? 'true' : 'false' }}"
                            aria-controls="sidebarStudentWithdrawal">
                            <i class="ri-account-box-fill"></i>
                            <span data-key="t-dashboards">Student Withdrawal</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('student-withdrawal', 'auto-withdrawal') ? 'show' : '' }}"
                            id="sidebarStudentWithdrawal">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('student-withdrawal.create') }}"
                                        class="nav-link {{ Request::is('student-withdrawal/create') ? 'active' : '' }}"
                                        data-key="t-analytics">Create Withdrawal</a>
                                    <a href="{{ route('student-withdrawal.index') }}"
                                        class="nav-link {{ Request::is('student-withdrawal') && !Request::is('student-withdrawal/create') ? 'active' : '' }}"
                                        data-key="t-analytics">Withdrawal List</a>
                                    <a href="{{ route('auto-withdrawal') }}"
                                        class="nav-link {{ Request::is('auto-withdrawal') ? 'active' : '' }}"
                                        data-key="t-analytics">Auto Withdrawal</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission

                @permission('list-generate-invoice')

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('enhanced-bulk-challans') ? 'active' : '' }}"
                            href="{{ route('enhanced-bulk-challans') }}">
                            <i class="ri-file-download-line"></i>
                            <span data-key="t-dashboards">Bulk Challans</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('bulk-mark-as-paid') ? 'active' : '' }}"
                            href="{{ route('bulk-mark-as-paid') }}">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span data-key="t-dashboards">Bulk Paid Challans</span>
                        </a>
                    </li>
                @endpermission

                @role('school_teacher')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('teacher-dashboard') ? '' : 'collapsed' }}"
                            href="#teacherDashboard" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('teacher-dashboard') ? 'true' : 'false' }}"
                            aria-controls="teacherDashboard">
                            <i class="mdi mdi-book-education"></i>
                            <span data-key="t-dashboards">Teacher</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('teacher-dashboard') ? 'show' : '' }}"
                            id="teacherDashboard">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('teacher-dashboard') }}"
                                        class="nav-link {{ Request::is('teacher-dashboard') ? 'active' : '' }}"
                                        data-key="t-analytics">Dashboard</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole
                @role('super_admin|network_associate')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('employees', 'visitDetail', 'timetables') ? '' : 'collapsed' }}"
                            href="#sidebarEmployee" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('employees', 'visitDetail', 'timetables') ? 'true' : 'false' }}"
                            aria-controls="sidebarEmployee">
                            <i class="mdi mdi-account-tie"></i>
                            <span data-key="t-dashboards">Employee / Teacher</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('employees', 'visitDetail', 'timetables') ? 'show' : '' }}"
                            id="sidebarEmployee">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('employees.create') }}?tab=basic_info"
                                        class="nav-link {{ Request::is('employees/create') ? 'active' : '' }}"
                                        data-key="t-employee">Add New Employee</a>
                                    <a href="{{ route('employees.index') }}"
                                        class="nav-link {{ Request::is('employees') && !Request::is('employees/create') ? 'active' : '' }}"
                                        data-key="t-employee">Employee List</a>

                                    <a href="{{ route('timetables.index') }}"
                                        class="nav-link {{ Request::is('timetables') ? 'active' : '' }}"
                                        data-key="t-employee">Teacher TimeTable</a>
                                </li>

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
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="{{ route('teacher_evaluation.index') }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span data-key="t-dashboards">Teacher Effectiveness</span>
                        </a>
                    </li>

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
                @endrole

                @permission(['list-booklist', 'list-scheme-of-work', 'list-lesson-plan', 'view-lessonplan-calendar',
                    'list-academic-calendar', 'list-class-timetable', 'list-school-manuals', 'list-infinity-teacher-guide',
                    'list-summer-resource-pack', 'list-winter-resource-pack'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is(
                            'show-curriculum',
                            'calendar',
                            'general-document/certificates/index',
                            'general-document/admission-test/index',
                            'booklist',
                            'standardized-timetable',
                            'scheme_of_work',
                            'lesson-plans',
                            'lesson-plans-calendar',
                            'academic-calendar/index',
                            'general-document/class-timetable/index',
                            'general-document/infinity-teacher-guide/index',
                            'general-document/school-manual/index',
                        )
                            ? ''
                            : 'collapsed' }}"
                            href="#sidebarAcademics" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is(
                                'show-curriculum',
                                'calendar',
                                'general-document/certificates/index',
                                'general-document/admission-test/index',
                                'booklist',
                                'standardized-timetable',
                                'scheme_of_work',
                                'lesson-plans',
                                'lesson-plans-calendar',
                                'academic-calendar/index',
                                'general-document/class-timetable/index',
                                'general-document/infinity-teacher-guide/index',
                                'general-document/school-manual/index',
                            )
                                ? 'true'
                                : 'false' }}"
                            aria-controls="sidebarAcademics">
                            <i class="fas fa-graduation-cap"></i>
                            <span data-key="t-dashboards">Academics</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarAcademics">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('show-curriculum') ? 'active' : '' }}"
                                        href="{{ route('show-curriculum') }}">Curriculum</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('calendar') ? '' : 'collapsed' }}"
                                        href="#calendarItems" data-bs-toggle="collapse" role="button"
                                        aria-expanded="{{ Request::is('calendar') ? 'true' : 'false' }}"
                                        aria-controls="calendarItems">
                                        <span data-key="t-multi-level">Calendar</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ Request::is('calendar') ? 'show' : '' }}"
                                        id="calendarItems">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('calendar/academic') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Academic</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('calendar/activity') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Activity</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('calendar/training') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Training</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @permission('list-certificates')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.certificates.index') }}"
                                            class="nav-link {{ Request::is('general-document/certificates/index') ? 'active' : '' }}">Certificates</a>
                                    </li>
                                @endpermission
                                @permission('list-scheme-of-work')
                                    <li class="nav-item">
                                        <a href="{{ route('general-document.admission-test.index') }}"
                                            class="nav-link {{ Request::is('general-document/admission-test/index') ? 'active' : '' }}">Admission
                                            Tests</a>
                                    </li>
                                @endpermission
                                @permission('list-booklist')
                                    <li class="nav-item">
                                        <a href="{{ route('booklist.index') }}"
                                            class="nav-link {{ Request::is('booklist') ? 'active' : '' }}">Booklist</a>
                                    </li>
                                @endpermission
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('standardized-timetable') ? 'active' : '' }}"
                            href="javascript:void(0)">Standardized Timetable</a>
                    </li>
                    @permission('list-scheme-of-work')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ Request::is('scheme_of_work') ? 'active' : '' }}"
                                href="{{ route('scheme_of_work.index') }}">Scheme of Work</a>
                        </li>
                    @endpermission
                    @permission('list-lesson-plan')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ Request::is('lesson-plans') ? 'active' : '' }}"
                                href="{{ route('lesson-plans.index') }}">Daily Lesson Plan</a>

                    @endpermission

                    @permission('view-lessonplan-calendar')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ Request::is('lesson-plans-calendar') ? 'active' : '' }}"
                                href="{{ route('lesson-plans.calendar') }}">DLP Calendar</a>
                        </li>
                    @endpermission

                    @permission('list-academic-calendar')
                        <li class="nav-item">
                            <a href="{{ route('academic-calendar.index') }}"
                                class="nav-link {{ Request::is('academic-calendar/index') ? 'active' : '' }}">Calendar</a>
                        </li>
                    @endpermission

                    @permission('list-class-timetable')
                        <li class="nav-item">
                            <a href="{{ route('general-document.class-timetable.index') }}"
                                class="nav-link {{ Request::is('general-document/class-timetable/index') ? 'active' : '' }}">Class
                                Timetable</a>
                        </li>
                    @endpermission

                    @permission('list-infinity-teacher-guide')
                        <li class="nav-item">
                            <a href="{{ route('general-document.infinity-teacher-guide.index') }}"
                                class="nav-link {{ Request::is('general-document/infinity-teacher-guide/index') ? 'active' : '' }}">Infinity
                                Teacher Guide</a>
                        </li>
                    @endpermission

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('resource-pack') ? 'active' : '' }}"
                            href="javascript:void(0)">Resource Pack</a>
                    </li>

                    @permission('list-summer-resource-pack')
                        <li class="nav-item">
                            <a href="{{ route('general-document.summer-resource-pack.index') }}"
                                class="nav-link {{ Request::is('general-document.summer-resource-pack') ? 'active' : '' }}">Summer
                                Resource Pack</a>
                        </li>
                    @endpermission

                    @permission('list-winter-resource-pack')
                        <li class="nav-item">
                            <a href="{{ route('general-document.winter-resource-pack.index') }}"
                                class="nav-link {{ Request::is('general-document.winter-resource-pack') ? 'active' : '' }}">Winter
                                Resource Pack</a>
                        </li>
                    @endpermission

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('examination') ? '' : 'collapsed' }}"
                            href="#examinationItems" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ Request::is('examination') ? 'true' : 'false' }}"
                            aria-controls="examinationItems">
                            <span data-key="t-multi-level">Examination</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('examination') ? 'show' : '' }}"
                            id="examinationItems">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('examination/mid-year') ? '' : 'collapsed' }}"
                                        href="#midYearExaminationItems" data-bs-toggle="collapse" role="button"
                                        aria-expanded="{{ Request::is('examination/mid-year') ? 'true' : 'false' }}"
                                        aria-controls="midYearExaminationItems">
                                        <span data-key="t-multi-level">Mid-Year</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ Request::is('examination/mid-year') ? 'show' : '' }}"
                                        id="midYearExaminationItems">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('examination/mid-year/syllabus') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Syllabus & Date Sheet</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('examination/mid-year/papers') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Papers along with Marking Keys</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('examination/end-of-year') ? '' : 'collapsed' }}"
                                        href="#eoyExaminationItems" data-bs-toggle="collapse" role="button"
                                        aria-expanded="{{ Request::is('examination/end-of-year') ? 'true' : 'false' }}"
                                        aria-controls="eoyExaminationItems">
                                        <span data-key="t-multi-level">End of Year</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ Request::is('examination/end-of-year') ? 'show' : '' }}"
                                        id="eoyExaminationItems">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('examination/end-of-year/syllabus') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Syllabus & Date Sheet</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link menu-link {{ Request::is('examination/end-of-year/papers') ? 'active' : '' }}"
                                                    href="javascript:void(0)">Papers along with Marking Keys</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @permission('list-school-manuals')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ Request::is('general-document/school-manual/index') ? 'active' : '' }}"
                                href="{{ route('general-document.school-manual.index') }}">School Manuals</a>
                        </li>
                    @endpermission
                </ul>
            </div>
        </li>
        @endpermission

        <li class="nav-item">
            <a class="nav-link menu-link {{ Request::is('homeWorkDiary') ? '' : 'collapsed' }}"
                href="{{ route('homeWorkDiary.index') }}">
                <i class="mdi mdi-home-account"></i>
                <span data-key="t-homework">Home Work Diary</span>
            </a>
        </li>

        @permission(['assessment-permission', 'assessment-settings-permission', 'settings-assessment-level-permission',
            'settings-grading-criteria-permission', 'settings-skill-permission', 'settings-general-behaviour-permission',
            'settings-subject-marks-setup-permission', 'assessment-entry-permission', 'assessment-grade-book-permission',
            'assessment-student-behaviour-skill-permission', 'subject-remarks-permission'])
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup', 'student-behaviour-skill', 'assessment-entry', 'subject-remarks', 'grade-book') ? '' : 'collapsed' }}"
                    href="#sideBarAssessmentGradebook" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup', 'student-behaviour-skill', 'assessment-entry', 'subject-remarks', 'grade-book') ? 'true' : 'false' }}"
                    aria-controls="sideBarAssessmentGradebook">
                    <i class="mdi mdi-book-account"></i>
                    <span data-key="t-leaves">Assessment / GradeBook</span>
                </a>
                <div class="collapse menu-dropdown {{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup', 'student-behaviour-skill', 'assessment-entry', 'subject-remarks', 'grade-book') ? 'show' : '' }}"
                    id="sideBarAssessmentGradebook">
                    <ul class="nav nav-sm flex-column">
                        @permission('assessment-settings-permission')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup') ? '' : 'collapsed' }}"
                                    href="#assessment_setting" data-bs-toggle="collapse" role="button"
                                    aria-expanded="{{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup') ? 'true' : 'false' }}"
                                    aria-controls="assessment_setting">
                                    <span data-key="t-multi-level">Setting</span>
                                </a>
                                <div class="collapse menu-dropdown {{ Request::is('assessment-level', 'grading-criteria', 'skill', 'general-behaviour', 'subject-marks-setup') ? 'show' : '' }}"
                                    id="assessment_setting">
                                    <ul class="nav nav-sm flex-column">
                                        @permission('settings-assessment-level-permission')
                                            <li class="nav-item">
                                                <a href="{{ route('assessment-level.index') }}"
                                                    class="nav-link {{ Request::is('assessment-level') ? 'active' : '' }}"
                                                    data-key="t-projects">Assessment Level</a>
                                            </li>
                                        @endpermission
                                        @permission('settings-grading-criteria-permission')
                                            <li class="nav-item">
                                                <a href="{{ route('grading-criteria.index') }}"
                                                    class="nav-link {{ Request::is('grading-criteria') ? 'active' : '' }}"
                                                    data-key="t-projects">Grading Criteria</a>
                                            </li>
                                        @endpermission
                                        @permission('settings-skill-permission')
                                            <li class="nav-item">
                                                <a href="{{ route('skill.index') }}"
                                                    class="nav-link {{ Request::is('skill') ? 'active' : '' }}"
                                                    data-key="t-projects">Skills</a>
                                            </li>
                                        @endpermission
                                        @permission('settings-general-behaviour-permission')
                                            <li class="nav-item">
                                                <a href="{{ route('general-behaviour.index') }}"
                                                    class="nav-link {{ Request::is('general-behaviour') ? 'active' : '' }}"
                                                    data-key="t-projects">General Behaviour</a>
                                            </li>
                                        @endpermission
                                        @permission('settings-subject-marks-setup-permission')
                                            <li class="nav-item">
                                                <a href="{{ route('subject-marks-setup.index') }}"
                                                    class="nav-link {{ Request::is('subject-marks-setup') ? 'active' : '' }}"
                                                    data-key="t-projects">Subject Marks Setup</a>
                                            </li>
                                        @endpermission
                                    </ul>
                                </div>
                            </li>
                        @endpermission
                        @permission('assessment-student-behaviour-skill-permission')
                            <li class="nav-item">
                                <a href="{{ route('student-behaviour-skill.index') }}"
                                    class="nav-link {{ Request::is('student-behaviour-skill') ? 'active' : '' }}">
                                    <span data-key="t-dashboards">Skill Behaviour Entry</span>
                                </a>
                            </li>
                        @endpermission
                        @permission('assessment-entry-permission')
                            <li class="nav-item">
                                <a href="{{ route('assessment-entry.index') }}"
                                    class="nav-link {{ Request::is('assessment-entry') ? 'active' : '' }}">
                                    <span data-key="t-dashboards">Assessment Entry</span>
                                </a>
                            </li>
                        @endpermission
                        @permission('subject-remarks-permission')
                            <li class="nav-item">
                                <a href="{{ route('subject-remarks.index') }}"
                                    class="nav-link {{ Request::is('subject-remarks') ? 'active' : '' }}">
                                    <span data-key="t-dashboards">Subject Remarks</span>
                                </a>
                            </li>
                        @endpermission
                        @permission('assessment-grade-book-permission')
                            <li class="nav-item">
                                <a href="{{ route('grade-book.index') }}"
                                    class="nav-link {{ Request::is('grade-book') ? 'active' : '' }}">
                                    <span data-key="t-dashboards">Grade Book</span>
                                </a>
                            </li>
                        @endpermission
                    </ul>
                </div>
            </li>
        @endpermission

        @permission(['list-syllabus', 'list-datesheet', 'list-assessment-paper'])
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('general-document/syllabus/index', 'general-document/subject-teacher-timetable/index', 'general-document/assessment-paper/index') ? '' : 'collapsed' }}"
                    href="#sideBarAssessment" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('general-document/syllabus/index', 'general-document/subject-teacher-timetable/index', 'general-document/assessment-paper/index') ? 'true' : 'false' }}"
                    aria-controls="sideBarAssessment">
                    <i class="mdi mdi-file-document"></i>
                    <span data-key="t-leaves">Assessment</span>
                </a>
                <div class="collapse menu-dropdown {{ Request::is('general-document/syllabus/index', 'general-document/subject-teacher-timetable/index', 'general-document/assessment-paper/index') ? 'show' : '' }}"
                    id="sideBarAssessment">
                    <ul class="nav nav-sm flex-column">
                        @permission('list-syllabus')
                            <li class="nav-item">
                                <a href="{{ route('general-document.syllabus.index') }}"
                                    class="nav-link {{ Request::is('general-document/syllabus/index') ? 'active' : '' }}">Syllabus</a>
                            </li>
                        @endpermission
                        @permission('list-datesheet')
                            <li class="nav-item">
                                <a href="{{ route('general-document.subject-teacher-timetable.index') }}"
                                    class="nav-link {{ Request::is('general-document/subject-teacher-timetable/index') ? 'active' : '' }}">Datesheet</a>
                            </li>
                        @endpermission
                        @permission('list-assessment-paper')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('general-document/assessment-paper/index') ? 'active' : '' }}"
                                    href="{{ route('general-document.assessment-paper.index') }}">Specified Assessment</a>
                            </li>
                        @endpermission
                    </ul>
                </div>
            </li>
        @endpermission

        @permission('list-attachments')
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('general-document') ? 'active' : '' }}"
                    href="{{ route('general-document.index') }}">
                    <i class="ri-attachment-fill"></i>
                    <span data-key="t-dashboards">Documents</span>
                </a>
            </li>
        @endpermission

        <li class="nav-item">
            <a class="nav-link menu-link {{ Request::is('fee-concessions', 'fee-concessions-type', 'fee-charges', 'fee-charges-type', 'fee-packages', 'fee-period', 'fee-tier', 'branch-securities', 'guardian-info-update') ? '' : 'collapsed' }}"
                href="#sideBarFee" data-bs-toggle="collapse" role="button"
                aria-expanded="{{ Request::is('fee-concessions', 'fee-concessions-type', 'fee-charges', 'fee-charges-type', 'fee-packages', 'fee-period', 'fee-tier', 'branch-securities', 'guardian-info-update') ? 'true' : 'false' }}"
                aria-controls="sideBarFee">
                <i class="ri-currency-fill"></i> <span data-key="t-leaves">Fee</span>
            </a>
            <div class="collapse menu-dropdown {{ Request::is('fee-concessions', 'fee-concessions-type', 'fee-charges', 'fee-charges-type', 'fee-packages', 'fee-period', 'fee-tier', 'branch-securities', 'guardian-info-update') ? 'show' : '' }}"
                id="sideBarFee">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ route('fee-concessions.index') }}"
                            class="nav-link {{ Request::is('fee-concessions') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Concessions</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-concessions-type.index') }}"
                            class="nav-link {{ Request::is('fee-concessions-type') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Concessions Type</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-charges.index') }}"
                            class="nav-link {{ Request::is('fee-charges') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Charges</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-charges-type.index') }}"
                            class="nav-link {{ Request::is('fee-charges-type') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Charges Type</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-packages.index') }}"
                            class="nav-link {{ Request::is('fee-packages') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Packages</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-period.index') }}"
                            class="nav-link {{ Request::is('fee-period') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Periods</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fee-tier.index') }}"
                            class="nav-link {{ Request::is('fee-tier') ? 'active' : '' }}"
                            data-key="t-fee-level">Fee Tier</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('branch-securities.index') }}"
                            class="nav-link {{ Request::is('branch-securities') ? 'active' : '' }}"
                            data-key="t-fee-level">Security Charges</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guardian-info-update.index') }}"
                            class="nav-link {{ Request::is('guardian-info-update') ? 'active' : '' }}"
                            data-key="t-projects">Guardian Info Update</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link {{ Request::is('fee_structure') ? '' : 'collapsed' }}"
                href="{{ route('fee_structure.index') }}">
                <i class="mdi mdi-account-tie-voice"></i>
                <span data-key="t-dashboards">Fee Structure</span>
            </a>
        </li>

        @permission(['list-support', 'create-support'])

        @endpermission

        @permission('system-notifications')
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('system-notifications') ? '' : 'collapsed' }}"
                    href="{{ route('system-notifications.index') }}">
                    <i class="fas fa-volume-up"></i>
                    <span data-key="t-dashboards">Announcement</span>
                </a>
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

        @permission('academic-year-working-days')
            <li class="nav-item">
                <a href="{{ route('academic-year-working-days.index') }}"
                    class="nav-link menu-link {{ Request::is('academic-year-working-days') ? '' : 'collapsed' }}">
                    <i class="ri-attachment-fill"></i>
                    <span data-key="t-dashboards">Manage Working Days</span>
                </a>
            </li>
        @endpermission
        @role(['super_admin', 'human_resource'])
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('fixed-assets*') ? '' : 'collapsed' }}" href="#sidebarFixedAssets" data-bs-toggle="collapse"
                    role="button" aria-expanded="{{ Request::is('fixed-assets*') ? 'true' : 'false' }}" aria-controls="sidebarFixedAssets">
                    <i class="fas fa-boxes me-1"></i>
                    <span data-key="t-fixed-assets">Fixed Assets</span>
                </a>
                <div class="collapse menu-dropdown {{ Request::is('fixed-assets*') ? 'show' : '' }}" id="sidebarFixedAssets">
                    <ul class="nav nav-sm flex-column">

                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.categories.index') }}" class="nav-link {{ Request::is('fixed-assets/categories*') ? 'active' : '' }}">
                                <i class="fas fa-tags me-1"></i>
                                Categories
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.suppliers.index') }}" class="nav-link {{ Request::is('fixed-assets/suppliers*') ? 'active' : '' }}">
                                <i class="fas fa-table me-1"></i>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.assets.index') }}" class="nav-link {{ Request::is('fixed-assets/assets') ? 'active' : '' }}">
                                <i class="fas fa-cube me-1"></i>
                                Asset Register
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.transfer-requests.index') }}" class="nav-link {{ Request::is('fixed-assets/transfer-requests*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt me-1"></i>
                                Transfer Assets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.purchase-requests.index') }}" class="nav-link {{ Request::is('fixed-assets/purchase-requests*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt me-1"></i>
                                Purchase Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.purchase-orders.index') }}" class="nav-link {{ Request::is('fixed-assets/purchase-orders*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar me-1"></i>
                                Purchase Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.grn.index') }}" class="nav-link {{ Request::is('fixed-assets/grn*') ? 'active' : '' }}">
                                <i class="fas fa-box-open me-1"></i>
                                Goods Received Notes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fixed-assets.stock-reports.index') }}" class="nav-link">
                                <i class="fas fa-warehouse me-1"></i>
                                Stock Reports
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
        @endrole

        @role('super_admin')
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('activity-logs*') ? '' : 'collapsed' }}"
                    href="{{ route('activity-logs.index') }}">
                    <i class="ri-file-list-3-line"></i>
                    <span data-key="t-dashboards">Activity Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('recycle-bin*') ? 'active' : '' }}"
                    href="{{ route('recycle-bin.index') }}">
                    <i class="ri-delete-bin-7-line"></i>
                    <span data-key="t-recycle-bin">Recycle Bin</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::is('unpaid-students-list', 'paid-students-list', 'students-relation-list', 'reports-attendance', 'reports/comprehensive-attendance', 'reports/daily-operational', 'sibling-report', 'student-concession-report', 'branches-report', 'invoice-status-report', 'transfer-in-out', 'student-promotion-report') ? '' : 'collapsed' }}"
                    href="#sidebarReports" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('unpaid-students-list', 'paid-students-list', 'students-relation-list', 'reports-attendance', 'reports/comprehensive-attendance', 'reports/daily-operational', 'sibling-report', 'student-concession-report', 'branches-report', 'invoice-status-report', 'transfer-in-out', 'student-promotion-report') ? 'true' : 'false' }}"
                    aria-controls="sidebarReports">
                    <i class="ri-file-chart-line"></i>
                    <span data-key="t-dashboards">Reports</span>
                </a>
                <div class="collapse menu-dropdown {{ Request::is('unpaid-students-list', 'paid-students-list', 'students-relation-list', 'reports-attendance', 'reports/comprehensive-attendance', 'reports/daily-operational', 'sibling-report', 'student-concession-report', 'branches-report', 'invoice-status-report', 'transfer-in-out', 'student-promotion-report') ? 'show' : '' }}"
                    id="sidebarReports">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('unpaid-students-list') }}"
                                class="nav-link {{ Request::is('unpaid-students-list') ? 'active' : '' }}"
                                data-key="t-projects">Student Recoverables</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('paid-students-list') }}"
                                class="nav-link {{ Request::is('paid-students-list') ? 'active' : '' }}"
                                data-key="t-projects">Fee Paid</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('students-relation-list') }}"
                                class="nav-link {{ Request::is('students-relation-list') ? 'active' : '' }}"
                                data-key="t-projects">Students Relation</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports-attendance') }}"
                                class="nav-link {{ Request::is('reports-attendance') ? 'active' : '' }}"
                                data-key="t-projects">Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance-report.index') }}"
                                class="nav-link {{ Request::is('reports/comprehensive-attendance') ? 'active' : '' }}"
                                data-key="t-projects">Comprehensive Attendance Report</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('daily-operational-report.index') }}"
                                class="nav-link {{ Request::is('reports/daily-operational') ? 'active' : '' }}"
                                data-key="t-projects">Daily Operational Report</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sibling-report') }}"
                                class="nav-link {{ Request::is('sibling-report') ? 'active' : '' }}"
                                data-key="t-projects">Sibling Report</a>
                        </li>
                        @role('super_admin')
                            <li class="nav-item">
                                <a href="{{ route('student-concession-report') }}"
                                    class="nav-link {{ Request::is('student-concession-report') ? 'active' : '' }}"
                                    data-key="t-projects">Student Concessions</a>
                            </li>
                        @endrole
                        <li class="nav-item">
                            <a href="{{ route('branches-report') }}"
                                class="nav-link {{ Request::is('branches-report') ? 'active' : '' }}"
                                data-key="t-projects">Branches</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('invoice-status-report') }}"
                                class="nav-link {{ Request::is('invoice-status-report') ? 'active' : '' }}"
                                data-key="t-projects">Invoice Statuses</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfer-in-out') }}"
                                class="nav-link {{ Request::is('transfer-in-out') ? 'active' : '' }}"
                                data-key="t-projects">Transfer In/Out</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-promotion-report') }}"
                                class="nav-link {{ Request::is('student-promotion-report') ? 'active' : '' }}"
                                data-key="t-projects">Students Promotions</a>
                        </li>
                        @role('super_admin|human_resource')
                            <li class="nav-item">
                                <a href="{{ route('employee-report') }}"
                                    class="nav-link {{ Request::is('employee-report') ? 'active' : '' }}"
                                    data-key="t-projects">Employees</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('general-ledger-report') }}"
                                    class="nav-link {{ Request::is('general-ledger-report') ? 'active' : '' }}"
                                    data-key="t-projects">General Ledger</a>
                            </li>
                        @endrole
                    </ul>
                </div>
            </li>

            @include('layouts.nav.settings-menu')
        @endrole
        </ul>
        </div>
    </div>
</div>

@php
    $settingsRoutes = [
        'geographic-settings*', 'building-type', 'cities', 'countries', 'classes', 'class-subjects', 'class-groups',
        'companies', 'departments', 'designations', 'fee-', 'application-type', 'leave-types', 'designationLeaveQuota',
        'academic-settings*', 'regions', 'withdrawal-reason', 'withdrawal-cancellation-reason', 'student-transfer-reason',
        'working-day', 'working-shift', 'official-leave-day', 'staff-type', 'branch-working-shift', 'sections', 'states',
        'subject-groups', 'subjects', 'subject-settings*', 'class-subject-settings*', 'system-modules', 'towns',
        'inquiry-type', 'followUpType', 'campusOfficeType', 'tax-settings*', 'paper-types', 'hm-campus-round*',
        'calls-details*', 'repair-maintenance*', 'petty-cash-details*', 'electricity-meter*', 'generator-info*'
    ];
    $isActive = Request::is($settingsRoutes);
@endphp

<li class="nav-item">
    <a class="nav-link menu-link {{ $isActive ? 'active' : 'collapsed' }}"
        href="#sidebarMultilevel" data-bs-toggle="collapse" role="button"
        aria-expanded="{{ $isActive ? 'true' : 'false' }}"
        aria-controls="sidebarMultilevel">
        <i class="ri-settings-2-line"></i> <span data-key="t-multi-level">Setting</span>
    </a>
    <div class="collapse menu-dropdown {{ $isActive ? 'show' : '' }}" id="sidebarMultilevel">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('geographic-settings.index') }}"
                    class="nav-link {{ Request::is('geographic-settings*') ? 'active' : '' }}"
                    data-key="t-geographic">Geographic & Location</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('class-subject-settings.index') }}"
                    class="nav-link {{ Request::is('class-subject-settings*') ? 'active' : '' }}"
                    data-key="t-class-subject">Classes & Subjects</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('class-groups.index') }}"
                    class="nav-link {{ Request::is('class-groups') ? 'active' : '' }}"
                    data-key="t-analytics">School Types</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('companies.index') }}"
                    class="nav-link {{ Request::is('companies') ? 'active' : '' }}"
                    data-key="t-analytics">Companies</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('departments.index') }}"
                    class="nav-link {{ Request::is('departments') ? 'active' : '' }}"
                    data-key="t-projects">Departments</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('designations.index') }}"
                    class="nav-link {{ Request::is('designations') ? 'active' : '' }}"
                    data-key="t-designation">Designations</a>
            </li>
            <li class="nav-item">
                <a href="#sidebarAccount" class="nav-link {{ Request::is('fee-') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('fee-') ? 'true' : 'false' }}"
                    aria-controls="sidebarAccount" data-key="t-fee-level">Fee</a>
                <div class="collapse menu-dropdown {{ Request::is('fee-') ? 'show' : '' }}"
                    id="sidebarAccount">
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
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#sidebarLeaves"
                    class="nav-link {{ Request::is('application-type', 'leave-types', 'designationLeaveQuota') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('application-type', 'leave-types', 'designationLeaveQuota') ? 'true' : 'false' }}"
                    aria-controls="sidebarLeaves" data-key="t-fee-level">Leaves</a>
                <div class="collapse menu-dropdown {{ Request::is('application-type', 'leave-types', 'designationLeaveQuota') ? 'show' : '' }}"
                    id="sidebarLeaves">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('application-type.index') }}"
                                class="nav-link {{ Request::is('application-type') ? 'active' : '' }}"
                                data-key="t-fee-level">Application Types</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leave-types.index') }}"
                                class="nav-link {{ Request::is('leave-types') ? 'active' : '' }}"
                                data-key="t-fee-level">Leave Types</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('designationLeaveQuota.index') }}"
                                class="nav-link {{ Request::is('designationLeaveQuota') ? 'active' : '' }}"
                                data-key="t-fee-level">Designation Leave Quotas</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('academic-settings.index') }}"
                    class="nav-link {{ Request::is('academic-settings*') ? 'active' : '' }}"
                    data-key="t-projects">Academic Settings</a>
            </li>
            <li class="nav-item">
                <a href="#sidebarStudentsMulti"
                    class="nav-link {{ Request::is('withdrawal-reason', 'withdrawal-cancellation-reason', 'student-transfer-reason') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('withdrawal-reason', 'withdrawal-cancellation-reason', 'student-transfer-reason') ? 'true' : 'false' }}"
                    aria-controls="sidebarStudentsMulti" data-key="t-students-level">Students</a>
                <div class="collapse menu-dropdown {{ Request::is('withdrawal-reason', 'withdrawal-cancellation-reason', 'student-transfer-reason') ? 'show' : '' }}"
                    id="sidebarStudentsMulti">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('withdrawal-reason.index') }}"
                                class="nav-link {{ Request::is('withdrawal-reason') ? 'active' : '' }}"
                                data-key="t-students-level">Withdrawal Reasons</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('withdrawal-cancellation-reason.index') }}"
                                class="nav-link {{ Request::is('withdrawal-cancellation-reason') ? 'active' : '' }}"
                                data-key="t-students-level">Cancellation Reason</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-transfer-reason.index') }}"
                                class="nav-link {{ Request::is('student-transfer-reason') ? 'active' : '' }}"
                                data-key="t-students-level">Transfer Reason</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#sidebarShifts"
                    class="nav-link {{ Request::is('working-day', 'working-shift', 'official-leave-day') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('working-day', 'working-shift', 'official-leave-day') ? 'true' : 'false' }}"
                    aria-controls="sidebarShifts" data-key="t-shift-level">Shifts</a>
                <div class="collapse menu-dropdown {{ Request::is('working-day', 'working-shift', 'official-leave-day') ? 'show' : '' }}"
                    id="sidebarShifts">
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
            <li class="nav-item">
                <a href="#sidebarBranchShifts"
                    class="nav-link {{ Request::is('staff-type', 'branch-working-shift') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Request::is('staff-type', 'branch-working-shift') ? 'true' : 'false' }}"
                    aria-controls="sidebarBranchShifts" data-key="t-shift-level">Branch Schedule</a>
                <div class="collapse menu-dropdown {{ Request::is('staff-type', 'branch-working-shift') ? 'show' : '' }}"
                    id="sidebarBranchShifts">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('staff-type.index') }}"
                                class="nav-link {{ Request::is('staff-type') ? 'active' : '' }}"
                                data-key="t-branch-shift-level">Staff Types</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch-working-shift.index') }}"
                                class="nav-link {{ Request::is('branch-working-shift') ? 'active' : '' }}"
                                data-key="t-branch-shift-level">Schedule Shifts</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('sections.index') }}"
                    class="nav-link {{ Request::is('sections') ? 'active' : '' }}"
                    data-key="t-projects">Sections</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('system-modules.index') }}"
                    class="nav-link {{ Request::is('system-modules') ? 'active' : '' }}"
                    data-key="t-ecommerce">System Modules</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('inquiry-type.index') }}"
                    class="nav-link {{ Request::is('inquiry-type') ? 'active' : '' }}"
                    data-key="t-projects">Admission Inquiry Types</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('followUpType.index') }}"
                    class="nav-link {{ Request::is('followUpType') ? 'active' : '' }}"
                    data-key="t-projects">Admission Follow Up Types</a>
            </li>
            @permission('list-campus-type')
                <li class="nav-item">
                    <a href="{{ route('campusOfficeType.index') }}"
                        class="nav-link {{ Request::is('campusOfficeType') ? 'active' : '' }}"
                        data-key="t-projects">Campus/Office Types</a>
                </li>
            @endpermission
            <li class="nav-item">
                <a href="{{ route('tax-settings.index') }}"
                    class="nav-link {{ Request::is('tax-settings*') ? 'active' : '' }}" data-key="t-projects">Tax Settings</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paper-types.index') }}"
                    class="nav-link {{ Request::is('paper-types') ? 'active' : '' }}"
                    data-key="t-projects">Paper Type</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.provident-fund-definitions.index') }}" class="nav-link"
                    data-key="t-projects">
                    Provident Fund Definitions
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.income-tax-slabs.index') }}" class="nav-link"
                    data-key="t-projects">
                    Income Tax Slabs
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.deduction-types.index') }}" class="nav-link"
                    data-key="t-projects">
                    Deduction Types
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('hm-campus-round.index') }}" class="nav-link {{ Request::is('hm-campus-round*') ? 'active' : '' }}"
                    data-key="t-projects">
                    HM Campus Round
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calls-details.index') }}" class="nav-link {{ Request::is('calls-details*') ? 'active' : '' }}"
                    data-key="t-projects">
                    Calls Details Of HM + Admin
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('repair-maintenance.index') }}" class="nav-link {{ Request::is('repair-maintenance*') ? 'active' : '' }}"
                    data-key="t-projects">
                    Repair & Maintenance
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('petty-cash-details.index') }}" class="nav-link {{ Request::is('petty-cash-details*') ? 'active' : '' }}"
                    data-key="t-projects">
                    Petty Cash Details
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('electricity-meter.index') }}" class="nav-link {{ Request::is('electricity-meter*') ? 'active' : '' }}"
                    data-key="t-projects">
                    Daily Electricity Meter Reading
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('generator-info.index') }}" class="nav-link {{ Request::is('generator-info*') ? 'active' : '' }}"
                    data-key="t-projects">
                    Daily Generator Information
                </a>
            </li>
        </ul>
    </div>
</li>


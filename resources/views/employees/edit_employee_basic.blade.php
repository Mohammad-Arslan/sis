@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employee List</a></li>
        <li class="breadcrumb-item active">Edit Employee</li>
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Employees </h4>

                </div>

                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs mb-3" role="tablist">
                        @permission('edit-employee-basic-info')
                        <li class="nav-item">
                            <a class="nav-link basic {{ request()->query('tab') == 'basic_info' ? 'active' : '' }}"
                               href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=basic_info' : '#' }}" aria-selected="false">
                                <i class="ri-user-line align-middle me-1"></i> Basic Information
                            </a>
                        </li>
                        @endpermission
                        @permission('edit-employee-service-info')
                        <li class="nav-item">
                            <a class="nav-link service {{ request()->query('tab') == 'service_info' ? 'active' : '' }}"
                               href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=service_info' : '#' }}" aria-selected="false">
                                <i class="ri-briefcase-5-line me-1 align-middle"></i> Service Info
                            </a>
                        </li>
                        @endpermission
                        @permission('edit-employee-company-info')
                        <li class="nav-item">
                            <a class="nav-link company {{ request()->query('tab') == 'company_info' ? 'active' : '' }}"
                                href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=company_info' : '#' }}" aria-selected="false">
                                <i class="ri-home-5-line me-1 align-middle"></i> Company Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link company {{ request()->query('tab') == 'dependent_info' ? 'active' : '' }}"
                                href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=dependent_info' : '#' }}" aria-selected="false">
                                <i class="ri-team-line me-1 align-middle"></i> Dependent Info
                            </a>
                        </li>
                        @endpermission
                        @if ($employee[0]->designation && $employee[0]->designation->designation_name == 'Teacher')
                            <li class="nav-item">
                                <a class="nav-link company {{ request()->query('tab') == 'classes' ? 'active' : '' }}"
                                    href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=classes' : '#' }}" aria-selected="false">
                                    <i class="ri-home-5-line me-1 align-middle"></i> Teacher Classes
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link company {{ request()->query('tab') == 'working_shifts' ? 'active' : '' }}"
                               href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=working_shifts' : '#' }}" aria-selected="false">
                                <i class="ri-briefcase-line me-1 align-middle"></i> Working Shifts
                            </a>
                        </li>
                        @permission('edit-employee-official-leave')
                        <li class="nav-item">
                            <a class="nav-link company {{ request()->query('tab') == 'official_leaves' ? 'active' : '' }}"
                               href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=official_leaves' : '#' }}" aria-selected="false">
                                <i class="ri-briefcase-line me-1 align-middle"></i> National holidays
                            </a>
                        </li>
                        @endpermission
                        <li class="nav-item">
                            <a class="nav-link company {{ request()->query('tab') == 'attendance' ? 'active' : '' }}"
                               href="{{ (isset($employee)) ? '/edit-employee/'.$employee[0]->id.'?tab=attendance' : '#' }}" aria-selected="false">
                                <i class="ri-home-5-line me-1 align-middle"></i> Attendance
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        @permission('edit-employee-basic-info')
                        <div class="tab-pane {{ request()->query('tab') == 'basic_info' ? 'active' : '' }}" id="nav-border-justified-basic" role="tabpanel">
                            @include('employees.edit_basic_info_form')
                        </div>
                        @endpermission
                        @permission('edit-employee-service-info')
                        <div class="tab-pane {{ request()->query('tab') == 'service_info' ? 'active' : '' }}" id="nav-border-justified-service" role="tabpanel">
                            @include('employees.edit_service_info_form')
                        </div>
                        @endpermission
                        @permission('edit-employee-company-info')
                        <div class="tab-pane {{ request()->query('tab') == 'company_info' ? 'active' : '' }}" id="nav-border-justified-company" role="tabpanel">
                            @include('employees.edit_company_info_form')
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'dependent_info' ? 'active' : '' }}" id="nav-border-justified-dependent" role="tabpanel">
                            @include('employees.employee_dependents')
                        </div>
                        @endpermission
                        @if ($employee[0]->designation && $employee[0]->designation->designation_name == 'Teacher')
                            <div class="tab-pane {{ request()->query('tab') == 'classes' ? 'active' : '' }}" id="nav-border-justified-classes" role="tabpanel">
                                @include('employees.teacher_classes')
                            </div>
                        @endif

                        <div class="tab-pane {{ request()->query('tab') == 'working_shifts' ? 'active' : '' }}" id="nav-border-justified-shifts" role="tabpanel">
                            @include('employees.employee_working_shifts', ['employee_id' => $employee[0]->id,'working_days' => $working_days,
                            'working_shifts' => $working_shifts])
                        </div>

                        @permission('edit-employee-official-leave')
                        <div class="tab-pane {{ request()->query('tab') == 'official_leaves' ? 'active' : '' }}" id="nav-border-justified-leaves" role="tabpanel">
                            @include('employees.employee_official_leaves', ['employee_id' => $employee[0]->id,'working_days' => $working_days,
                            'official_leaves' => $official_leaves])
                        </div>
                        @endpermission

                        <div class="tab-pane {{ request()->query('tab') == 'attendance' ? 'active' : '' }}" id="nav-border-justified-classes" role="tabpanel">
                            @include('employees.attendance')
                        </div>

                    </div>
                </div>
            </div>
        </div>


        @endsection


        @push('header_scripts')


        @endpush

        @push('footer_scripts')

            <script type="text/javascript">
            //#confirm_date, #regular_date
                $(document).ready(function () {
                    $('#date_of_birth, #dependent_dob, #date_of_marriage').flatpickr({
                        dateFormat: 'Y-m-d',
                        altFormat: 'Y-m-d',
                        maxDate: 'today'
                    })
                    $('#cnic_expiry, #expiry_date').flatpickr({
                        dateFormat: 'Y-m-d',
                        altFormat: 'Y-m-d',
                        minDate: 'today'
                    })
                });
                function GetReportingManager(designation_id,employee_id)
                {
                    var branch_id = $("#branchId").val();
                    var department_id = $("#departmentId").val();
                    var data = {
                        'branch_id' : branch_id,
                        'department_id' : department_id,
                        'designation_id' : designation_id,
                        'employee_id' : employee_id
                    }
                    //alert(data.branch_id);
                    $.ajax({
                            type:'POST',
                            url:'/get-reporting-manager',
                            data: data,
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        success:function(data) {
                            $('#reportingTo').html('');
                            $('#reportingTo').html(data);
                        }
                    });
                }

            </script>

    @endpush

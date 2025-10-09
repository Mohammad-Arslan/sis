@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employee List</a></li>
        <li class="breadcrumb-item active">Create Employee</li>
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add New Employee</h4>
                </div>

                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs mb-3" role="tablist">
                        @permission('add-employee-basic')
                            <li class="nav-item">
                                <a class="nav-link basic  {{ request()->query('tab') == 'basic_info' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-basic" role="tab"
                                    aria-selected="false">
                                    <i class="ri-user-line align-middle me-1"></i> Basic Information
                                </a>
                            </li>
                        @endpermission
                        @permission('add-employee-service-info')
                            <li class="nav-item">
                                <a class="nav-link service {{ request()->query('tab') == 'service_info' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-service" role="tab"
                                    aria-selected="false">
                                    <i class="ri-briefcase-5-line me-1 align-middle"></i> Service Info
                                </a>
                            </li>
                        @endpermission
                        @permission('add-employee-company-info')
                            <li class="nav-item">
                                <a class="nav-link company {{ request()->query('tab') == 'company_info' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-company" role="tab"
                                    aria-selected="false">
                                    <i class="ri-home-5-line me-1 align-middle"></i> Company Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link dependent {{ request()->query('tab') == 'dependent_info' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-dependent" role="tab"
                                    aria-selected="false">
                                    <i class="ri-team-line me-1 align-middle"></i> Dependent Info
                                </a>
                            </li>
                        @endpermission
                        @permission('add-employee-shifts')
                            <li class="nav-item">
                                <a class="nav-link shifts {{ request()->query('tab') == 'working_shifts' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-shifts" role="tab"
                                    aria-selected="false">
                                    <i class="ri-briefcase-line me-1 align-middle"></i> Working Shifts
                                </a>
                            </li>
                        @endpermission
                        @permission('add-employee-official-leave')
                            <li class="nav-item">
                                <a class="nav-link leaves {{ request()->query('tab') == 'official_leaves' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#nav-border-justified-leaves" role="tab"
                                    aria-selected="false">
                                    <i class="ri-briefcase-line me-1 align-middle"></i> National Holidays
                                </a>
                            </li>
                        @endpermission
                    </ul>
                    <div class="tab-content">
                        @permission('add-employee-basic')
                            <div class="tab-pane {{ request()->query('tab') == 'basic_info' ? 'active' : '' }}"
                                id="nav-border-justified-basic" role="tabpanel">
                                @include('employees.basic_info_form')
                            </div>
                        @endpermission
                        @permission('add-employee-service-info')
                            <div class="tab-pane {{ request()->query('tab') == 'service_info' ? 'active' : '' }}"
                                id="nav-border-justified-service" role="tabpanel">
                                @include('employees.service_info_form')
                            </div>
                        @endpermission
                        @permission('add-employee-company-info')
                            <div class="tab-pane {{ request()->query('tab') == 'company_info' ? 'active' : '' }}"
                                id="nav-border-justified-company" role="tabpanel">
                                @include('employees.company_info_form')
                            </div>
                            <div class="tab-pane {{ request()->query('tab') == 'dependent_info' ? 'active' : '' }}"
                                id="nav-border-justified-dependent" role="tabpanel">
                                @include('employees.employee_dependents')
                            </div>
                        @endpermission
                        @permission('add-employee-shifts')
                            <div class="tab-pane {{ request()->query('tab') == 'working_shifts' ? 'active' : '' }}"
                                id="nav-border-justified-shifts" role="tabpanel">
                                @include('employees.employee_working_shifts', [
                                    'working_days' => $working_days,
                                    'working_shifts' => $working_shifts,
                                ])
                            </div>
                        @endpermission
                        @permission('add-employee-official-leave')
                            <div class="tab-pane {{ request()->query('tab') == 'official_leaves' ? 'active' : '' }}"
                                id="nav-border-justified-leaves" role="tabpanel">
                                @include('employees.employee_official_leaves', [
                                    'working_days' => $working_days,
                                    'official_leaves' => $official_leaves,
                                ])
                            </div>
                        @endpermission
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
            $(document).ready(function() {
                $('#date_of_birth, #dependent_dob, #date_of_marriage').flatpickr({
                    dateFormat: 'Y-m-d',
                    altFormat: 'Y-m-d',
                    maxDate: 'today'
                })
                $('#cnic_expiry,  #probation_end_date, #expiry_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    altFormat: 'Y-m-d',
                    minDate: 'today'
                })
            });

            function GetReportingManager(designation_id, employee_id) {
                var branch_id = $("#branchId").val();
                var department_id = $("#departmentId").val();
                var data = {
                    'branch_id': branch_id,
                    'department_id': department_id,
                    'designation_id': designation_id,
                    'employee_id': employee_id
                }
                //alert(data.branch_id);
                $.ajax({
                    type: 'POST',
                    url: '/get-reporting-manager',
                    data: data,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $('#reportingTo').html('');
                        $('#reportingTo').html(data);
                    }
                });
            }
        </script>
    @endpush

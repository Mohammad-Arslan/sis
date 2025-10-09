@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Employee Report</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('employee-report') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                        <a href="{{ route('employee-export') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="company_id" name="company_id">
                                    <option value="">Please select</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                                <label for="company_id" class="form-label">Company</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="region_id" name="region_id">
                                    <option value="">Please select</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">Region</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id" name="state_id">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">Province</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="department_id" name="department_id">
                                    <option value="">Please select</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                                <label for="department_id" class="form-label">Department</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="designation_id" name="designation_id">
                                    <option value="">Please select</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                    @endforeach
                                </select>
                                <label for="designation_id" class="form-label">Designation</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="job_status" name="job_status">
                                    <option value="">Please select</option>
                                    <option value="regular">Regular</option>
                                    <option value="probation">Probation</option>
                                    <option value="left">Left</option>
                                    <option value="adhoc">Adhoc</option>
                                    <option value="contractual">Contractual</option>
                                </select>
                                <label for="job_status" class="form-label">Job Status</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="marital_status" name="marital_status">
                                    <option value="">Please select</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    
                                </select>
                                <label for="marital_status" class="form-label">Marital Status</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="searchTerm" type="text" placeholder="Search by name, employee ID, email, or mobile..." class="form-control">
                                <label for="searchTerm" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>

                    <table id="employee-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Branch</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Job Status</th>
                                <th>Marital Status</th>
                                <th>Hiring Date</th>
                                <th>Total Service</th>
                                <th>Nationality</th>
                                <th>Religion</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Employee ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Branch</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Job Status</th>
                                <th>Marital Status</th>
                                <th>Hiring Date</th>
                                <th>Total Service</th>
                                <th>Nationality</th>
                                <th>Religion</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
<style>
    /* Custom badge styles for job status column */
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        color: #fff !important;
    }
    
    .badge-success {
        background-color: #28a745 !important;
        color: #fff !important;
    }
    
    .badge-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .badge-danger {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    
    .badge-info {
        background-color: #17a2b8 !important;
        color: #fff !important;
    }
    
    .badge-primary {
        background-color: #007bff !important;
        color: #fff !important;
    }
    
    .badge-secondary {
        background-color: #6c757d !important;
        color: #fff !important;
    }
    
    /* Ensure badges are visible in DataTables */
    #employee-data-table .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
        border-radius: 0.375rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    /* Force badge visibility */
    #employee-data-table td .badge {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Additional Bootstrap color variables for better compatibility */
    :root {
        --bs-success: #28a745;
        --bs-warning: #ffc107;
        --bs-danger: #dc3545;
        --bs-info: #17a2b8;
        --bs-primary: #007bff;
        --bs-secondary: #6c757d;
    }
    
    /* Override any conflicting styles */
    .table td .badge {
        margin: 0;
        border: none;
        box-shadow: none;
    }
    
    /* Ensure proper contrast for warning badge */
    .badge-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
        border: 1px solid #ffc107;
    }
</style>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#employee-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                },
                ajax: {
                    url: "{{ route('employee-report') }}",
                    data: function(d) {
                        d.company_id = $('#company_id').val();
                        d.region_id = $('#region_id').val();
                        d.state_id = $('#state_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.department_id = $('#department_id').val();
                        d.designation_id = $('#designation_id').val();
                        d.job_status = $('#job_status').val();
                        d.marital_status = $('#marital_status').val();
                        d.searchTerm = $('#searchTerm').val();
                    }
                },
                columns: [
                    {
                        data: 'employee_id',
                        name: 'employee_id',
                        width: "8%"
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        width: "12%"
                    },
                    {
                        data: 'email',
                        name: 'email',
                        width: "12%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'region_name',
                        name: 'region_name',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'state_name',
                        name: 'state_name',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'designation_name',
                        name: 'designation_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'job_status',
                        name: 'job_status',
                        width: "8%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-' && data !== null && data !== undefined) {
                                var badgeClass = 'badge-secondary';
                                var statusText = data.toString().trim();
                                
                                switch(statusText.toLowerCase()) {
                                    case 'regular':
                                        badgeClass = 'badge-success';
                                        break;
                                    case 'probation':
                                        badgeClass = 'badge-warning';
                                        break;
                                    case 'left':
                                        badgeClass = 'badge-danger';
                                        break;
                                    case 'adhoc':
                                        badgeClass = 'badge-info';
                                        break;
                                    case 'contractual':
                                        badgeClass = 'badge-primary';
                                        break;
                                    default:
                                        badgeClass = 'badge-secondary';
                                }
                                
                                var capitalizedText = statusText.charAt(0).toUpperCase() + statusText.slice(1).toLowerCase();
                                var badgeHtml = '<span class="badge ' + badgeClass + '">' + capitalizedText + '</span>';
                                
                                return badgeHtml;
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'marital_status',
                        name: 'marital_status',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'hiring_date',
                        name: 'hiring_date',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'total_service',
                        name: 'total_service',
                        width: "10%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-' && data !== null && data !== undefined) {
                                return '<span class="badge badge-info">' + data + '</span>';
                            }
                            return '<i>-</i>';
                        }
                    },
                    {
                        data: 'nationality',
                        name: 'nationality',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'religion',
                        name: 'religion',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    }
                ],
                order: [
                    [0, "desc"]
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#employee-data-table').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#searchTerm', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 2 || value.length == 0) {
                $('#employee-data-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush

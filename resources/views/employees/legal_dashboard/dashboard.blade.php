@extends('layouts.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .custom-list-group {
            list-style: none;
            padding: 0;
            font-family: "Courier New", monospace;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #333;
            /* Darker text color */
        }

        .values {
            display: flex;
            justify-content: space-between;
            padding: 8px;
        }

        .label {
            width: 25%;
            text-align: center;
            font-weight: bold;
            color: #555;
            /* Slightly darker text color */
        }

        .value {
            width: 25%;
            text-align: center;
            font-weight: normal;
            /* Regular font weight */
            color: #000;
            /* Black text color */
        }

        .table-style tr th:nth-child(2) {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style tr td:nth-child(2) {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style1 tr th:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style1 tr td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        {{-- onboarding Details Table start --}}
        <div class="row">
            <div class="col-xl-12">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show"
                        role="alert">
                        <i class="ri-error-warning-line label-icon"></i><strong>Error</strong>
                        - {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title flex-grow-1 mb-0">Onboarding Applications</h4>

                    </div><!-- end cardheader -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter load-select form-select" id="franchise_state_id"
                                        name="franchise_state_id" placeholder="state" data-target="city_id"
                                        data-url="{{ route('list-cities') }}" aria-label="State select" required>
                                        <option value="">Please select</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="franchise_state_id" class="form-label">State/Province</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                        id="city" name="city_id" aria-label="City select" required>
                                        <option value="">Please select</option>
                                        @if (old('city_id'))
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->city_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="city" class="form-label">City</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="school_type" name="school_type"
                                        aria-label="Agreement select">
                                        <option value="">Please select</option>
                                        @foreach ($school_type as $school_type)
                                            <option value="{{ $school_type->id }}">
                                                {{ $school_type->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="school_type" class="form-label">School Type</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="status_list" name="status">
                                        <option value=" ">Please select</option>
                                        {{-- <option value="pending">Pending</option> --}}
                                        <option value="approved">Approved</option>
                                        <option value="not_approved">Rejected</option>
                                    </select>
                                    <label for="status" class="form-label">Status</label>
                                </div>
                            </div>
                            {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput_onboard" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput_onboard" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                        </div>
                        <div class="table-responsive table-card table-style">
                            <table id="qa-onboarding-detail-table" class="table table-nowrap table-centered align-middle">
                                <thead class="bg-light text-muted">
                                    <tr>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <th>Proposed School Name</th>
                                        <th>School Type</th>
                                        <th>Agreement Type</th>
                                        <th>Total Franchise Fee</th>
                                        <th>Received Amount</th>
                                        <th>Agreement Date</th>
                                        <th>Operational Date</th>
                                        <th>Status</th>
                                        <th>DD Review / Approval</th>
                                        <th>QA / IASF Report</th>
                                    </tr>
                                    </tr><!-- end tr -->
                                </thead><!-- thead -->
                                <tbody>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <th>Proposed School Name</th>
                                        <th>School Type</th>
                                        <th>Agreement Type</th>
                                        <th>Total Franchise Fee</th>
                                        <th>Received Amount</th>
                                        <th>Agreement Date</th>
                                        <th>Operational Date</th>
                                        <th>Status</th>
                                        <th>DD Review / Approval</th>
                                        <th>QA / IASF Report</th>
                                    </tr>
                                </tfoot>
                            </table><!-- end table -->
                        </div>

                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
        {{-- onboarding Details Table End --}}

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        @if (isset(request()->req) && request()->req == 'me' && !isSuperAdmin()) Requested
                        @else
                            My @endif Visits List
                    </h4>
                    {{-- <div class="flex-shrink-0">
                    <!-- Buttons with Label -->
                    <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                            class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                    <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                            class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                </div> --}}
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        @if (isSuperAdmin() || (isset(request()->req) && request()->req == 'me'))
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="s_user_id" name="s_user_id"
                                        aria-label="Employee select">
                                        <option value="">Please select</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->user_id }}">
                                                {{ $employee->user->name . ' [' . $employee->department->department_name . ']' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="employee" class="form-label">Employee</label>

                                </div>
                            </div>
                        @endif
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_campus_office_id" name="s_campus_office_id"
                                    aria-label="Campus Office select">
                                    <option value="">Please select</option>
                                    @foreach ($campus_types as $campus_type)
                                        <option value="{{ $campus_type->id }}">{{ $campus_type->type }}</option>
                                    @endforeach
                                </select>
                                <label for="s_campus_office_id" class="form-label">Campus/Office Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_branch_id" name="s_branch_id"
                                    aria-label="Branch select">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="s_branch_id" class="form-label">Campus Name</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_from_city_id" name="s_from_city_id"
                                    aria-label="City from select">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_from_city_id" class="form-label">From Location</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_to_city_id" name="s_to_city_id"
                                    aria-label="City select">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_to_city_id" class="form-label">To Location</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_total_duration" name="s_total_duration"
                                    aria-label="Duration select">
                                    <option value="">Please select</option>
                                    <option value="0.25 Day">0.25 Day</option>
                                    <option value="0.5 Day">0.5 Day</option>
                                    <option value="1 Day">1 Day</option>
                                    <option value="2 Days">2 Days</option>
                                    <option value="3 Days">3 Days</option>
                                    <option value="4 Days">4 Days</option>
                                    <option value="5 Days">5 Days</option>
                                </select>
                                <label for="s_total_duration" class="form-label">No. of Hours / Days</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_approval_status" name="s_approval_status"
                                    aria-label="Visit Status Select">
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approve">Approved</option>
                                    <option value="cancel">Cancelled</option>
                                </select>
                                <label for="s_approval_status" class="form-label">Visit Status</label>
                            </div>
                        </div>
                        {{-- @role('super_admin')
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    @endrole --}}
                    </div>
                    <table id="own-visits-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Name</th>
                                <th>Department</th>
                                <th>Campus/Office Type</th>
                                <th>Campus Name</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>No. Of Hours / Days</th>
                                <th>Travel On</th>
                                <th>Return On</th>
                                <th>Travel Mode</th>
                                <th style="width: 20em;">Purpose</th>
                                <th style="width: 20em;">Remarks</th>
                                <th>Status</th>
                                <th>Approval Auth</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Name</th>
                                <th>Department</th>
                                <th>Campus/Office Type</th>
                                <th>Campus Name</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>No. Of Hours / Days</th>
                                <th>Travel On</th>
                                <th>Return On</th>
                                <th>Travel Mode</th>
                                <th>Purpose</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Approval Auth</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
    <script src="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/dom-autoscroller/dom-autoscroller.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">

    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            /** Mark in*/
            $(document).on('click', '.mark_attendance_in', function(e) {
                e.preventDefault();
                var today = new Date();
                let url = $(this).attr('data-route');
                let employee_id = $(this).attr('data-employee-id');
                let time_in = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                let attendance_type = 1;
                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to mark your attendance?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                employee_id,
                                time_in,
                                attendance_type
                            },
                            success: function(response) {
                                Swal.fire('Done!', '', 'success')
                                location.reload();
                            }
                        })
                    }
                })
            });
            $(document).on('click', '.mark_attendance_out', function(e) {
                e.preventDefault();
                var today = new Date();
                let url = $(this).attr('data-route');
                let id = $(this).attr('data-id');
                let time_out = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to mark out your attendance?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id,
                                time_out
                            },
                            success: function(response) {
                                Swal.fire('Done!', '', 'success')
                                location.reload();
                            }
                        })
                    }
                })
            });
        });
    </script>
    <script>

        $(document).ready(function() {
            $('#qa-onboarding-detail-table').DataTable({
                searching: true,
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
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                },
                ajax: {
                    url: "{{ route('on-boarding-list') }}",
                    data: function(d) {
                        d.state_id = $('#franchise_state_id').val();
                        d.city_id = $('#city').val();
                        d.school_type = $('#school_type').val();
                        d.status = $('#status_list').val();
                    }
                },
                columns: [
                    /*{data: 'id', name: 'id', width: "5%",orderable: true},
                    {data: 'company.company_name', name: 'company.company_name'},*/
                    {
                        data: 'full_name',
                        name: 'full_name',
                        width: "10%",
                    },
                    {
                        data: 'purposed_school_name',
                        name: 'purposed_school_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'school_type',
                        name: 'school_type',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'agreement_type',
                        name: 'agreement_type',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'total_franchise_fee',
                        name: 'total_franchise_fee',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'amount_received',
                        name: 'amount_received',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'agreement_date',
                        name: 'agreement_date',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'operational_date',
                        name: 'operational_date',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'statuses',
                        name: 'statuses',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'franchise_application_dd_status',
                        name: 'franchise_application_dd_status',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'reports_action',
                        name: 'reports_action',
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    }
                ],
                order: [
                    [0, "desc"]
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#qa-onboarding-detail-table').DataTable().ajax.reload(null, false);
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#own-visits-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },

                ajax: {
                    url: "{{ route('own-visit') }}",
                    data: function(d) {
                        //d.s_user_id = $('#s_user_id').val();
                        d.campus_office_id = $('#s_campus_office_id').val();
                        d.branch_id = $('#s_branch_id').val();
                        d.from_city_id = $('#s_from_city_id').val();
                        d.to_city_id = $('#s_to_city_id').val();
                        d.total_duration = $('#s_total_duration').val();
                        d.approval_status = $('#s_approval_status').val();
                    }
                },
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    //     width: "5%"
                    // },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'campus_office',
                        name: 'campus_office'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'city_from',
                        name: 'city_from'
                    },
                    {
                        data: 'city_to',
                        name: 'city_to'
                    },
                    {
                        data: 'total_duration',
                        name: 'total_duration'
                    },
                    {
                        data: 'travel_on',
                        name: 'travel_on'
                    },
                    {
                        data: 'return_on',
                        name: 'return_on'
                    },
                    {
                        data: 'travel_mode',
                        name: 'travel_mode'
                    },
                    {
                        data: 'purpose',
                        name: 'purpose',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 150px;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 40em;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'approval_status',
                        // name: 'approval_status',
                        render: function(data, type, row) {
                            if (data == 'pending') {
                                return '<span class="badge bg-warning text-bold">Pending</span>';
                            } else if (data == 'approve') {
                                return '<span class="badge bg-success text-bold">Approved</span>';
                            } else {
                                return '<span class="badge bg-danger text-bold">Cancelled</span>';
                            }

                        }
                    },
                    {
                        data: 'approval_auth',
                        name: 'approval_auth',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    },
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#own-visits-data-table').DataTable().ajax.reload(null, false).page('first');
        });

    </script>
@endpush

@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Student Withdrawal List</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Withdrawal List</h4>
                    <!-- <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                            <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                        </div>
                                    </div> -->

                    <div class="flex-shrink-0">
                        @permission('add-student')
                            <a href="{{ route('student-withdrawal.create') }}" class="btn btn-success-new btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create Withdrawal
                            </a>
                        @endpermission

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        @if (isSuperAdmin() || isHeadOfficeEmp())
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                        @endforeach
                                    </select>
                                    <label for="designation_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id" placeholder="Class">
                                    <option value="">Please select</option>
                                    @if (!isSuperAdmin() && !isHeadOfficeEmp()){
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->com_classes->id }}">
                                                {{ $class->com_classes->class_name }} </option>
                                        @endforeach
                                    @else
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id"
                                    placeholder="Section">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status-list" name="status">
                                    <option value="all">Status</option>
                                    <option value="on_roll">On Roll</option>
                                    <option value="registered">Registered</option>
                                    <option value="processing">Processing</option>
                                    <option value="left">Left</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="student-search" type="text" placeholder="Search.." class="form-control">
                                <label for="student-search" class="form-label">Search...</label>
                            </div>
                        </div>
                </div>
                <table id="withdrawal-form-list"
                    class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Region</th>
                            <th>Branch Code</th>
                            <th>Branch Name</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Class / Section</th>
                            <th>Security Amount</th>
                            <th>Guardian Name</th>
                            <th>Guardian Number</th>
                            <th>Approved By</th>
                            <th>Approved Date</th>
                            <th>Application Date</th>
                            <th>Withdrawal WEF</th>
                            <th>Last Day</th>
                            <th>Last invoice paid</th>
                            <th>Clearance Amount</th>
                            <th>Reason</th>
                            <th>Created by</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Application ID</th>
                            <th>Region</th>
                            <th>Branch Code</th>
                            <th>Branch Name</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Class / Section</th>
                            <th>Security Amount</th>
                            <th>Guardian Name</th>
                            <th>Guardian Number</th>
                            <th>Approved By</th>
                            <th>Approved Date</th>
                            <th>Application Date</th>
                            <th>Withdrawal WEF</th>
                            <th>Last Day</th>
                            <th>Last invoice paid</th>
                            <th>Clearance Amount</th>
                            <th>Reason</th>
                            <th>Created by</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
</div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#withdrawal-form-list').DataTable({
            searching: false,
            retrieve: true,
            serverSide: true,
            processing: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                searchPlaceholder: "Search..."
            },
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            scrollX: true,
            ajax: {
                url: "{{ route('student-withdrawal.index') }}",
                data: function(d) {
                    //d.gender = $('#gender').val();
                    d.section_id = $('#section_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.class_id = $('#class_id').val();
                    d.status = $('#status-list').val();
                    d.searchName = $('#student-search').val().toLowerCase();
                }
            },
            columns: [{
                    data: 'application_id',
                    name: 'application_id'
                },
                {
                    data: 'region',
                    name: 'region'
                },
                {
                    data: 'branch_code',
                    name: 'branch_code'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name'
                },
                {
                    data: 'student_name',
                    name: 'student_name'
                },
                {
                    data: 'student.roll_no',
                    name: 'student.roll_no'
                },
                {
                    data: 'class_section',
                    name: 'class_section'
                },
                {
                    data: 'security_amount',
                    name: 'security_amount'
                },
                {
                    data: 'guardian.guardian_name',
                    name: 'guardian.guardian_name'
                },
                {
                    data: 'guardian.mobile',
                    name: 'guardian.mobile'
                },
                {
                    data: 'approved_by',
                    name: 'approved_by'
                },
                {
                    data: 'approved_date',
                    name: 'approved_date'
                },
                {
                    data: 'application_date',
                    name: 'application_date'
                },
                {
                    data: 'withdrawal_wef',
                    name: 'withdrawal_wef',
                },
                {
                    data: 'last_day_at',
                    name: 'last_day_at'
                },
                {
                    data: 'last_invoice_paid_at',
                    name: 'last_invoice_paid_at'
                },
                {
                    data: 'clearance_amount',
                    name: 'clearance_amount'
                },
                {
                    data: 'reason.withdrawal_reason',
                    name: 'reason.withdrawal_reason'
                },
                {
                    data: 'created_by',
                    name: 'created_by'
                },
                {
                    "data": "status",

                    render: function(data, type, row) {

                        if (row.student.status == 'on_roll') {
                            return '<span class="badge bg-success">On Roll</span>';
                        } else if (row.student.status == 'registered') {
                            return '<span class="badge bg-primary">Registered</span>';
                        } else if (row.student.status == 'left') {
                            return '<span class="badge bg-warning">Left</span>';
                        } else {
                            return '<span class="badge bg-danger">Processing</span>';
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "5%"
                }
            ]
        });
    });
    $(document).on('change', '.filter', function() {
        $('#withdrawal-form-list').DataTable().ajax.reload(null, false).page('first');
    });

    $(document).on("keyup", '#student-search', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#withdrawal-form-list').DataTable().ajax.reload(null, false);
        }
    });
</script>
@endpush

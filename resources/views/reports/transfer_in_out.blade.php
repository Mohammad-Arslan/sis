@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">Transfer In/Out</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Transfer In/Out</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('transfer-in-out') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                        <a href="{{ route('transfer-in-out-export') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        @if (isSuperAdmin() || isHeadOfficeEmp())
                            <div class="col-md-3 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                        @endforeach
                                    </select>
                                    <label for="branch_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status-list" name="status">
                                    <option value="">Select Status</option>
                                    <option value="APPROVED">APPROVED</option>
                                    <option value="PENDING">PENDING</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="report_order" name="report_order">
                                    <option value="">Please select</option>
                                    <option value="in">Transfer IN</option>
                                    <option value="out">Transfer OUT</option>
                                </select>
                                <label for="report_order" class="form-label">Report Order</label>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="report_type" name="report_type">
                                <option value="">Please select</option>
                                <option value="wef">WEF Date</option>
                                <option value="order">Order Date</option>
                            </select>
                            <label for="report_order" class="form-label">Report Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <div class="filter input-group">
                                <input type="text"
                                    class="form-control @if ($errors->has('start_date')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                    value="{{ old('start_date') }}" name="start_date" id="start_date" required>
                                <label for="start_date" class="form-label">From Date<span class="text-danger">*</span></label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('start_date'))
                                        {{ $errors->first('start_date') }}
                                    @else
                                        From date is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <div class="filter input-group">
                                <input type="text"
                                    class="form-control @if ($errors->has('end_date')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                    value="{{ old('end_date') }}" name="end_date" id="end_date" required>
                                <label for="end_date" class="form-label">To Date<span class="text-danger">*</span></label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('end_date'))
                                        {{ $errors->first('end_date') }}
                                    @else
                                        To date is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <table id="transfer-form-list" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class - Section</th>
                            <th>Order No</th>
                            <th>Order Date</th>
                            <th>WEF Date</th>
                            <th>Transfer Reason</th>
                            <th>Transfer From Branch</th>
                            <th>Transfer To Branch</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Academic Year</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class - Section</th>
                            <th>Order No.</th>
                            <th>Order Date</th>
                            <th>WEF Date</th>
                            <th>Transfer Reason</th>
                            <th>Transfer From Branch</th>
                            <th>Transfer To Branch</th>
                            <th>Joining Date</th>
                            <th>Status</th>
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

        $('#transfer-form-list').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            scrollX: true,
            language: {
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />"
            },
            ajax: {
                url: "{{ route('transfer-in-out') }}",
                data: function(d) {
                    d.academic_year_id = $('#academic_year_id').val();
                    d.region_id = $('#region_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status-list').val();
                    d.report_order = $('#report_order').val();
                    d.report_type = $('#report_type').val();
                }
            },
            columns: [
                {
                    data: 'academic_year',
                    name: 'academic_year',
                    width: '5%'
                },
                {
                    data: 'student.registration_no',
                    name: 'student.registration_no'
                },
                {
                    data: 'student_name',
                    name: 'student_name'
                },
                {
                    data: 'class_section',
                    name: 'class_section'
                },
                {
                    data: 'application_id',
                    name: 'application_id',
                    width: '5%'
                },
                {
                    data: 'request_date',
                    name: 'request_date'
                },
                {
                    data: 'transfer_wef',
                    name: 'transfer_wef'
                },
                {
                    data: 'reason.transfer_reason',
                    name: 'reason.transfer_reason'
                },
                {
                    data: 'from_branch_name',
                    name: 'from_branch_name',
                    width: '5%'
                },
                // {
                //     data: 'to_branch_code',
                //     name: 'to_branch_code'
                // },
                {
                    data: 'to_branch_name',
                    name: 'to_branch_name'
                },
                {
                    data: 'joining_date',
                    name: 'joining_date'
                },
                {
                    "data": "status",
                    width: '5%',
                    render: function(data, type, row) {

                        if (row.status == 'APPROVED') {
                            return '<span class="badge bg-success">APPROVED</span>';
                        } else if (row.status == 'PENDING') {
                            return '<span class="badge bg-primary">PENDING</span>';
                        } else {
                            return '<span class="badge bg-warning">REJECTED</span>';
                        }
                    }
                }
            ]
        });
    });
    $(document).on('change', '.filter', function() {
        $('#transfer-form-list').DataTable().ajax.reload(null, false).page('first');
    });

    $(document).on("keyup", '#student-search', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#transfer-form-list').DataTable().ajax.reload(null, false);
        }
    });
</script>
@endpush

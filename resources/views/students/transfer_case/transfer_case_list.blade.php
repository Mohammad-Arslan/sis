@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Transfer List</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Transfer List</h4>

                    <div class="flex-shrink-0">
                        <a href="{{ route('student-transfer-case.create') }}" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create Transfer Case
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
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
                                    @if (!isSuperAdmin() && !isHeadOfficeEmp())
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
                <table id="transfer-form-list" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>AY</th>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            {{-- <th>From Branch Code</th> --}}
                            <th>From Branch Name</th>
                            {{-- <th>To Branch Code</th> --}}
                            <th>To Branch Name</th>
                            <th>Joining Date</th>
                            <th>WEF Date</th>
                            <th>Transfer Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>AY</th>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            {{-- <th>From Branch Code</th> --}}
                            <th>From Branch Name</th>
                            {{-- <th>To Branch Code</th> --}}
                            <th>To Branch Name</th>
                            <th>Joining Date</th>
                            <th>WEF Date</th>
                            <th>Transfer Reason</th>
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
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
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
                url: "{{ route('student-transfer-case.index') }}",
                data: function(d) {
                    d.academic_year_id = $('#academic_year_id').val();
                    d.section_id = $('#section_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.class_id = $('#class_id').val();
                    d.status = $('#status-list').val();
                    d.searchName = $('#student-search').val().toLowerCase();
                }
            },
            columns: [{
                    data: 'academic_year',
                    name: 'academic_year',
                    width: '5%'
                },
                {
                    data: 'application_id',
                    name: 'application_id',
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
                // {
                //     data: 'from_branch_code',
                //     name: 'from_branch_code'
                // },
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
                    data: 'transfer_wef',
                    name: 'transfer_wef'
                },
                {
                    data: 'reason.transfer_reason',
                    name: 'reason.transfer_reason'
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
        $('#transfer-form-list').DataTable().ajax.reload(null, false).page('first');
    });

    $(document).on("keyup", '#student-search', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#transfer-form-list').DataTable().ajax.reload(null, false);
        }
    });

    // $(document).on('click', '#delete-record', function(e) {
    //     e.preventDefault(); // prevent <a> default behavior

    //     var url = $(this).attr('href');
    //     var table = $(this).data('table');

    //     if (confirm('Are you sure you want to delete this record?')) {
    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             data: {
    //                 _method: 'DELETE',
    //                 _token: "{{ csrf_token() }}"
    //             },
    //             success: function(response) {
    //                 if (response.code == 200) {
    //                     $('#' + table).DataTable().ajax.reload(null, false);
    //                 } else {
    //                     console.log(response.error);
    //                 }
    //             },
    //             error: function(xhr) {
    //                 console.log(xhr.responseText);
    //             }
    //         });
    //     }
    // });
</script>
@endpush

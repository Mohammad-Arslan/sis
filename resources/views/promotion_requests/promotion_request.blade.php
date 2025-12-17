@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Student Promotion Request List</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Promotion Request List</h4>

                    <div class="flex-shrink-0">
                        <a href="{{ route('promotion-requests.create') }}" class="btn btn-primary btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create Bulk Promotions
                        </a>
                        <a href="{{ route('promotion-requests.individual') }}" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create Individual Promotion
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="prev_academic_year_id" name="prev_academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">From Academic Year</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="cur_academic_year" name="cur_academic_year">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">To Academic Year</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="from_branch_id" name="from_branch_id"
                                    placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="designation_id" class="form-label">From Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="from_class_id" name="from_class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>

                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                    @endforeach
                                </select>
                                <label for="class_id" class="form-label">From Class</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="to_branch_id" name="to_branch_id"
                                    placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="designation_id" class="form-label">To Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="to_class_id" name="to_class_id" placeholder="Class">
                                    <option value="">Please select</option>

                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                    @endforeach
                                </select>
                                <label for="class_id" class="form-label">To Class</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="is_promotion" name="is_promotion">
                                    <option value="">Please select a Type</option>
                                    <option value="1">
                                        Promotion
                                    </option>
                                    <option value="0">
                                        Pass-out
                                    </option>
                                </select>
                                <label for="is_promotion" class="form-label">Promotion Type </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="promotion_status" name="status">
                                    <option value="">Please select a status</option>
                                    <option value="PENDING">
                                        PENDING
                                    </option>
                                    <option value="APPROVED">
                                        APPROVED
                                    </option>
                                    <option value="REJECTED">
                                        REJECTED
                                    </option>
                                </select>
                                <label for="status" class="form-label">Promotion Status </label>
                            </div>
                        </div>

                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="student-search" type="text" placeholder="Search.." class="form-control">
                                <label for="student-search" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                    </div>
                    <table id="promotion-request-list"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>From Academic Year</th>
                                <th>From Branch</th>
                                <th>From Class</th>
                                <th>From Section</th>
                                <th>To Academic Year</th>
                                <th>To Branch</th>
                                <th>To Class</th>
                                <th>To Section</th>
                                <th>Type</th>
                                <th>Promotion/Pass-out</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>From Academic Year</th>
                                <th>From Branch</th>
                                <th>From Class</th>
                                <th>From Section</th>
                                <th>To Academic Year</th>
                                <th>To Branch</th>
                                <th>To Class</th>
                                <th>To Section</th>
                                <th>Type</th>
                                <th>Promotion/Pass-out</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="promotion_modal_div"></div>
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

            $('#promotion-request-list').DataTable({
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
                    url: "{{ route('promotion-requests.index') }}",
                    data: function(d) {
                        // d.academic_year_id = $('#academic_year_id').val();
                        d.prev_academic_year = $('#prev_academic_year_id').val();
                        d.cur_academic_year = $('#cur_academic_year_id').val();
                        d.from_branch_id = $('#from_branch_id').val();
                        d.from_class_id = $('#from_class_id').val();
                        d.to_branch_id = $('#to_branch_id').val();
                        d.to_class_id = $('#to_class_id').val();
                        d.status = $('#promotion_status').val();
                        d.is_promotion = $('#is_promotion').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '5%'
                    },
                    {
                        data: 'pre_aca',
                        name: 'pre_aca'
                    },
                    {
                        data: 'pre_branch',
                        name: 'pre_branch',
                        width: '5%'
                    },
                    {
                        data: 'pre_class',
                        name: 'pre_class'
                    },
                    {
                        data: 'pre_sec',
                        name: 'pre_sec'
                    },
                    {
                        data: 'cur_aca',
                        name: 'cur_aca'
                    },
                    {
                        data: 'cur_branch',
                        name: 'cur_branch',
                        width: '5%'
                    },
                    {
                        data: 'cur_class',
                        name: 'cur_class'
                    },
                    {
                        data: 'cur_sec',
                        name: 'cur_sec'
                    },
                    {
                        "data": "type",
                        width: '5%',
                        render: function(data, type, row) {

                            return row.type.toUpperCase();

                        }
                    },
                    {
                        "data": "is_promotion",
                        width: '5%',
                        render: function(data, type, row) {

                            if (row.is_promotion === '1') {
                                return '<span class="badge bg-success">PROMOTION</span>';
                            } else {
                                return '<span class="badge bg-info">PASS-OUT</span>';
                            }
                        }
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

            $(document).on('click', '.show_promotion_requests', function() {
                $.ajax({
                    url: $(this).data('url'),
                    type: "POST",
                    cache: false,
                    data: {},
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('.promotion_modal_div').html(response.html);
                        $('#studentPromotionModal').modal('toggle');
                    },
                    error: function(xhr) {}
                });
            });
        });
        $(document).on('change', '.filter', function() {
            $('#promotion-request-list').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#student-search', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#promotion-request-list').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush

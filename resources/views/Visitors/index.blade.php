@extends('layouts.master')

@section('content')
@include('components.flash_message')
    <div class="row">
        @if((isset(request()->req) && request()->req != 'me') || (isset($req) && $req != 'me') && isHeadOfficeEmp())
            @if (isset($visitDetail))
                @include('Visitors.edit_visit')
            @else
                @permission('add-visit')
                    @include('Visitors.add_visit')
                @endpermission
            @endif
        @endif
        @if(isSuperAdmin())
            @if (isset($visitDetail))
                @include('Visitors.edit_visit')
            @else
                @permission('add-visit')
                    @include('Visitors.add_visit')
                @endpermission
            @endif
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">@if((isset(request()->req) && request()->req =='me') && !isSuperAdmin()) Requested @else My @endif Visits List</h4>
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
                        @if((isSuperAdmin()) || (isset(request()->req) && request()->req =='me'))
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_user_id" name="s_user_id" aria-label="Employee select">
                                    <option value="">Please select</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee->user_id }}">{{ $employee->user->name.' ['.$employee->department->department_name.']' }}</option>
                                    @endforeach
                                </select>
                                <label for="employee" class="form-label">Employee</label>

                            </div>
                        </div>
                        @endif
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_campus_office_id" name="s_campus_office_id" aria-label="Campus Office select">
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
                                <select class="filter form-select" id="s_branch_id" name="s_branch_id" aria-label="Branch select">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old("branch_id") == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_branch_id" class="form-label">Campus Name</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_from_city_id" name="s_from_city_id" aria-label="City from select">
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
                                <select class="filter form-select" id="s_to_city_id" name="s_to_city_id" aria-label="City select">
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
                                <select class="filter form-select" id="s_total_duration" name="s_total_duration" aria-label="Duration select">
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
                                <select class="filter form-select" id="s_approval_status" name="s_approval_status" aria-label="Visit Status Select">
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
                    <table id="visits-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
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


            $('#visits-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                @if(isSuperAdmin())
                    ajax:{
                        url:"{{ route('visitDetail.index') }}",
                        data: function(d) {
                            d.user_id = $('#s_user_id').val();
                            d.campus_office_id = $('#s_campus_office_id').val();
                            d.branch_id = $('#s_branch_id').val();
                            d.from_city_id = $('#s_from_city_id').val();
                            d.to_city_id = $('#s_to_city_id').val();
                            d.total_duration = $('#s_total_duration').val();
                            d.approval_status = $('#s_approval_status').val();

                        }
                    },
                @else
                    ajax: {
                        url:"{{ route('visitDetail.index', ['req' => isset(request()->req) ? request()->req : 'my']) }}",
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
                @endif

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
                        render: function ( data, type, row ) {
                            if(data == 'pending'){
                                return '<span class="badge bg-warning text-bold">Pending</span>';
                            }
                            else if(data == 'approve'){
                                return '<span class="badge bg-success text-bold">Approved</span>';
                            }
                            else
                            {
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
            $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        // @role('super_admin')
        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
        // @endrole
    </script>
@endpush

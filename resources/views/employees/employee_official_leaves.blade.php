{{-- @extends('layouts.master')

@section('content')
@include('components.flash_message')--}}
    <div class="row">

        @if (isset($employeeOfficialLeaveDay))
            @include('employees.edit_employee_official_leaves')
        @else
            @permission('add-employee-official-leave')
                @include('employees.add_employee_official_leaves')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Employee National Holiday List </h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="employee-official-leave-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID </th>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Timings</th>
                                <th>Holiday Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Timings</th>
                                <th>Holiday Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>



@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
           var route = "{{ route('employee-official-leave-day.index', ['employee_id' => '']) }}";
            @if(isset($employee_id))
                route = "{{ route('employee-official-leave-day.index', ['employee_id' => $employee_id]) }}";
            @elseif (isset($employee->id))
                route = "{{ route('employee-official-leave-day.index', ['employee_id' => $employee->id]) }}";
            @endif;
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });
            //alert(route);
            $('#employee-official-leave-data-table').DataTable({
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
                ajax: route,
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'working_day.name',
                        name: 'working_day.name'
                    },
                    {
                        data: 'leave_date',
                        name: 'leave_date'
                    },
                    {
                        data: 'shift_timing',
                        name: 'shift_timing'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
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


    </script>
@endpush

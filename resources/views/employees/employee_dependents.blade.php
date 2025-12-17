{{-- @extends('layouts.master')

@section('content')
@include('components.flash_message')--}}
    <div class="row">

        @if (isset($employeeDependent))
            @include('employees.edit_employee_dependent')
        @else
            @include('employees.add_employee_dependent')
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Employee Dependent List </h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="employee-dependent-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>CNIC</th>
                                <th>DOB</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID </th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>CNIC</th>
                                <th>DOB</th>
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
            var route = "{{ route('employee-dependent.index', ['employee_id' => '']) }}";
            @if(isset($employee[0]->id))
                route = "{{ route('employee-dependent.index', ['employee_id' => $employee[0]->id]) }}";
            @elseif (isset($employee->id))
                route = "{{ route('employee-dependent.index', ['employee_id' => $employee->id]) }}";
            @endif;
            //alert(route);
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });
            //alert(route);
            $('#employee-dependent-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: route,
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'dependent_name',
                        name: 'dependent_name'
                    },
                    {
                        data: 'dependent_relationship',
                        name: 'dependent_relationship'
                    },
                    {
                        data: 'dependent_cnic',
                        name: 'dependent_cnic'
                    },
                    {
                        data: 'dependent_dob',
                        name: 'dependent_dob',
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

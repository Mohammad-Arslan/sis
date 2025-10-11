@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        @if (isset($academicYearWorkingDays))
            @include('settings.academic_year_working_days.edit_academic_year_working_days')
        @else
            {{-- @permission('add-academic-year-working-days') --}}
            @include('settings.academic_year_working_days.add_academic_year_working_days')
            {{-- @endpermission --}}
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Academic Year Working Days List</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="academic-year-working-days-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Branch</th>
                                <th>Academic Year</th>
                                <th>Term</th>
                                <th>Working Days</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                {{-- <th>State</th> --}}
                                <th>Branch</th>
                                <th>Academic Year</th>
                                <th>Term</th>
                                <th>Working Days</th>
                                <th>Created At</th>
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

            $('#academic-year-working-days-data-table').DataTable({
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
                ajax: "{{ route('academic-year-working-days.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    // {
                    //     data: 'state.state_name',
                    //     name: 'state.state_name'
                    // },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                     {
                        data: 'academic_year_title',
                        name: 'academic_year_title'
                    },
                    {
                        data: 'term_name',
                        name: 'term_name'
                    },
                    {
                        data: 'working_days',
                        name: 'working_days'
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

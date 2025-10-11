@extends('layouts.master')

@section('content')
    <div class="row">

        @if (isset($comClass))
            @include('settings.classes.edit_class')
        @else
            @permission('add-class')
                @include('settings.classes.add_class')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Class List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="classes-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Sort Order</th>
                                <th>Attendance Type</th>
                                <th>Description</th>
                                <th>Abbreviation</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Sort Order</th>
                                <th>Attendance Type</th>
                                <th>Description</th>
                                <th>Abbreviation</th>
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


            $('#classes-data-table').DataTable({
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
                ajax: "{{ route('classes.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'sort',
                        name: 'sort',
                        width: "10%"
                    },
                    {
                        data: 'attendance_type',
                        name: 'attendance_type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'abbreviation',
                        name: 'abbreviation',
                        width: "5%"
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

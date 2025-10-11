@extends('layouts.master')

@section('content')
    <div class="row">

        <!-- <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                        A simple Success alert with <a href="#" class="alert-link">an example
                            link</a>. Give it a click if you like.
                    </div>
                </div> -->
        @if (isset($buildingType))
            @include('settings.building_type.edit_building_type')
        @else
            @permission('add-fee-period')
                @include('settings.building_type.add_building_type')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Building Type List</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="building-type-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Building Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Building Type</th>
                                <th>Description</th>
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

            $('#building-type-data-table').DataTable({
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
                ajax: "{{ route('building-type.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'type_name',
                        name: 'type_name'
                    },
                    {
                        data: 'type_description',
                        name: 'type_description'
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

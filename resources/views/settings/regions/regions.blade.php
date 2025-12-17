@extends('layouts.master')

@section('content')
    <div class="row">

        @if (isset($region))
            @include('settings.regions.edit_region')
        @else
            @permission('add-region')
                @include('settings.regions.add_region')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Region List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="region-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Region</th>
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
                                <th>Region</th>
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


            $('#region-data-table').DataTable({
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
                ajax: "{{ route('regions.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'region_name',
                        name: 'region_name'
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

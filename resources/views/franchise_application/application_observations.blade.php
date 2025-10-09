@extends('layouts.master')

@section('content')
@include('components.flash_message')
    <div class="row">

        @if (isset($frachiseApplicationRemark))
            @include('franchise_application.edit_observation')
        @else
            @permission('add-franchise-app-remarks')
                @include('franchise_application.add_observation')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Observations List</h4>
                    {{-- <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div> --}}
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="observation-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Observation/Remarks</th>
                                <th>Observation Type</th>
                                <th>Observation By</th>
                                <th>Observer Role</th>
                                <th>Observation On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Observation/Remarks</th>
                                <th>Observation Type</th>
                                <th>Observation By</th>
                                <th>Observer Role</th>
                                <th>Observation On</th>
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


            $('#observation-data-table').DataTable({
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
                ajax: "{{ route('frachiseApplicationRemark.index', ['franchise_application_id' => isset(request()->franchise_application_id) ? request()->franchise_application_id : $frachiseApplicationRemark->franchise_application_id]) }}",
                columns: [
                    {
                        data: 'observation',
                        render: function ( data, type, row ) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                        //name: 'observation',
                    },
                    {
                        data: 'observation_for',
                        name: 'observation_for',
                        sClass: "text-center"
                    },
                    {
                        data: 'observation_by',
                        name: 'observation_by'
                    },
                    {
                        data: 'observation_role',
                        name: 'observation_role'
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

@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Franchise List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('franchises.create') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Franchise
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <table id="data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Application No.</th>
                                <th>Applicant Name</th>
                                <th>CNIC</th>
                                <th>City</th>
                                <th>Personal Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Interested in</th>
                                <th>Source</th>
								<th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Application No.</th>
                                <th>Applicant Name</th>
                                <th>CNIC</th>
                                <th>City</th>
                                <th>Personal Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Interested in</th>
                                <th>Source</th>
								<th>Created At</th>
                                <th>Actions</th>
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
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: `<img class='ucs_loader' src='{{asset('loader.gif')}}' />`,
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('franchises.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%",
                        orderable: true
                    },
                    {
                        data: 'appl_name',
                        name: 'appl_name'
                    },
                    {
                        data: 'CNIC',
                        name: 'CNIC',
                        width: "15%"
                    },
                    {
                        data: 'cities.city_name',
                        name: 'cities.city_name',
                        width: "10%"
                    },
                    {
                        data: 'personal_address',
                        name: 'personal_address',
                        width: "8%"
                    },
                    {
                        data: 'primary_mobile_no',
                        name: 'primary_mobile_no',
                        width: "8%"
                    },
                    {
                        data: 'email',
                        name: 'email',
                        width: "8%"
                    },
                    {
                        data: 'franchise_type',
                        name: 'franchise_type',
                        width: "8%"
                    },
                    {
                        data: 'source.source_name',
                        name: 'source.source_name',
                        width: "10%"
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
                        sClass: 'text-center'
                    },
                ]
            });
        });
    </script>
@endpush

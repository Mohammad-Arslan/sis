@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Network Associate List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('network-associates.create') . '?tab=nwa' }}"
                            class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Network Associate
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="company_id" name="company_id" placeholder="Company">
                                    <option value="">Please select</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                                <label for="company_id" class="form-label">Company</label>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>

                    <table id="data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>NWA ID</th>
                                <th>NWA</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>NTN No.</th>
                                <th>STRN No.</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>NWA ID</th>
                                <th>NWA</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>NTN No.</th>
                                <th>STRN No.</th>
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
                searching: false,
                retrieve: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                ajax: {
                    url: "{{ route('network-associates.index') }}",
                    data: function(d) {
                        d.company_id = $('#company_id').val();
                        d.searchTerm = $('#myInput').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'company.company_name',
                        name: 'company.company_name',
                        width: "15%"
                    },
                    {
                        data: 'user.email',
                        name: 'user.email',
                        width: "10%"
                    },
                    {
                        data: 'NTN',
                        name: 'NTN',
                        width: "15%"
                    },
                    {
                        data: 'STRN',
                        name: 'STRN',
                        width: "15%"
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

        $(document).on('change', '.filter', function() {
            $('#data-table').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#myInput', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 3 || value.length == 0) {
                $('#data-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush

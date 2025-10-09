@extends('layouts.master')

@section('content')
    <div class="row">
        @if (isset($feeConcession))
            @include('settings.fee_concessions.edit_fee_concession')
        @else
            @permission('add-fee-concession')
                @include('settings.fee_concessions.add_fee_concession')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Fee Concession List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a href="{{ route('fee-concessions.index') }}" class="btn btn-primary btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
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
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . '[' . $branch->branch_code . ']' }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="concession_id" name="concession_id"
                                    placeholder="Concessions Type">
                                    <option value="">Please select</option>
                                    @foreach ($fee_concessions_type as $concessions_type)
                                        <option value="{{ $concessions_type->id }}">{{ $concessions_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="concession_id" class="form-label">Concessions Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="fee-concessions-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Concession</th>
                                {{-- <th>Company</th> --}}
                                <th>Percentage</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Concession</th>
                                {{-- <th>Company</th> --}}
                                <th>Percentage</th>
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
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#fee-concessions-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('fee-concessions.index') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.company_id = $('#company_id').val();
                        d.concession_id = $('#concession_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'academic_year.title',
                        name: 'academic_year.title'
                    },
                    {
                        data: 'branch.branch_code',
                        name: 'branch.branch_code'
                    },
                    {
                        data: 'branch.br_name',
                        name: 'branch.br_name'
                    },
                    {
                        data: 'fee_concession_type.name',
                        name: 'fee_concession_type.name'
                    },
                    // {
                    //     data: 'company.company_name',
                    //     name: 'company.company_name'
                    // },


                    {
                        data: 'concession_percentage',
                        name: 'concession_percentage'
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
            $('#fee-concessions-data-table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#fee-concessions-data-table').DataTable().ajax.reload(null, false).page('first');
            }
        });
    </script>
@endpush

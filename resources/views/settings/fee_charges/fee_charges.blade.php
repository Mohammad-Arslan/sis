@extends('layouts.master')

@section('content')
    <div class="row">

        <!-- <div class="col-lg-12">
                                    <div class="alert alert-success" role="alert">
                                        A simple Success alert with <a href="#" class="alert-link">an example
                                            link</a>. Give it a click if you like.
                                    </div>
                                </div> -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
                @if (session('errors'))
                    <hr>
                    <ul class="mb-0">
                        @foreach (session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if (isset($feeCharge))
            @include('settings.fee_charges.edit_fee_charges')
        @else
            @permission('add-fee-charge')
                @include('settings.fee_charges.add_fee_charge')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Fee Charges List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a href="{{ route('fee-charges.index') }}" class="btn btn-primary btn-label btn-sm">
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
                                <select class="filter form-select" id="s_academic_year_id" name="s_academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}"
                                            {{ $academic_year->active == 1 ? 'selected' : '' }}>{{ $academic_year->title }}
                                        </option>
                                    @endforeach

                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
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
                                <select class="filter form-select" id="charges_id" name="charges_id"
                                    placeholder="Charges Type">
                                    <option value="">Please select</option>
                                    @foreach ($fee_charges_type as $charges_type)
                                        <option value="{{ $charges_type->id }}">{{ $charges_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="charges_id" class="form-label">Charges Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="fee-charges-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Fee Charges</th>
                                <!-- <th>Abbreviation</th> -->
                                <!-- <th>Description</th> -->
                                <th>Company</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Frequency</th>
                                <th>Amount</th>
                                <!-- <th>Is_Discountable</th> -->
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
                                <th>Fee Charges</th>
                                <!-- <th>Abbreviation</th> -->
                                <!-- <th>Description</th> -->
                                <th>Company</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Frequency</th>
                                <th>Amount</th>
                                <!-- <th>Is_Discountable</th> -->
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

            $('#fee-charges-data-table').DataTable({
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
                    url: "{{ route('fee-charges.index') }}",
                    data: function(d) {
                        d.academic_year_id = $('#s_academic_year_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.company_id = $('#company_id').val();
                        d.charges_id = $('#charges_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'fee_charges_type.name',
                        name: 'fee_charges_type.name'
                    },
                    // {
                    //     data: 'abbreviation',
                    //     name: 'abbreviation'
                    // },
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    {
                        data: 'company.company_name',
                        name: 'company.company_name'
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
                        data: 'fee_charges_type.frequency',
                        name: 'fee_charges_type.frequency'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    // {
                    //     data: 'is_discountable',
                    //     name: 'is_discountable'
                    // },
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
            $('#fee-charges-data-table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#fee-charges-data-table').DataTable().ajax.reload(null, false).page('first');
            }
        });
    </script>
@endpush

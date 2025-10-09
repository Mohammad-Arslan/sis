@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Franchise Applications</li>
    </x-breadcrumb>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Franchise Application List </h4>
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0)" class="btn btn-success btn-sm clear_filters">
                            Clear Filters
                        </a>
                        @permission('add-franchise-application')
                            <a href="{{ route('franchise-applications.create') }}" class="btn btn-success btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Application
                            </a>
                        @endpermission
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter load-select form-select" id="state_id" name="state_id"
                                    placeholder="state" data-target="city_id" data-url="{{ route('list-cities') }}"
                                    aria-label="State select" required>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">State/Province</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                    id="city" name="city_id" aria-label="City select" required>
                                    <option value="">Please select</option>
                                    @if (old('state_id'))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="city" class="form-label">City</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('city_id'))
                                        {{ $errors->first('city_id') }}
                                    @else
                                        City is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example" id="source_id"
                                    name="source_id" required>
                                    <option value="">Please select</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->id }}"
                                            {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                            {{ $source->source_name }}</option>
                                    @endforeach
                                </select>
                                <label for="source_id" class="form-label">Source </label>
                                <div class="invalid-tooltip">
                                    Where did you hear about us?
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="applicationStatus" name="application_status" required>
                                    <option value="">Please select</option>
                                    <option value="P">Pending</option>
                                    <option value="A">Approved</option>
                                    <option value="C">Cancelled</option>
                                </select>
                                <label for="applicationStatus" class="form-label">Application Status </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="bd_status" name="bd_status" required>
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="not_approved">Not Approved</option>
                                </select>
                                <label for="bd_status" class="form-label">BD Status </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="qa_status" name="qa_status" required>
                                    <option value="">Please select</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Forwarded">Forwarded</option>
                                </select>
                                <label for="qa_status" class="form-label">QA Status </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="tor_status" name="tor_status" required>
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="not_approved">Not Approved</option>
                                </select>
                                <label for="tor_status" class="form-label">TOR Status </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="legal_status" name="legal_status" required>
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="need_recommendation">Need Recommendation</option>
                                    <option value="ready_for_mou">Ready For MOU/FA</option>
                                </select>
                                <label for="legal_status" class="form-label">Legal Status </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="dd_status" name="dd_status" required>
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="not_approved">Not Approved</option>
                                </select>
                                <label for="dd_status" class="form-label">DD Status </label>
                            </div>
                        </div>
                        <!-- <div class="col-md-2 col-sm-12">
                <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('from_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('from_date') }}" name="from_date" id="from_date" required>
                <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
                </div>
                <label for="from_date" class="form-label">From Date</label>
                <div class="invalid-tooltip">
                @if ($errors->has('from_date'))
    {{ $errors->first('from_date') }}
@else
    Setup Date is required!
    @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="filter form-control @if ($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('to_date') }}" name="to_date" id="to_date">
                <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
                </div>
                <label for="to_date" class="form-label">To Date</label>
                <div class="invalid-tooltip">
                @if ($errors->has('to_date'))
    {{ $errors->first('to_date') }}
@else
    To Date is required!
    @endif
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="filter form-control @if ($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y" data-deafult-date="" value="{{ old('to_date') }}" name="to_date" id="to_date">
                    <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                    </div>
                    <label for="to_date" class="form-label">To Date</label>
                    <div class="invalid-tooltip">
                    @if ($errors->has('to_date'))
    {{ $errors->first('to_date') }}
@else
    To Date is required!
    @endif
                                </div>
                            </div>
                        </div> -->

                        <div class="col-md-4 col-sm-12">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="filter form-control" data-provider="flatpickr"
                                    data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('date_range') }}"
                                    name="date_range" id="date_range" data-range-date="true">
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label for="date_range" class="form-label">Date Range</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="agreement_type" name="agreement_type">
                                    <option value="">Please select</option>
                                    <option value="APP">Applied</option>
                                    <option value="LOI">LOI</option>
                                    <option value="FA">FA</option>
                                    <option value="MOU">MOU</option>
                                </select>
                                <label for="agreement_type" class="form-label">Agreement Type </label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="search-box">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                    </div>

                    <table id="data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Province/City</th>
                                <th>Applicant Name</th>
                                <th>School Name</th>
                                <th>School Type</th>
                                <th>Agreement Type</th>
                                <th>Agreement Date</th>
                                <th>Execution Date</th>
                                <th>Cut Off Date</th>
                                <th>Extension Date</th>
                                <th>Operational Date</th>
                                <th>Actual Operational Date</th>
                                <th>Renewal Date</th>
                                <th>Total Franchise Fee</th>
                                <th>Received Amount</th>
                                {{-- <th>CNIC</th> --}}
                                {{-- <th>Address</th> --}}
                                {{-- <th>Source</th> --}}
                                <th>Application</th>
                                <th>BD Report</th>
                                <th>QA Report</th>
                                <th>FA & Approved TOR Checklist</th>
                                <th>Legal Review</th>
                                <th>DD Review</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Province/City</th>
                                <th>Applicant Name</th>
                                <th>School Name</th>
                                <th>School Type</th>
                                <th>Agreement Type</th>
                                <th>Agreement Date</th>
                                <th>Execution Date</th>
                                <th>Cut Off Date</th>
                                <th>Extension Date</th>
                                <th>Operational Date</th>
                                <th>Actual Operational Date</th>
                                <th>Renewal Date</th>
                                <th>Total Franchise Fee</th>
                                <th>Received Amount</th>
                                {{-- <th>CNIC</th> --}}
                                {{-- <th>Address</th> --}}
                                {{-- <th>Source</th> --}}
                                <th>Application</th>
                                <th>BD Report</th>
                                <th>QA Report</th>
                                <th>FA & Approved TOR Checklist</th>
                                <th>Legal Review</th>
                                <th>DD Review</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('franchise_application.modals.upload_document_modal')





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
                searching: false,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                scrollCollapse: true,
                // fixedColumns:   {
                //     left: 1,
                //     right: 1
                // },
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('franchise-applications.index') }}",
                    data: function(d) {
                        d.state_id = $('#state_id').val();
                        d.city_id = $('#city').val();
                        d.source_id = $('#source_id').val();
                        d.status = $('#applicationStatus').val();
                        d.agreement_type = $('#agreement_type').val();
                        d.bd_status = $('#bd_status').val();
                        d.qa_status = $('#qa_status').val();
                        d.legal_status = $('#legal_status').val();
                        d.tor_status = $('#tor_status').val();
                        d.dd_status = $('#dd_status').val();
                        // d.to_date = $('#to_date').val();
                        d.date_range = $('#date_range').val();
                        d.searchTerm = $('#myInput').val().toLowerCase();
                    }
                },
                columns: [
                    {
                        data: 'province_city',
                        name: 'province_city',
                        width: "10%"
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        width: "15%"
                    },
                    {
                        data: 'purposed_school_name',
                        name: 'purposed_school_name',

                    },
                    {
                        data: 'school_type',
                        name: 'school_type',

                    },
                    {
                        data: 'agreement_type',
                        name: 'agreement_type',
                    },
                    {
                        data: 'agreement_date',
                        name: 'agreement_date',
                    },
                    {
                        data: 'executionDate',
                        name: 'executionDate',

                    },
                    {
                        data: 'cut_offDate',
                        name: 'cut_offDate',

                    },
                    {
                        data: 'extensionDate',
                        name: 'extensionDate',

                    },
                    {
                        data: 'operational_date',
                        name: 'operational_date',

                    },
                    {
                        data: 'actual_operational_date',
                        name: 'actual_operational_date',

                    },
                    {
                        data: 'renewal_date',
                        name: 'renewal_date',

                    },
                    {
                        data: 'total_franchise_fee',
                        name: 'total_franchise_fee',

                    },
                    {
                        data: 'amount_received',
                        name: 'amount_received',

                    },


                    // {
                    //     data: 'CNIC',
                    //     name: 'CNIC',
                    //     width: "15%"
                    // },

                    // {
                    //     data: 'personal_address',
                    //     name: 'personal_address',
                    //     width: "20%"
                    // },
                    /*{data: 'source.source_name', name: 'source.source_name', width: "5%"},*/
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%"
                    },
                    {
                        data: 'franchise_application_bd_status',
                        name: 'franchise_application_bd_status',
                        width: "5%"
                    },
                    {
                        data: 'franchise_application_qa_status',
                        name: 'franchise_application_qa_status',
                        width: "5%"
                    },
                    {
                        data: 'franchise_application_tor_status',
                        name: 'franchise_application_tor_status',
                        width: "5%"
                    },
                    {
                        data: 'franchise_application_legal_status',
                        name: 'franchise_application_legal_status',
                        width: "5%"
                    },
                    {
                        data: 'franchise_application_dd_status',
                        name: 'franchise_application_dd_status',
                        width: "5%"
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "10%",
                        orderable: true
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

        $(document).on('change', '.filter', function() {
            $('#data-table').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#myInput', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 2 || value.length == 0) {
                $('#data-table').DataTable().ajax.reload(null, false);
            }
        });

        $(document).on("click", '.clear_filters', function() {
            $('.filter').val('');
            $('#data-table').DataTable().ajax.reload(null, false);
        });
    </script>
@endpush

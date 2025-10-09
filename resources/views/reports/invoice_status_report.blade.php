@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Invoice Statuses </h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('invoice-status-report') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                    </a>
                    {{-- <a href="{{ route('paid-students-export') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                    </a> --}}
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager|network_associate')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="load-select filter form-select" id="region_id" name="region_id" placeholder="Region"
                                    data-target="branch_id" data-url="{{ route('list-branches-by-region') }}">
                                        <option value="">Please select</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="region_id" class="form-label">Region</label>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="load-select filter form-select" id="branch_id" name="branch_id"
                                            data-target="class_id,fee_period_id" data-url="{{ route('list-class-section-feeperiod') }}">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="branch_id" class="form-label">Branch</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select @if ($errors->has('fee_period_id')) is-invalid @endif"
                                            id="fee_period_id" name="fee_period_id" required>
                                        <option value="">Select Fee Period</option>
                                    </select>
                                    <label for="feePeriodId" class="form-label">Fee Period <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('fee_period_id'))
                                            {{ $errors->first('fee_period_id') }}
                                        @else
                                            Fee Period is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endrole
                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>
                                    @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager|network_associate')
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                    @endforeach
                                    @endrole
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id" placeholder="Section">
                                    <option value="">Please select</option>
                                    @role('super_admin|network_associate|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                    @endrole
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>--}}
                        {{-- @role('network_associate') --}}

                        {{-- @endrole --}}

                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                    </div>
                    <table id="invoice-status-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Student Count</th>
                                <th>Paid</th>
                                <th>Unpaid</th>
                                <th>Invoice Generated</th>
                                <th>Not Generated</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Student Count</th>
                                <th>Paid</th>
                                <th>Unpaid</th>
                                <th>Invoice Generated</th>
                                <th>Not Generated</th>
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

            $('#invoice-status-table').dataTable({
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
                    url: "{{ route('invoice-status-report') }}",
                    data: function(d) {
                        d.region_id = $('#region_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.fee_period_id = $('#fee_period_id').val();
                        //d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [
                    @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                    {
                        data: 'branch_code',
                        name: 'branch_code',
                        'defaultContent': ''
                    },
                    {
                        data: 'br_name',
                        name: 'br_name',
                        'defaultContent': ''
                    },
                    @endrole
                    {
                        data: 'student_count',
                        name: 'student_count',
                        'defaultContent': ''
                    },
                    {
                        data: 'paid_count',
                        name: 'paid_count',
                        'defaultContent': ''
                    },
                    {
                        data: 'unpaid_count',
                        name: 'unpaid_count',
                        'defaultContent': ''
                    },
                    {
                        data: 'generated_count',
                        name: 'generated_count',
                        'defaultContent': ''
                    },
                    {
                        data: 'not_generated_count',
                        name: 'not_generated_count',
                        'defaultContent': ''
                    },
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#invoice-status-table').DataTable().ajax.reload(null, false).page('first');
        });

        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#invoice-status-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
    </script>
@endpush

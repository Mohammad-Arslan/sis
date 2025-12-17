@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Payment Detail </h4>
                <div class="flex-shrink-0">
                    <a onclick="clearDate()" class="btn btn-info btn-label btn-sm">
                        <i class="ri-close-fill label-icon align-middle fs-16 me-2"></i> Clear
                    </a>
                    <a href="{{ route('paid-students-list') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                    </a>

                    <a href="{{ route('paid-students-export') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @role('super_admin|head-of-finance|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id" name="state_id">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">State</label>
                            </div>
                        </div>
                        @endrole
                        @role('super_admin|head-of-finance|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager|network_associate')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                        <option value="">Please select</option>
                                        @foreach ($academic_years as $academic_year)
                                            <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                        @endforeach
                                    </select>
                                    <label for="academic_year_id" class="form-label">Academic Year</label>
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
                                    <label for="feePeriodId" class="form-label">Fee Period </label>
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
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>
                                    @role('super_admin|head-of-finance|parent-relation-officer|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager|network_associate')
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
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
                                    @role('super_admin|network_associate|head-of-finance|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                    @endrole
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>
                        {{-- @role('network_associate') --}}
                        <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                        <div class="input-group">
                            <input type="text"
                                class="form-control disable-max-date filter @if ($errors->has('paid_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ old('paid_date') }}" name="paid_date" id="paid_date">
                            <label for="paid_date" class="form-label">Paid Date </label>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                        </div>
                        </div>
                        </div>
                        {{-- @endrole --}}
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                    <option value="">Please Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label for="gender" class="form-label">Gender</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="paid-student-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                @role('super_admin|head-of-finance|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>State</th>
                                {{--<th>Region</th>--}}
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Invoice No</th>
                                <th>Invoice Type</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Adm. Date</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Validity Date</th>
                                <th>Fee Month</th>
                                {{-- <th>Package Title</th> --}}
                                <th>Paid Date</th>
                                <th>Amount (Rs.)</th>
                                <th>Arrear</th>
                                {{--<th>Email</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                @role('super_admin|head-of-finance|manager-parent-relations|ho-accountant|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>State</th>
                                {{--<th>Region</th>--}}
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Invoice No</th>
                                <th>Invoice Type</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Adm. Date</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Validity Date</th>
                                <th>Fee Month</th>
                                <th>Paid Date</th>
                                <th>Amount (Rs.)</th>
                                <th>Arrear</th>
                                {{--<th>Email</th>--}}
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

            $('#paid-student-table').dataTable({
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
                    url: "{{ route('paid-students-list') }}",
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.gender = $('#gender').val();
                        d.section_id = $('#section_id').val();
                        d.region_id = $('#region_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.state_id = $('#state_id').val();
                        d.fee_period_id = $('#fee_period_id').val();
                        d.paid_date = $('#paid_date').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [
                    @role('super_admin|head-of-finance|ho-accountant|manager-parent-relations|senior-finance-manager|manager-parent-relations|head_of_bd|bd_sales|deputy-director|ceo|manager-quality-assurance|head_of_qa|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                    {
                        data: 'state',
                        name: 'state'
                    },
                    /*{
                        data: 'region',
                        name: 'region'
                    },*/
                    {
                        data: 'branch_code',
                        name: 'branch_code'
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    @endrole
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'invoice_frequency',
                        name: 'invoice_frequency'
                    },
                    {
                        data: 'student_id',
                        name: 'student_id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'student.gender',
                        name: 'student.gender',
                        'defaultContent': ''
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'section_name',
                        name: 'section_name'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'student.admission_wef',
                        name: 'student.admission_wef',
                        'defaultContent': ''
                    },
                    {
                        data: 'issue_date',
                        name: 'issue_date'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'validity_date',
                        name: 'validity_date'
                    },
                    {
                        data: 'fee_month',
                        name: 'fee_month'
                    },
                    // {
                    //     data: 'package_name',
                    //     name: 'package_name',
                    // },
                    {
                        data: 'paid_date',
                        name: 'paid_date',
                    },
                    {
                        data: 'cost',
                        name: 'cost',
                    },
                    {
                        data: 'arrears',
                        name: 'arrears',
                    },
                    /*{
                        data: 'student.email',
                        name: 'student.email',
                        'defaultContent': ''
                    }*/
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#paid-student-table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#paid-student-table').DataTable().ajax.reload(null, false).page('first');
            }
        });
        function clearDate(){
            paid_date = $('#paid_date').val('');
            $('#paid-student-table').DataTable().ajax.reload(null, false).page('first');
        }
    </script>

@endpush

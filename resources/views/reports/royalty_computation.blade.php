@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Royalty Computation</h4>
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0)" class="btn btn-info btn-label btn-sm royaltyComputationExport">
                            <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                    </div>
                    <!-- <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                            <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                        </div>
                                    </div> -->
                </div><!-- end card header -->
                <div class="card-body">
                    @permission('royalty-computation-report')
                    <div class="mx-3">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Admission Fees</th>
                                    <th scope="col">Tution Fees</th>
                                    <th scope="col">Security Deposit (Refundable)</th>
                                    <th scope="col" class="text-end">Total Fees</th>
                                    <th scope="col" class="text-end">Total Royalty</th>
                                    <th scope="col" class="text-end">Total NWA Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="admission_charges">0</td>
                                    <td class="tution_charges">0</td>
                                    <td class="security_charges">0</td>
                                    <td class="text-end total_fees">0</td>
                                    <td class="text-end total_royalty">0</td>
                                    <td class="text-end total_amount">0</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="border mt-5 mb-4 border-dashed"></div>
                    <form action="{{route('royalty-computation-export')}}" method="post" class="submitRoyaltyComputation">
                        @csrf
                        <div class="row">

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select load-select @if ($errors->has('state_id')) is-invalid @endif"
                                        id="stateId" name="state_id" aria-label="stateId select" data-target="branch_id"
                                        data-url="{{ route('list-branches-by-state') }}" required>
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="stateId" class="form-label">State <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('state_id'))
                                            {{ $errors->first('state_id') }}
                                        @else
                                            State is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select load-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                        id="academic_year_id" name="academic_year_id">
                                        <option value="">Please select</option>
                                        @foreach ($academic_years as $academic_year)
                                            <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                        @endforeach
                                    </select>
                                    <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('academic_year_id'))
                                            {{ $errors->first('academic_year_id') }}
                                        @else
                                            Academic year is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                        id="branchId" name="branch_id" aria-label="branchId select"
                                        data-target="class_id,fee_period_id"
                                        data-url="{{ route('list-class-section-feeperiod') }}" required>
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="branchId" class="form-label">Branch <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('branch_id'))
                                            {{ $errors->first('branch_id') }}
                                        @else
                                            Branch is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('fee_period_id')) is-invalid @endif"
                                        id="feePeriodId" name="fee_period_id" aria-label="feePeriodId select" required>
                                        <option value="">Select Fee Period</option>
                                        @foreach ($fee_periods as $fee_period)
                                            <option value="{{ $fee_period->id }}">{{ $fee_period->period_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="feePeriodId" class="form-label">Fee Period <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('fee_period_id'))
                                            {{ $errors->first('fee_period_id') }}
                                        @else
                                            Fee Period is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="load-select form-select @if ($errors->has('class_id')) is-invalid @endif"
                                        id="classId" name="class_id" aria-label="classId select" data-target="section_id"
                                        data-url="{{ route('list-branch-classes-sections') }}" required>
                                        <option value="">Select Class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="classId" class="form-label">Class <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('class_id'))
                                            {{ $errors->first('class_id') }}
                                        @else
                                            Class is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('section_id')) is-invalid @endif"
                                        id="sectionId" name="section_id" aria-label="sectionId select" required>
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="sectionId" class="form-label">Section <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('section_id'))
                                            {{ $errors->first('section_id') }}
                                        @else
                                            Section is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('payment_status')) is-invalid @endif"
                                        id="paymentStatus" name="payment_status" aria-label="paymentStatus select" required>
                                        <option value="paid" selected>Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                    <label for="paymentStatus" class="form-label">Payment Status <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('payment_status'))
                                            {{ $errors->first('payment_status') }}
                                        @else
                                            Payment Status is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('from_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('from_date') }}" name="from_date" id="fromDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="fromDate" class="form-label">From Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('from_date'))
                                            {{ $errors->first('from_date') }}
                                        @else
                                            From Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('to_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('to_date') }}" name="to_date" id="toDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="toDate" class="form-label">To Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('to_date'))
                                            {{ $errors->first('to_date') }}
                                        @else
                                            To Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table id="royalty-computation"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th colspan="9" scope="colgroup"></th>
                                <th colspan="7" scope="colgroup" class="text-center">Fees</th>
                                <th colspan="1" scope="colgroup"></th>
                            </tr>
                            <tr>
                                <th>Branch Code</th>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Invoice no</th>
                                <th>Fee Period</th>
                                <th>Admission WEF</th>
                                <th>Classes</th>
                                <th>Section</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                                <th>Admission Fees</th>
                                <th>Tution Fees</th>
                                <th>Security Deposit (Refundable)</th>
                                <th>Total</th>
                                <th>Royalty</th>
                                <th>NWA Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @endpermission
                </div>
            </div>
        </div>
    </div>
@endsection


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            var dataTable = $('#royalty-computation').DataTable({
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
                ajax: {
                    url: '/reports/computation',
                    data: function(d) {
                        d.filters = {
                            from_date: $('#fromDate').val(),
                            to_date: $('#toDate').val(),
                            fee_period_id: $('#feePeriodId').val(),
                            section_id: $('#sectionId').val(),
                            class_id: $('#classId').val(),
                            branch_id: $('#branchId').val(),
                            academic_year_id: $('#academic_year_id').val(),
                            state_id: $('#stateId').val(),
                            payment_status: $('#paymentStatus').val()
                        }
                    },
                },
                columns: [{
                        data: 'student.branch.branch_code',
                        name: 'student.branch.branch_code'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'student.roll_no',
                        name: 'student.roll_no'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'fee_period',
                        name: 'fee_period'
                    },
                    {
                        data: 'admission_wef',
                        name: 'admission_wef'
                    },
                    {
                        data: 'student_fee_package.com_class.class_name',
                        name: 'student_fee_package.com_class.class_name'
                    },
                    {
                        data: 'student_fee_package.section.section_name',
                        name: 'student_fee_package.section.section_name'
                    },
                    {
                        data: 'is_paid',
                        name: 'is_paid'
                    },
                    {
                        data: 'paid_date',
                        name: 'paid_date'
                    },
                    {
                        data: 'admission_fees',
                        name: 'admission_fees'
                    },
                    {
                        data: 'tution_fees',
                        name: 'tution_fees'
                    },
                    {
                        data: 'security_fees',
                        name: 'security_fees'
                    },
                    {
                        data: 'total_fees',
                        name: 'total_fees'
                    },
                    {
                        data: 'royalty',
                        name: 'royalty'
                    },
                    {
                        data: 'nwa_amount',
                        name: 'nwa_amount'
                    },
                    {
                        data: 'edit_invoice',
                        name: 'edit_invoice'
                    }
                ],
                drawCallback: function() {
                    var addi_data = dataTable.ajax.json();
                    console.log(addi_data);

                    $('.admission_charges').html(addi_data['admission'])
                    $('.tution_charges').html(addi_data['tution'])
                    $('.security_charges').html(addi_data['security'])
                    $('.total_fees').html(addi_data['total'])
                    $('.total_royalty').html(addi_data['total_royalty'])
                    $('.total_amount').html(addi_data['nwa_amount'])
                }
            });

            function onFiltersChange() {
                $('#royalty-computation').DataTable().ajax.reload(null, false)
            }

            $('#fromDate').change(onFiltersChange)
            $('#toDate').change(onFiltersChange)
            $('#paymentStatus').change(onFiltersChange)
            $('#feePeriodId').change(onFiltersChange)
            $('#sectionId').change(onFiltersChange)
            $('#classId').change(onFiltersChange)
            $('#branchId').change(onFiltersChange)
            $('#academic_year_id').change(onFiltersChange)
            $('#regionId').change(onFiltersChange)
            $('#stateId').change(onFiltersChange)

            $('.royaltyComputationExport').on('click',function (){
                $('.submitRoyaltyComputation').submit();
            });
        });

        function sumOfProperty(array = [], property) {
            return array.reduce((prev, data) => prev += data[property], 0);
        }
    </script>
@endpush

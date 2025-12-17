@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promotion-requests.index') }}">Promotion Requests</a></li>
        @if (isset($promotionRequest))
            <li class="breadcrumb-item active">Edit Student Promotion</li>
        @else
            <li class="breadcrumb-item active">Individual Student Promotion</li>
        @endif
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <form class="row g-3 needs-validation promotion-form" novalidate method="POST"
                action="{{ isset($promotionRequest) ? route('promotion-requests.update', $promotionRequest->id) : route('promotion-requests.store') }}">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        @if (isset($promotionRequest))
                            <li class="breadcrumb-item active">Edit Student Promotion</li>
                        @else
                            <h4 class="mb-0 card-title flex-grow-1">Individual Student Promotion</h4>
                        @endif
                    </div><!-- end card header -->

                    <div class="card-body">

                        <h5 class="mb-3 text-muted d-flex align-items-center"><i
                                class="ri-folder-shield-2-fill me-1"></i>Selection Criteria</h5>
                        <div class="live-preview">
                            <div class="row g3">
                                @if (isset($promotionRequest))
                                    @php
                                        $student = $promotionRequest->student_promotion_request->student;
                                        $student_full_name =
                                            $student->first_name . ' ' . $student->middle_name ??
                                            ' ' . $student->last_name;
                                    @endphp
                                @endif
                                <!-- Student info section start -->
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="number"
                                            class="form-control load-student @if ($errors->has('student_roll_no')) is-invalid @endif"
                                            id="studentRollNo" name="student_roll_no" placeholder="Please enter Student ID"
                                            value="{{ isset($promotionRequest) ? $student->roll_no : old('student_roll_no') }}"
                                            data-student-url="{{ route('get-student') }}" required
                                            {{ isset($promotionRequest) ? 'readonly' : '' }}>
                                        <label for="studentRollNo" class="form-label">Student ID <span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('student_roll_no'))
                                                {{ $errors->first('student_roll_no') }}
                                            @else
                                                Student ID is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('student_name')) is-invalid @endif"
                                            id="studentName" name="student_name" placeholder="Please enter student name"
                                            readonly
                                            value="{{ isset($promotionRequest) ? $student_full_name : old('student_name') }}">
                                        <label for="studentName" class="form-label">Student Name</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('student_name'))
                                                {{ $errors->first('student_name') }}
                                            @else
                                                Student Name is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('admission_date')) is-invalid @endif"
                                            id="admissionDate" name="admission_date" placeholder="Please enter student name"
                                            readonly
                                            value="{{ isset($promotionRequest) ? $student->admission_wef : old('admission_date') }}">
                                        <label for="studentName" class="form-label">Admission Date</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('admission_date'))
                                                {{ $errors->first('admission_date') }}
                                            @else
                                                Admission Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('academic_year_title')) is-invalid @endif"
                                            id="academic_year_id" name="academic_year_title"
                                            placeholder="Please enter student academic year" readonly
                                            value="{{ isset($promotionRequest) ? $promotionRequest->prev_academic_year->title : old('academic_year_title') }}">
                                        <label for="academic_year_id" class="form-label">Academic Year</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('academic_year_title'))
                                                {{ $errors->first('academic_year_title') }}
                                            @else
                                                Academic year is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('branch')) is-invalid @endif"
                                            id="branch" name="branch" placeholder="Please enter student branch" readonly
                                            value="{{ isset($promotionRequest) ? $promotionRequest->prev_branch->br_name : old('branch') }}">
                                        <label for="branch" class="form-label">Branch</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('branch'))
                                                {{ $errors->first('branch') }}
                                            @else
                                                Branch is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('class')) is-invalid @endif"
                                            id="class" name="class" placeholder="Please enter student class" readonly
                                            value="{{ isset($promotionRequest) ? $promotionRequest->prev_class->class_name : old('class') }}">
                                        <label for="class" class="form-label">Class</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('class'))
                                                {{ $errors->first('class') }}
                                            @else
                                                Class is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text"
                                            class="form-control @if ($errors->has('section')) is-invalid @endif"
                                            id="section" name="section" placeholder="Please enter student section"
                                            readonly
                                            value="{{ isset($promotionRequest) ? $promotionRequest->prev_section->section_name : old('section') }}">
                                        <label for="section" class="form-label">Section</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('section'))
                                                {{ $errors->first('section') }}
                                            @else
                                                Section is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-12 text-end">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_reports" disabled>Fetch
                                    Reports</a>
                            </div>
                            @include('gradebook_history.index') --}}

                            <input type="hidden" name="promotion_type" value="individual">
                            <input type="hidden" name="academic_year" id="academic_year"
                                value="{{ isset($promotionRequest) ? $promotionRequest->prev_academic_year_id : old('academic_year') }}" />
                            <input type="hidden" name="student_id"
                                value="{{ isset($promotionRequest) ? $student->id : old('student_id') }}" />
                            <input type="hidden" id="branch_id" name="branch_id"
                                value="{{ isset($promotionRequest) ? $promotionRequest->prev_branch_id : old('branch_id') }}">
                            <input type="hidden" name="class_id" id="class_id"
                                value="{{ isset($promotionRequest) ? $promotionRequest->prev_class_id : old('class_id') }}" />
                            <input type="hidden" name="section_id" id="section_id"
                                value="{{ isset($promotionRequest) ? $promotionRequest->prev_section_id : old('section_id') }}" />
                            <input type="hidden" name="branch_class_section_id" id="branch_class_section_id"
                                value="{{ isset($promotionRequest) ? $promotionRequest->prev_branch_class_section_id : old('branch_class_section_id') }}" />
                            @csrf

                            @if (isset($promotionRequest))
                                @method('PUT')
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card">
                    <!-- Dues info section start -->
                    <div class="mt-3 border border-dashed"></div>
                    <h5 class="text-muted d-flex align-items-center"><i class="ri-folder-shield-2-fill me-1"></i>Dues
                        Info</h5>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('last_paid_amount')) is-invalid @endif"
                                    id="last_paid_amount" name="last_paid_amount" placeholder="Last Paid Amount" readonly
                                    value="{{ old('last_paid_amount') }}">
                                <label for="last_paid_amount" class="form-label">Last Paid Amount</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('last_paid_amount'))
                                        {{ $errors->first('last_paid_amount') }}
                                    @else
                                        Class Section is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('last_paid_date')) is-invalid @endif"
                                    id="last_paid_date" name="last_paid_date" placeholder="Last paid date" readonly
                                    value="{{ old('last_paid_date') }}">
                                <label for="last_paid_date" class="form-label">Last paid date</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('last_paid_date'))
                                        {{ $errors->first('last_paid_date') }}
                                    @else
                                        Last paid date!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('last_paid_fee_period')) is-invalid @endif"
                                    id="last_paid_fee_period" name="last_paid_fee_period"
                                    placeholder="Last paid fee period" readonly
                                    value="{{ old('last_paid_fee_period') }}">
                                <label for="last_paid_fee_period" class="form-label">Last paid fee period</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('last_paid_fee_period'))
                                        {{ $errors->first('last_paid_fee_period') }}
                                    @else
                                        Last paid fee period!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('last_unpaid_amount')) is-invalid @endif"
                                    id="last_unpaid_amount" name="last_unpaid_amount" placeholder="Unpaid Amount"
                                    readonly value="{{ old('last_unpaid_amount') }}">
                                <label for="last_unpaid_amount" class="form-label">Unpaid Amount</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('last_unpaid_amount'))
                                        {{ $errors->first('last_unpaid_amount') }}
                                    @else
                                        Unpaid Amount!
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('unpaid_validity_date')) is-invalid @endif"
                                    id="unpaid_validity_date" name="unpaid_validity_date"
                                    placeholder="Unpaid Validity Date" readonly
                                    value="{{ old('unpaid_validity_date') }}">
                                <label for="unpaid_validity_date" class="form-label">Unpaid Validity Date</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('unpaid_validity_date'))
                                        {{ $errors->first('unpaid_validity_date') }}
                                    @else
                                        Unpaid Validity Date!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('last_unpaid_fee_period')) is-invalid @endif"
                                    id="last_unpaid_fee_period" name="last_unpaid_fee_period"
                                    placeholder="Unpaid Fee Period" readonly value="{{ old('last_unpaid_fee_period') }}">
                                <label for="last_unpaid_fee_period" class="form-label">Unpaid Fee Period</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('last_unpaid_fee_period'))
                                        {{ $errors->first('last_unpaid_fee_period') }}
                                    @else
                                        Unpaid Fee Period!
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dues info section end -->
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3 text-muted d-flex align-items-center"><i
                                class="ri-folder-shield-2-fill me-1"></i>Promoted To</h5>
                        <div class="live-preview">
                            <div class="row g-3 lower_filter">
                                @include('promotion_requests.promoted_filters')
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('header_scripts')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#studentRollNo').val() != '') {
                onStudentChange();
                /*onRelationChange();*/
            }

            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#promoted_branch_class_id').data('url', route);
                console.log('#branch_id', $('#promoted_branch_class_id').data('url'));
            });
            $('#promoted_branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#promoted_branch_class_id').data('url', route);
                console.log('#promoted_branch_id', $('#promoted_branch_class_id').data('url'));
                
                // Load classes for the selected branch
                if (typeof loadClassesAndSections === 'function') {
                    loadClassesAndSections();
                }
            });
        });

        $(document).on('change', '#applicationDate', onApplicationDateChange);

        $("#applicationDate").flatpickr({
            minDate: "today",
            maxDate: new Date().fp_incr(7) // 7 days from now
        });

        $("#lastDayAt").flatpickr({
            minDate: "today"
        });

        function onApplicationDateChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            var date = addDaysToDate(aplicationDateObj, 30);
            var fpDate = flatpickr('#lastDayAt', {
                minDate: "today"
            })
            fpDate.setDate(date);
        }

        $(document).on('change', '#libraryClearance', onLibraryClearanceChange);

        function onLibraryClearanceChange() {
            if (this.checked) $('#clearanceAmount').attr('disabled', false)
            else $('#clearanceAmount').attr('disabled', true)
        }

        $(document).on('change', '#approved_date', onApproveDataChange);

        function onApproveDataChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            var date = addDaysToDate(aplicationDateObj, 30);
            var fpDate = flatpickr('#approved_date', {
                minDate: "today"
            });
            fpDate.setDate(date);
        }

        /*$(document).on('change', '#applicant_relation', onRelationChange);*/

        function onRelationChange() {
            //var url = $(this).data('url');
            //var relationId = this.value;
            var url = $('#applicant_relation').data('url');
            var relationId = $('#applicant_relation').val();
            var studentId = $('input[name="student_id"]').val();
            $.ajax({
                url: url + '?student_id=' + studentId + '&relation_id=' + relationId,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        //Update relation name into text field
                        $('input[name="applicant_name"]').val(data.guardian_name);
                        $('input[name="guardian_id"]').val(data.id);
                        //$('input[name="beneficiary_id"]').val(data.id);
                        $('.applicant_relation_field_container .invalid-tooltip').html(
                            'Application relation is required').hide();
                    } else {
                        $('.applicant_relation_field_container .invalid-tooltip').html(
                            'Selected relation not available is system!').show();
                        //Update relation name into text field
                        $('input[name="applicant_name"]').val('');
                        $('input[name="guardian_id"]').val('');
                        //$('input[name="beneficiary_id"]').val(data.id);

                    }
                }
            })
        }

        $(document).on('input', '.load-student', onStudentChange);

        function onStudentChange() {

            //var value = $(this).val();
            //var url = $(this).data('url');
            var value = $('#studentRollNo').val();
            var url = $('#studentRollNo').data('student-url');

            $.ajax({
                url: url + '?id=' + value,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        $('input[name="student_name"]').val(
                            `${data.student.first_name} ${data.student.middle_name ? `${data.student.middle_name} ` : ''}${data.student.last_name}`
                        );
                        $('input[name="admission_date"]').val(data.student.admission_wef);
                        //console.log(data.active_class.branch_class_sections.sections.section_name);
                        $('input[name="class"]').val(data.student.active_class.branch_class_sections.com_classes
                            .class_name);
                        $('input[name="section"]').val(data.student.active_class.branch_class_sections.sections
                            .section_name);
                        $('input[name="branch"]').val(data.student.branch.br_name);

                        $('input[name="student_id"]').val(data.student.id);
                        $('input[id="branch_id"]').val(data.student.branch.id);
                        $('input[id="class_id"]').val(data.student.active_class.branch_class_sections
                            .com_classes.id);
                        $('input[id="section_id"]').val(data.student.active_class.branch_class_sections.sections
                            .id);
                        $('input[id="branch_class_section_id"]').val(data.student.active_class
                            .branch_class_sections.id);
                        $('input[id="academic_year_id"]').val(data.student.active_class.academic_years.title);
                        $('input[id="academic_year"]').val(data.student.active_class.academic_year_id);

                        var securityAmount = data.security_amount;
                        //Update security amount
                        $('input[name="security_amount"]').val(securityAmount);
                        //remove comma from numbers
                        securityAmount = parseFloat(securityAmount.toString().replace(/,/g, ''));
                        //Check if security amount is greater then zero to select drop down for refund status to unpaid
                        if (securityAmount > 0) {
                            $('#refund_status').val('unpaid');
                        } else {
                            $('#refund_status').val('');
                        }

                        //Update paid dues section
                        $('input[name="last_paid_amount"]').val(data.last_paid_invoice.total);
                        $('input[name="last_paid_date"]').val(data.last_paid_invoice.validity);
                        $('input[name="last_paid_fee_period"]').val(data.last_paid_invoice.period);

                        //Update unpaid dues section
                        $('input[name="last_unpaid_amount"]').val(data.last_unpaid_invoice.total);
                        $('input[name="unpaid_validity_date"]').val(data.last_unpaid_invoice.validity);
                        $('input[name="last_unpaid_fee_period"]').val(data.last_unpaid_invoice.period);
                        //triger the event to get branches
                        $("#branch_id").trigger('change');
                        $('.fetch_report').attr('disabled', false);
                    }
                }
            })
        }
        $(document).ready(function() {
            $(document).on('change', '.load-student', function() {
                var studentRollNo = $("#studentRollNo").val();
            });
            $(document).on('change', '.filter', function() {
                $('#invoices-data-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush

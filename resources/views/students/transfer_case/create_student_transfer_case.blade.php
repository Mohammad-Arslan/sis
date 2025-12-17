@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Create Transfer Case</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div id="student-not-found-message" class="d-none alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Transfer Form</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('student-transfer-case.index') }}" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-file-list-3-fill label-icon align-middle fs-16 me-2"></i> Transfer List
                        </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <h5 class="text-muted d-flex align-items-center mb-3"><i
                            class="ri-folder-shield-2-fill me-1"></i>Student
                        Information</h5>
                    <div class="live-preview">
                        @if (isset($studentTransferCase))
                            <form class="row g-3 needs-validation" novalidate method="POST"
                                action="{{ route('student-transfer-case.update', $studentTransferCase->id) }}">
                                @csrf
                                @method('PUT')
                            @else
                                <form class="row g-3 needs-validation" novalidate method="POST"
                                    action="{{ route('student-transfer-case.store') }}">
                                    @csrf
                        @endif
                        <!-- Student info section start -->

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number"
                                    class="form-control load-student @if ($errors->has('student_roll_no')) is-invalid @endif"
                                    id="studentRollNo" name="student_roll_no" placeholder="Please enter roll number"
                                    value="{{ isset($studentTransferCase) ? $student->roll_no : old('student_roll_no') }}"
                                    {{ isset($studentTransferCase) ? 'readonly' : '' }}
                                    data-url="{{ route('get-student') }}" required>
                                <label for="studentRollNo" class="form-label">Student Id <span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('student_roll_no'))
                                        {{ $errors->first('student_roll_no') }}
                                    @else
                                        Student Id is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('student_name')) is-invalid @endif"
                                    id="studentName" name="student_name" placeholder="Please enter student name" readonly
                                    value="{{ old('student_name') }}">
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

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('security_amount')) is-invalid @endif"
                                    id="securityAmount" name="security_amount" placeholder="Please enter clearance amount"
                                    value="{{ old('security_amount') }}" disabled>
                                <label for="securityAmount" class="form-label">Security Amount </label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('security_amount'))
                                        {{ $errors->first('security_amount') }}
                                    @else
                                        Security Amount is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('admission_date')) is-invalid @endif"
                                    id="admissionDate" name="admission_date" placeholder="Please enter student name"
                                    readonly value="{{ old('admission_date') }}">
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

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('class')) is-invalid @endif" id="class"
                                    name="class" placeholder="Please enter student class" readonly
                                    value="{{ old('class') }}">
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

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('section')) is-invalid @endif" id="section"
                                    name="section" placeholder="Please enter student section" readonly
                                    value="{{ old('section') }}">
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

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                    class="form-control @if ($errors->has('branch')) is-invalid @endif"
                                    id="branch" name="branch" placeholder="Please enter student branch" readonly
                                    value="{{ old('branch') }}">
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
                        <!-- Student info section end -->

                        <!-- Dues info section start -->

                        <div class="border mt-3 border-dashed"></div>
                        <h5 class="text-muted d-flex align-items-center"><i class="ri-folder-shield-2-fill me-1"></i>Dues
                            Info</h5>

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
                        <!-- Dues info section end -->

                        <input type="hidden" name="student_id"
                            value="@if (isset($studentTransferCase->student_id)) {{ $studentTransferCase->student_id }}@else{{ old('student_id') }} @endif" />

                        <div class="border mt-3 border-dashed"></div>
                        <h5 class="text-muted d-flex align-items-center"><i
                                class="ri-folder-shield-2-fill me-1"></i>Create Transfer Case</h5>

                        <input type="hidden" name="from_branch" value="{{ old('from_branch') }}">

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select
                                    class="form-select load-select @if ($errors->has('state_id')) is-invalid @endif"
                                    id="stateId" name="state_id" aria-label="stateId select" data-target="branch_to"
                                    data-url="{{ route('list-branches-by-state') }}" required>
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ (isset($studentTransferCase) ? $studentTransferCase->state_id : old('state_id')) == $state->id ? 'selected' : '' }}>
                                            {{ $state->state_name }}</option>
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

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('to_branch')) is-invalid @endif"
                                    id="branch_id" name="branch_to" aria-label="Branch select" required>
                                    <option value="">Please select</option>
                                    @if (isset($branch))
                                        <option value="{{ $branch['id'] }}">
                                            {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
                                    @else
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ (isset($studentTransferCase) ? $studentTransferCase->to_branch : old('to_branch')) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="branch_id" class="form-label">To Branch <span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('to_branch'))
                                        {{ $errors->first('to_branch') }}
                                    @else
                                        Branch is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('transfer_reason_id')) is-invalid @endif"
                                    id="transfer_reason_id" name="transfer_reason_id" aria-label="Branch select"
                                    required>
                                    <option value="">Please select</option>

                                    @foreach ($transfer_reasons as $transfer_reason)
                                        <option value="{{ $transfer_reason->id }}"
                                            {{ (isset($studentTransferCase) ? $studentTransferCase->transfer_reason_id : old('transfer_reason_id')) == $transfer_reason->id ? 'selected' : '' }}>
                                            {{ $transfer_reason->transfer_reason }}</option>
                                    @endforeach

                                </select>
                                <label for="branchId" class="form-label">Transfer Reasons<span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('transfer_reason'))
                                        {{ $errors->first('transfer_reason') }}
                                    @else
                                        Transfer Reasons is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                    id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}"
                                            {{ (isset($studentTransferCase) ? $studentTransferCase->academic_year_id : old('academic_year_id')) == $academic_year->id ? 'selected' : '' }}>
                                            {{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year<span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('academic_year_id'))
                                        {{ $errors->first('academic_year_id') }}
                                    @else
                                        Academic Year is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('request_date')) is-invalid @endif"
                                        {{ !isset($studentTransferCase) ? 'data-provider="flatpickr"' : null }}
                                        data-date-format={{ !isset($studentTransferCase) ? 'd-m-Y' : 'd-m-Y' }}
                                        data-altFormat={{ !isset($studentTransferCase) ? 'd-m-Y' : 'd-m-Y' }}
                                        data-deafult-date=""
                                        value="{{ isset($studentTransferCase) ? $studentTransferCase->request_date : old('request_date') }}"
                                        name="request_date" id="requestDate" readonly>
                                    <label for="requestDate" class="form-label">Request Date<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('request_date'))
                                            {{ $errors->first('request_date') }}
                                        @else
                                            Request Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('transfer_wef')) is-invalid @endif"
                                        {{ !isset($studentTransferCase) ? 'data-provider="flatpickr"' : null }}
                                        data-date-format={{ !isset($studentTransferCase) ? 'd-m-Y' : 'd-m-Y' }}
                                        data-altFormat={{ !isset($studentTransferCase) ? 'd-m-Y' : 'd-m-Y' }}
                                        value="{{ isset($studentTransferCase) ? $studentTransferCase->transfer_wef : old('transfer_wef') }}"
                                        name="transfer_wef" id="transferWEFDate" readonly>
                                    <label for="transferWEFDate" class="form-label">Transfer WEF <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('transfer_wef'))
                                            {{ $errors->first('transfer_wef') }}
                                        @else
                                            Transfer WEF is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('joining_date')) is-invalid @endif"
                                        {{ !isset($studentTransferCase) ? 'data-provider="flatpickr"' : null }}
                                        data-date-format={{ !isset($studentTransferCase) ? 'd-m-Y' : '' }}
                                        data-altFormat={{ !isset($studentTransferCase) ? 'd-m-Y' : '' }}
                                        value="{{ isset($studentTransferCase) ? $studentTransferCase->joining_date : old('joining_date') }}"
                                        name="joining_date" id="joiningDate" readonly>
                                    <label for="joiningDate" class="form-label">Joining Date <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('joining_date'))
                                            {{ $errors->first('joining_date') }}
                                        @else
                                            Joining Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-label-group in-border">
                                <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                    placeholder="Please enter your street address">{{ isset($studentTransferCase) ? $studentTransferCase->remarks : old('remarks') }}</textarea>
                                <label for="perAddress" class="form-label">Remarks </label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('remarks'))
                                        {{ $errors->first('remarks') }}
                                    @else
                                        Remarks is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit"
                                {{ isset($studentTransferCase) && $studentTransferCase->status != 'PENDING' ? 'disabled' : '' }}>Submit
                                form</button>
                            <a type="button" class="btn btn-light bg-gradient waves-effect waves-light"
                                href="{{ route('student-transfer-case.index') }}">Cancel</a>
                        </div>
                        </form>
                    </div>
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
            if ($('#studentRollNo').val() != '') {
                onStudentChange();
            }

        });

        $(document).on('change', '#requestDate', onApplicationDateChange);

        $("#requestDate").flatpickr({
            // minDate: "today",
            // maxDate: new Date().fp_incr(7) // 7 days from now
            maxDate: 'today',
        });

        function onApplicationDateChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            const newDate = new Date(aplicationDateObj);
            newDate.setDate(1);
            newDate.setMonth(newDate.getMonth() + 1);
            var d = newDate.getDate();
            var m = newDate.getMonth();
            m += 1; // JavaScript months are 0-11
            var y = newDate.getFullYear();
            // alert(newDate)
            $('#transferWEFDate').val(d + "-" + m + "-" + y);
            var fpDateJD = flatpickr('#joiningDate', {
                minDate: newDate
            })
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this,
                    args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }

        // Debounced version of onStudentChange with 5 seconds (5000ms)
        const debouncedOnStudentChange = debounce(onStudentChange, 5000);

        $(document).on('input', '.load-student', debouncedOnStudentChange);

        function onStudentChange() {
            var value = $('#studentRollNo').val();
            var url = $('#studentRollNo').data('url');

            // Clear previous not found message
            $('#student-not-found-message').removeClass('d-none');
            $('#student-not-found-message').addClass('d-block');
            $('#student-not-found-message').text('Record not found');

            if (!value) {
                // If input is empty, clear fields and message
                clearStudentFields();
                return;
            }

            $.ajax({
                url: url + '?id=' + value,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        // Found student, clear not found message
                        $('#student-not-found-message').text('');
                        $('input[name="student_id"]').val(data.student.id);
                        $('input[name="student_name"]').val(
                            `${data.student.first_name} ${data.student.middle_name ? `${data.student.middle_name} ` : ''}${data.student.last_name}`
                        );
                        $('input[name="admission_date"]').val(data.student.admission_wef);
                        $('input[name="class"]').val(data.student.active_class.branch_class_sections.com_classes
                            .class_name);
                        $('input[name="section"]').val(data.student.active_class.branch_class_sections.sections
                            .section_name);
                        $('input[name="branch"]').val(data.student.branch.br_name);
                        $('input[name="from_branch"]').val(data.student.branch.id);

                        var securityAmount = data.security_amount;
                        $('input[name="security_amount"]').val(securityAmount);
                        securityAmount = parseFloat(securityAmount.toString().replace(/,/g, ''));
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
                    } else {
                        // Not found
                        clearStudentFields();
                        $('#student-not-found-message').text('Record not found');
                    }
                },
                error: function() {
                    clearStudentFields();
                    $('#student-not-found-message').text('Record not found');
                }
            });
        }

        // Helper to clear all student fields
        function clearStudentFields() {
            $('input[name="student_id"]').val('');
            $('input[name="student_name"]').val('');
            $('input[name="admission_date"]').val('');
            $('input[name="class"]').val('');
            $('input[name="section"]').val('');
            $('input[name="branch"]').val('');
            $('input[name="from_branch"]').val('');
            $('input[name="security_amount"]').val('');
            $('#refund_status').val('');
            $('input[name="last_paid_amount"]').val('');
            $('input[name="last_paid_date"]').val('');
            $('input[name="last_paid_fee_period"]').val('');
            $('input[name="last_unpaid_amount"]').val('');
            $('input[name="unpaid_validity_date"]').val('');
            $('input[name="last_unpaid_fee_period"]').val('');
        }
    </script>
@endpush

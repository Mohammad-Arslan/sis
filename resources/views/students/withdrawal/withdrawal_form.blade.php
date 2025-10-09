@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Create New Withdrawal</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Withdrawal Form</h4>
                    <div class="flex-shrink-0">
                        <a class="btn btn-sm btn-success-new btn-label waves-effect waves-light"
                            href="{{ route('students-withdrawal-form-create') }}">
                            <i class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Print Withdrawal Form
                        </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <h5 class="text-muted d-flex align-items-center mb-3"><i
                            class="ri-folder-shield-2-fill me-1"></i>Student
                        Information</h5>
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate method="POST"
                            action="{{ route('student-withdrawal.store') }}">

                            <!-- Student info section start -->

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="number"
                                        class="form-control load-student @if ($errors->has('student_roll_no')) is-invalid @endif"
                                        id="studentRollNo" name="student_roll_no" placeholder="Please enter Student ID"
                                        value="{{ old('student_roll_no', isset($withdrawalRequest->student->roll_no) ? $withdrawalRequest->student->roll_no : '') }}"
                                        data-url="{{ route('get-student') }}" required>
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
                                        readonly value="{{ old('student_name') }}">
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
                                        readonly value="{{ old('admission_date') }}">
                                    <label for="admissionDate" class="form-label">Admission Date</label>
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
                                        class="form-control @if ($errors->has('class')) is-invalid @endif"
                                        id="class" name="class" placeholder="Please enter student class" readonly
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
                                        class="form-control @if ($errors->has('section')) is-invalid @endif"
                                        id="section" name="section" placeholder="Please enter student section" readonly
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

                            <!-- Withdrawal info section start -->
                            <div class="border mt-3 border-dashed"></div>
                            <h5 class="text-muted d-flex align-items-center"><i
                                    class="ri-folder-shield-2-fill me-1"></i>Withdrawal Information</h5>



                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border applicant_relation_field_container">
                                    <select class="form-select @if ($errors->has('applicant_relation')) is-invalid @endif"
                                        id="applicant_relation" name="applicant_relation" aria-label="withdrawal select"
                                        data-url="{{ route('get-student-relation') }}" required>
                                        <option value="">Applicant Relation</option>
                                        @foreach ($relations as $relation)
                                            <option value="{{ $relation->id }}"
                                                @if (isset($withdrawalRequest->guardian->relation->id)) {{ $withdrawalRequest->guardian->relation->id == $relation->id ? 'selected' : '' }}
                                            @else
                                            {{ old('applicant_relation') == $relation->id ? 'selected' : '' }} @endif>
                                                {{ $relation->relation_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="applicant_relation" class="form-label">Applicant Relation <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('applicant_relation'))
                                            {{ $errors->first('applicant_relation') }}
                                        @else
                                            Application relation is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('applicant_name')) is-invalid @endif"
                                        id="applicant_name" name="applicant_name"
                                        placeholder="Please enter applicant name" readonly
                                        value="{{ old('applicant_name', isset($withdrawalRequest->guardian->guardian_name) ? $withdrawalRequest->guardian->guardian_name : '') }}">
                                    <label for="applicant_name" class="form-label">Applicant Name</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('applicant_name'))
                                            {{ $errors->first('applicant_name') }}
                                        @else
                                            Applicant name is required!
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('withdrawal_reason_id')) is-invalid @endif"
                                        id="withdrawal_reason_id" name="withdrawal_reason_id"
                                        aria-label="withdrawal reason select" required>
                                        <option value="">Please select a Withdrawal Reason</option>
                                        @foreach ($reasons as $reason)
                                            <option value="{{ $reason->id }}"
                                                @if (isset($withdrawalRequest->reason->withdrawal_reason)) {{ $withdrawalRequest->reason->id == $reason->id ? 'selected' : '' }}
                                                @else
                                                {{ old('withdrawal_reason_id') == $reason->id ? 'selected' : '' }} @endif>
                                                {{ $reason->withdrawal_reason }}</option>
                                        @endforeach
                                    </select>
                                    <label for="withdrawal_reason_id" class="form-label">Withdrawal Reason <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('withdrawal_reason_id'))
                                            {{ $errors->first('withdrawal_reason_id') }}
                                        @else
                                            Withdrawal Reason is required!
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                        <option value="">Please select</option>
                                        @foreach ($academic_years as $academic_year)
                                            <option value="{{ $academic_year->id }}"
                                                @if (old('academic_year_id', $academic_year->active == 1 ? $academic_year->id : null) == $academic_year->id) selected @endif>
                                                {{ $academic_year->title }} </option>
                                        @endforeach
                                    </select>
                                    <label for="academic_year_id" class="form-label">Academic Year <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('application_date')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                            value="{{ old('application_date') }}" name="application_date"
                                            id="applicationDate" required>
                                        <label for="applicationDate" class="form-label">Application Date <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('application_date'))
                                                {{ $errors->first('application_date') }}
                                            @else
                                                Application Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('last_day_at')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                            value="{{ old('last_day_at', isset($withdrawalRequest->last_day_at_school) ? \Carbon\Carbon::parse($withdrawalRequest->last_day_at_school)->format('d-m-Y') : '') }}"
                                            name="last_day_at" id="lastDayAt" required>
                                        <label for="lastDayAt" class="form-label">Last Day <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('last_day_at'))
                                                {{ $errors->first('last_day_at') }}
                                            @else
                                                Last Day is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('security_amount')) is-invalid @endif"
                                        id="securityAmount" name="security_amount"
                                        placeholder="Please enter clearance amount" value="{{ old('security_amount') }}"
                                        disabled>
                                    <label for="securityAmount" class="form-label">Security Amount </label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('security_amount'))
                                            {{ $errors->first('security_amount') }}
                                        @else
                                            Security Amount is required!
                                        @endif
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('refund_status')) is-invalid @endif"
                                        id="refund_status" name="refund_status" aria-label="withdrawal reason select"
                                        required>
                                        <option value="">Please select a Refund Status</option>
                                        <option value="paid" {{ old('refund_status') == 'paid' ? 'selected' : '' }}>Paid
                                        </option>
                                        <option value="unpaid" {{ old('refund_status') == 'unpaid' ? 'selected' : '' }}>
                                            Unpaid</option>
                                        <option value="n/a" {{ old('refund_status') == 'n/a' ? 'selected' : '' }}>Not
                                            Applicable</option>
                                    </select>
                                    <label for="refund_status" class="form-label">Refund Status <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('refund_status'))
                                            {{ $errors->first('refund_status') }}
                                        @else
                                            Refund Status is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 col-sm-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="libraryClearance"
                                        name="library_clearance" {{ old('library_clearance') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="libraryClearance">
                                        Library Clearance
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="number"
                                        class="form-control @if ($errors->has('clearance_amount')) is-invalid @endif"
                                        id="clearanceAmount" name="clearance_amount"
                                        placeholder="Please enter clearance amount" value="{{ old('clearance_amount') }}"
                                        @if (!old('library_clearance')) disabled @endif>
                                    <label for="clearanceAmount" class="form-label">Clearance Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('clearance_amount'))
                                            {{ $errors->first('clearance_amount') }}
                                        @else
                                            Clearance Amount is required!
                                        @endif
                                    </div>
                                </div>
                            </div> --}}


                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                        placeholder="Please enter your street address">{{ old('remarks') }}</textarea>
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
                            <!-- Withdrawal info section end -->

                            <!-- Dues info section start -->

                            <div class="border mt-3 border-dashed"></div>
                            <div class="align-items-center d-flex">
                                <h5 class="text-muted d-flex align-items-center mb-0 flex-grow-1"><i
                                        class="ri-folder-shield-2-fill"></i>Dues Info</h5>

                                <div class="flex-shrink-0">
                                    <a class="btn btn-success-new btn-sm" onclick="gotoinvoice()">
                                        Student Invoice
                                    </a>

                                </div>

                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('last_paid_amount')) is-invalid @endif"
                                        id="last_paid_amount" name="last_paid_amount" placeholder="Last Paid Amount"
                                        readonly value="{{ old('last_paid_amount') }}">
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
                                        placeholder="Unpaid Fee Period" readonly
                                        value="{{ old('last_unpaid_fee_period') }}">
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

                            <!-- Authorisation info section start -->
                            <div class="border mt-3 border-dashed"></div>
                            <h5 class="text-muted d-flex align-items-center"><i
                                    class="ri-folder-shield-2-fill me-1"></i>Authorisation</h5>
                            <p>This cross cheque for security refund, if any, can only be issued in the parents name. This
                                will be dispatched through registered mail to the address given below.</p>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('beneficiary_name')) is-invalid @endif"
                                        id="beneficiary_name" name="beneficiary_name"
                                        placeholder="Please enter beneficiary name"
                                        value="{{ old('beneficiary_name', isset($withdrawalRequest->beneficiary_name) ? $withdrawalRequest->beneficiary_name : '') }}">
                                    <label for="beneficiary_name" class="form-label">Beneficiary Name</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('beneficiary_name'))
                                            {{ $errors->first('beneficiary_name') }}
                                        @else
                                            Beneficiary name is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('beneficiary_cnic')) is-invalid @endif"
                                        id="beneficiary_cnic" name="beneficiary_cnic"
                                        placeholder="Please enter beneficiary CNIC"
                                        value="{{ old('beneficiary_cnic') }}">
                                    <label for="beneficiary_cnic" class="form-label">Beneficiary CNIC #</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('beneficiary_cnic'))
                                            {{ $errors->first('beneficiary_cnic') }}
                                        @else
                                            Beneficiary CNIC is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('beneficiary_phone')) is-invalid @endif"
                                        id="beneficiary_phone" name="beneficiary_phone"
                                        placeholder="Please enter beneficiary phone"
                                        value="{{ old('beneficiary_phone') }}">
                                    <label for="beneficiary_phone" class="form-label">Beneficiary Phone</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('beneficiary_phone'))
                                            {{ $errors->first('beneficiary_phone') }}
                                        @else
                                            Beneficiary phone is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" id="beneficiary_postal_address" name="beneficiary_postal_address" rows="2"
                                        placeholder="Please enter your street address">{{ old('beneficiary_postal_address') }}</textarea>
                                    <label for="beneficiary_postal_address" class="form-label">Postal Address </label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('beneficiary_postal_address'))
                                            {{ $errors->first('beneficiary_postal_address') }}
                                        @else
                                            Beneficiary address is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Authorisation info section end -->


                            <!-- Approval info section start -->
                            <div class="border mt-3 border-dashed"></div>
                            <h5 class="text-muted d-flex align-items-center"><i
                                    class="ri-folder-shield-2-fill me-1"></i>Approval Information</h5>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('approved_by')) is-invalid @endif"
                                        id="approvedBy" name="approved_by" aria-label="withdrawal reason select"
                                        required>
                                        <option value="">Please select an employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('approved_by') == $employee->user->id ? 'selected' : '' }}>
                                                {{ $employee->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="approved_by" class="form-label">Approved By <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('approved_by'))
                                            {{ $errors->first('approved_by') }}
                                        @else
                                            Approved By is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('approved_on')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                            value="{{ old('approved_on') }}" name="approved_date" id="approved_date"
                                            required>

                                        <label for="approved_date" class="form-label">Approved On <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('approved_date'))
                                                {{ $errors->first('approved_date') }}
                                            @else
                                                Application Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" id="approvalRemarks" name="approval_remarks" rows="2"
                                        placeholder="Please enter your street address">{{ old('approval_remarks') }}</textarea>
                                    <label for="approvalRemarks" class="form-label">Approval Remarks </label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('approval_remarks'))
                                            {{ $errors->first('approval_remarks') }}
                                        @else
                                            Approval Remarks is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Dues info section end -->


                            <input type="hidden" name="student_id"
                                value="{{ old('student_id', isset($withdrawalRequest->student_id) ? $withdrawalRequest->student_id : '') }}" />
                            <input type="hidden" name="guardian_id"
                                value="{{ old('guardian_id', isset($withdrawalRequest->guardian_id) ? $withdrawalRequest->guardian_id : '') }}" />
                            <input type="hidden" name="beneficiary_id" value="{{ old('beneficiary_id') }}" />
                            @csrf
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
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
            // Set the initial state of clearanceAmount based on the checkbox
            if (!$('#libraryClearance').is(':checked')) {
                $('#clearanceAmount').attr('disabled', true);
            } else {
                $('#clearanceAmount').removeAttr('disabled');
            }

            if ($('#studentRollNo').val() != '') {
                onStudentChange();
                onRelationChange();
            }
        });

        $(document).on('change', '#applicationDate', onApplicationDateChange);

        $("#applicationDate").flatpickr({
            maxDate: "today",
            // maxDate: new Date().fp_incr(7) // 7 days from now
        });

        $("#lastDayAt").flatpickr({
            maxDate: "today"
        });

        function onApplicationDateChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            var date = addDaysToDate(aplicationDateObj, 30);
            var fpDate = flatpickr('#lastDayAt', {
                maxDate: "today"
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

        $(document).on('change', '#applicant_relation', onRelationChange);

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
            var url = $('#studentRollNo').data('url');

            // Clear all fields if student ID is empty
            if (!value || value.trim() === '') {
                clearStudentFields();
                return;
            }

            $.ajax({
                url: url + '?id=' + value,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        console.log(data);
                        $('input[name="student_id"]').val(data.student.id);
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

                        var securityAmount = data.student.security_amount;
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

                        $('input[name="student_id"]').val(data.student.id);
                        //Update paid dues section
                        $('input[name="last_paid_amount"]').val(data.last_paid_invoice.total);
                        $('input[name="last_paid_date"]').val(data.last_paid_invoice.validity);
                        $('input[name="last_paid_fee_period"]').val(data.last_paid_invoice.period);

                        //Update unpaid dues section
                        $('input[name="last_unpaid_amount"]').val(data.last_unpaid_invoice.total);
                        $('input[name="unpaid_validity_date"]').val(data.last_unpaid_invoice.validity);
                        $('input[name="last_unpaid_fee_period"]').val(data.last_unpaid_invoice.period);
                    } else {
                        // Clear fields if student not found
                        clearStudentFields();
                    }
                },
                error: function() {
                    // Clear fields on error
                    clearStudentFields();
                }
            })

        }

        function clearStudentFields() {
            // Clear student information fields
            $('input[name="student_id"]').val('');
            $('input[name="student_name"]').val('');
            $('input[name="admission_date"]').val('');
            $('input[name="class"]').val('');
            $('input[name="section"]').val('');
            $('input[name="branch"]').val('');
            
            // Clear security amount and refund status
            $('input[name="security_amount"]').val('');
            $('#refund_status').val('');
            
            // Clear paid dues section
            $('input[name="last_paid_amount"]').val('');
            $('input[name="last_paid_date"]').val('');
            $('input[name="last_paid_fee_period"]').val('');
            
            // Clear unpaid dues section
            $('input[name="last_unpaid_amount"]').val('');
            $('input[name="unpaid_validity_date"]').val('');
            $('input[name="last_unpaid_fee_period"]').val('');
            
            // Clear applicant/guardian related fields
            $('input[name="applicant_name"]').val('');
            $('input[name="guardian_id"]').val('');
            $('#applicant_relation').val('');
        }

        function gotoinvoice() {
            var value = $('#studentRollNo').val();
            var url = $('#studentRollNo').data('url');

            $.ajax({
                url: url + '?id=' + value,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        console.log(data);
                        // $('input[name="student_id"]').val(data.student.id);
                        $student_id = data.student.id;
                        if ($student_id != null) {
                            console.log($student_id);
                            location.href = ("/students/" + $student_id + `/edit?tab=invoice`);
                        }

                    }
                }
            })


        }
    </script>
@endpush

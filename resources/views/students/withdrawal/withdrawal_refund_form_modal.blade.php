<div id="withdrawalRefundFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Withdrawal Refund Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="withdrawal-cancellation-form" method="POST"
                    action="{{ route('student-withdrawal-refund-store') }}">

                    <div class="row">
                        <div class="col-12">
                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Application Details</h6>
                                        <h6><span class="text-muted fw-normal">Application #:</span>
                                            {{ $withdrawalInfo->application_id }}
                                        </h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">&nbsp;</h6>
                                            <h6><span class="text-muted fw-normal">Application Date:</span>
                                                {{ \Carbon\Carbon::parse($withdrawalInfo->application_date)->format('d-m-Y') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Student Name</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $studentInfo->first_name }}&nbsp;{{ $studentInfo->middle_name }}&nbsp;{{ $studentInfo->last_name }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Student ID</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $studentInfo->roll_no }}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Branch / Class / Section
                                        </p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $studentInfo->branch->br_name }} /
                                            {{ $studentInfo->active_class->branch_class_sections->com_classes->class_name }}
                                            /
                                            {{ $studentInfo->active_class->branch_class_sections->sections->section_name }}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Guardian Name</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $withdrawalInfo->guardian->guardian_name }}
                                        </h5>
                                    </div>
                                    <!--end col-->

                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>

                            <div class="card-body p-4 border-top border-top-dashed">

                                <div class="row g-3">
                                    <div class="col-lg-6 col-6">
                                        <div class="form-label-group in-border">
                                            <input type="text"
                                                class="form-control @if ($errors->has('security_amount')) is-invalid @endif"
                                                disabled id="securityAmount" name="security_amount"
                                                placeholder="Please enter clearance amount"
                                                value="Rs. @if ($securityFees > 0) {{ $securityFees }} @else 0 @endif">
                                            <label for="securityAmount" class="form-label">Security Amount <span
                                                class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('security_amount'))
                                                    {{ $errors->first('security_amount') }}
                                                @else
                                                    Security Amount is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">

                                        <div class="form-label-group in-border">
                                            <input type="text"
                                                class="form-control @if ($errors->has('cheque_number')) is-invalid @endif"
                                                @if (!$securityFees > 0) disabled @else required @endif
                                                id="securityAmount" name="cheque_number"
                                                placeholder="Please enter clearance amount"
                                                value="{{ $withdrawalInfo->cheque_number }}">
                                            <label for="securityAmount" class="form-label">Cheque Number <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cheque_number'))
                                                    {{ $errors->first('cheque_number') }}
                                                @else
                                                    Cheque Number is required!
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->

                                <div class="row g-3">
                                    <div class="col-lg-6 col-6">
                                        <div class="form-label-group in-border">
                                            <input type="text"
                                                class="form-control @if ($errors->has('beneficiary_name')) is-invalid @endif"
                                                id="beneficiary_name" name="beneficiary_name"
                                                @if (!$securityFees > 0) disabled @else required @endif
                                                placeholder="Please enter beneficiary amount"
                                                value="{{ $withdrawalInfo->beneficiary_name }}">
                                            <label for="beneficiary_name" class="form-label">Issue To <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('beneficiary_name'))
                                                    {{ $errors->first('beneficiary_name') }}
                                                @else
                                                    Cheque Issue To is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">

                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @if ($errors->has('cheque_date')) is-invalid @endif"
                                                    @if (!$securityFees > 0) disabled @else required @endif
                                                    data-provider="flatpickr" data-date-format="d-m-Y"
                                                    data-altFormat="d-m-Y"
                                                    value="{{ \Carbon\Carbon::parse($withdrawalInfo->cheque_date)->format('d-m-Y') }}"
                                                    name="cheque_date" id="cheque_date">
                                                <label for="cheque_date" class="form-label">Issue Date <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('cheque_date'))
                                                        {{ $errors->first('cheque_date') }}
                                                    @else
                                                        Issue Date is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-label-group in-border">
                                            <textarea class="form-control" id="refund_remarks" name="refund_remarks" rows="2"
                                                @if (!$securityFees > 0) disabled @else required @endif placeholder="Please enter your street address">{{ $withdrawalInfo->refund_remarks }}</textarea>
                                            <label for="refund_remarks" class="form-label">Refund Remarks <span
                                                class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('refund_remarks'))
                                                    {{ $errors->first('refund_remarks') }}
                                                @else
                                                    Refund Remarks is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!--end col-->
                                </div>
                                <!--end row-->



                            </div>

                            <!--end card-body-->
                            <div class="hstack gap-2 justify-content-end d-print-none">
                                {{-- }}<button onclick="updatePaymentStatus({{$student_invoice->id}})">
                                    Click!
                                </button>{{-- }}
                                <button type="submit" class="btn btn-success"
                                    @if (!$securityFees > 0) disabled @endif>
                                    <i class="md md-content-save align-bottom me-1"></i>Submit
                                </button>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    <input type="hidden" name="withdrawal_record_id" value="{{ $withdrawalInfo->id }}" />
                    {{ csrf_field() }}
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $("#cheque_date").flatpickr({
        //minDate: "today"
    });
</script>

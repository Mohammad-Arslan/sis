<div id="transferCancellationFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Transfer Case Cancellation Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="transfer-cancellation-form" method="POST"
                    action="{{ route('student-transfer-cancellation-store') }}">

                    <div class="row">
                        <div class="col-12">
                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Application Details</h6>
                                        <h6><span class="text-muted fw-normal">Application #:</span>
                                            {{ $transferCase->application_id }}
                                        </h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">&nbsp;</h6>
                                            <h6><span class="text-muted fw-normal">Application Date:</span>
                                                {{ \Carbon\Carbon::parse($transferCase->application_date)->format('d-m-Y') }}
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

                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">From Branch / Class /
                                            Section
                                        </p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $studentInfo->branch->br_name }} /
                                            {{ $studentInfo->active_class->branch_class_sections->com_classes->class_name }}
                                            /
                                            {{ $studentInfo->active_class->branch_class_sections->sections->section_name }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">To Branch
                                        </p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $transferCase->to_branch_model->br_name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4 border-top border-top-dashed">

                                <div class="row g-3">
                                    {{-- <div class="col-lg-3 col-6">
                                        <div class="form-label-group in-border">
                                            <input type="text" class="form-control" id="cancellation_by"
                                                name="cancellation_by" placeholder="Please enter name" value=""
                                                required>
                                            <label for="studentName" class="form-label">Cancellation By <span
                                                    class="text-danger">*</span></label>

                                        </div>

                                    </div> --}}
                                    <div class="col-lg-4 col-6">
                                        <div class="form-label-group in-border">
                                            <input type="hidden" id="cancelled_by" name="cancelled_by"
                                                value="{{ Auth::user()->id }}">
                                            <input type="text"
                                                class="form-control @if ($errors->has('cancelled_by')) is-invalid @endif"
                                                id="cancelled_by" name="" placeholder="Please enter"
                                                value="{{ Auth::user()->name }}" disabled>
                                            {{-- <select
                                                class="form-select @if ($errors->has('cancelled_by')) is-invalid @endif"
                                                id="cancelled_by" name="cancelled_by" aria-label="withdrawal reason select"
                                                required>
                                                <option value="">Please select an employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ old('cancelled_by') == $employee->user->id ? 'selected' : '' }}>
                                                        {{ $employee->user->name }}</option>
                                                @endforeach
                                            </select> --}}
                                            <label for="cancelled_by" class="form-label">Cancelled By <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancelled_by'))
                                                    {{ $errors->first('cancelled_by') }}
                                                @else
                                                    Cancelled By is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-6">

                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @if ($errors->has('cancellation_date')) is-invalid @endif"
                                                    data-provider="flatpickr" data-date-format="d-m-Y"
                                                    data-altFormat="d-m-Y" value="{{ old('cancellation_date') }}"
                                                    name="cancellation_date" id="cancellation_date" required>
                                                <label for="cancellation_date" class="form-label">Cancellation Date
                                                    <span class="text-danger">*</span></label>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('cancellation_date'))
                                                        {{ $errors->first('cancellation_date') }}
                                                    @else
                                                        Cancellation Date is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="form-label-group in-border">
                                            <select
                                                class="form-select @if ($errors->has('cancel_reason')) is-invalid @endif"
                                                id="cancel_reason" name="cancel_reason"
                                                aria-label="withdrawal reason select" required>
                                                <option value="">Please select an reason</option>
                                                <option value="Cancellation of Transfer">Cancellation of Transfer
                                                </option>
                                                <option value="Payment of Arrears">Payment of Arrears</option>
                                                <option value="Re-admission">Re-admission</option>
                                                <option value="Repeat Class">Repeat Class</option>
                                                <option value="Temporary Cancellation">Temporary Cancellation</option>
                                                {{-- <option value="Wrongly Withdrawn">Wrongly Withdrawn</option> --}}
                                            </select>
                                            <label for="cancel_reason" class="form-label">Cancellation Reason
                                                <span class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancel_reason'))
                                                    {{ $errors->first('cancel_reason') }}
                                                @else
                                                    Cancellation Reason is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-label-group in-border">
                                            <textarea class="form-control" id="cancelRemarks" name="cancellation_remarks" rows="2"
                                                placeholder="Please enter your street address">{{ old('cancellation_remarks') }}</textarea>
                                            <label for="cancelRemarks" class="form-label">Cancel Remarks </label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancellation_remarks'))
                                                    {{ $errors->first('cancellation_remarks') }}
                                                @else
                                                    Cancel Remarks is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end d-print-none">
                                <button type="submit" class="btn btn-success">
                                    <i class="md md-content-save align-bottom me-1"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="transfer_record_id" value="{{ $transferCase->id }}" />
                    {{ csrf_field() }}
                </form>
            </div>


        </div>
    </div>
</div>
<script type="text/javascript">
    $("#cancellation_date").flatpickr({
        minDate: "today",
        defaultDate: "today"
    });
</script>

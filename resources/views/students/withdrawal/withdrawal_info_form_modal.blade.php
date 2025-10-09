<a type="button" class="text-secondary show-modal" data-url="{{ route('student-invoices.show', $row['id']) }}"
   data-target="#invoiceDetailModal">{{ $row['invoice_no'] }}</a>

<div id="withdrawalApprovalFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Withdrawal Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="withdrawal-cancellation-form" method="POST" action="{{ route('student-withdrawal-approval-store') }}">

                    <div class="row">
                        <div class="col-12">
                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Application Details</h6>
                                        <h6><span class="text-muted fw-normal">Application #:</span>
                                            {{$withdrawalInfo->application_id}}
                                        </h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">&nbsp;</h6>
                                            <h6><span class="text-muted fw-normal">Application Date:</span>
                                                {{ \Carbon\Carbon::parse( $withdrawalInfo->application_date )->format('d-m-Y') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Student Name</p>
                                        <h5 class="fs-14 mb-0">{{$studentInfo->first_name}}&nbsp;{{$studentInfo->middle_name}}&nbsp;{{$studentInfo->last_name}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Student ID</p>
                                        <h5 class="fs-14 mb-0">
                                            {{$studentInfo->roll_no}}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Class / Section / Branch</p>
                                        <h5 class="fs-14 mb-0">
                                            {{$studentInfo->active_class->branch_class_sections->com_classes->class_name}} / {{$studentInfo->active_class->branch_class_sections->sections->section_name}} / {{$studentInfo->branch->br_name}}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Guardian Name</p>
                                        <h5 class="fs-14 mb-0">
                                            {{$withdrawalInfo->guardian->guardian_name}}
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
                                            <select class="form-select @if ($errors->has('approved_by')) is-invalid @endif"
                                                    id="approvedBy" name="approved_by" aria-label="withdrawal reason select" required>
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
                                    <div class="col-lg-6 col-6">

                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control @if ($errors->has('approved_date')) is-invalid @endif"
                                                       data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                                       value="{{ old('approved_date') }}" name="approved_date"
                                                       id="approved_date" required>
                                                <label for="approved_date" class="form-label">Approved On <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('approved_date'))
                                                        {{ $errors->first('approved_date') }}
                                                    @else
                                                        Approved Date is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>

                            <div class="card-body p-4 border-top border-top-dashed">

                                <div class="row g-3">

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

                                </div>
                            </div>

                            <!--end card-body-->
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                {{--}}<button onclick="updatePaymentStatus({{$student_invoice->id}})">
                                    Click!
                                </button>{{--}}
                                <button type="submit" class="btn btn-success">
                                    <i class="md md-content-save align-bottom me-1"></i>Submit
                                </button>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    <input type="hidden" name="withdrawal_record_id" value="{{$withdrawalInfo->id}}" />
                    {{csrf_field()}}
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
        $("#approved_date").flatpickr({
            minDate: "today"
        });
</script>

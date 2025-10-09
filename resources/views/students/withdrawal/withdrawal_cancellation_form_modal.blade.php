<div id="withdrawalCancellationFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Withdrawal Cancellation Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="withdrawal-cancellation-form" method="POST" action="{{ route('student-withdrawal-cancellation-store') }}">

                    <div class="row">
                        <div class="col-12">
                            {{--}}<div class="card-header border-bottom-dashed p-4">
                                <div class="d-sm-flex">
                                    <div class="flex-grow-1">
                                        <img src="{{ asset('ucs-logo.png') }}" class="card-logo card-logo-dark"
                                             alt="logo dark" height="100">
                                        <img src="{{ asset('ucs-logo.png') }}" class="card-logo card-logo-light"
                                             alt="logo light" height="100">
                                    </div>
                                </div>
                            </div>{{--}}
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
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Branch / Class / Section</p>
                                        <h5 class="fs-14 mb-0">
                                            {{$studentInfo->branch->br_name}} / {{$studentInfo->active_class->branch_class_sections->com_classes->class_name}} / {{$studentInfo->active_class->branch_class_sections->sections->section_name}}
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
                                    <div class="col-lg-3 col-6">
                                        <div class="form-label-group in-border">
                                            <input type="text"
                                                   class="form-control" id="cancellation_by" name="cancellation_by" placeholder="Please enter name" value="" required>
                                            <label for="studentName" class="form-label">Cancellation By <span class="text-danger">*</span></label>

                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-6">

                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control @if ($errors->has('cancellation_date')) is-invalid @endif"
                                                       data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                                       value="{{ old('cancellation_date') }}" name="cancellation_date"
                                                       id="cancellation_date" required>
                                                <label for="cancellation_date" class="form-label">Cancellation Date <span
                                                        class="text-danger">*</span></label>
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
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <div class="form-label-group in-border">
                                            <select class="form-select @if ($errors->has('cancellation_reason')) is-invalid @endif"
                                                    id="cancellation_reason" name="cancellation_reason"
                                                    aria-label="withdrawal reason select" required>
                                                    <option value="">Please select an reason</option>
                                                    <option value="Cancellation of Transfer">Cancellation of Transfer</option>
                                                    <option value="Payment of Arrears">Payment of Arrears</option>
                                                    <option value="Re-admission">Re-admission</option>
                                                    <option value="Repeat Class">Repeat Class</option>
                                                    <option value="Temporary Cancellation">Temporary Cancellation</option>
                                                    <option value="Wrongly Withdrawn">Wrongly Withdrawn</option>
                                            </select>
                                            <label for="cancellation_reason" class="form-label">Cancellation Reason <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancellation_reason'))
                                                    {{ $errors->first('cancellation_reason') }}
                                                @else
                                                    Cancellation Reason is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <div class="form-label-group in-border">
                                            <select class="form-select @if ($errors->has('cancellation_approved_by')) is-invalid @endif"
                                                    id="approvedBy" name="cancellation_approved_by"
                                                    aria-label="withdrawal reason select" required>
                                                <option value="">Please select an employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ old('cancellation_approved_by') == $employee->user->id ? 'selected' : '' }}>
                                                        {{ $employee->user->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="cancellation_approved_by" class="form-label">Approved By <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancellation_approved_by'))
                                                    {{ $errors->first('cancellation_approved_by') }}
                                                @else
                                                    Approved By is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <!--end col-->
                                </div>
                                <!--end row-->

                                <div class="row g-3">

                                    <div class="col-md-12">
                                        <div class="form-label-group in-border">
                                            <textarea class="form-control" id="approvalRemarks" name="cancellation_approved_remarks" rows="2"
                                                      placeholder="Please enter your street address">{{ old('cancellation_approved_remarks') }}</textarea>
                                            <label for="approvalRemarks" class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cancellation_approved_remarks'))
                                                    {{ $errors->first('cancellation_approved_remarks') }}
                                                @else
                                                    Approval Remarks is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!--end card-body-->
                            <div class="hstack gap-2 justify-content-end d-print-none">
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
        //$(document).on('change', '#cancellation_date', onApplicationCancelDateChange);

        $("#cancellation_date").flatpickr({
            minDate: "today"
        });

        // Add form validation for the cancellation date field
        const form = document.querySelector('#withdrawal-cancellation-form');
        form.addEventListener('submit', function (e) {
            const cancellationDate = document.getElementById('cancellation_date');
            const cancellationDateValue = cancellationDate.value.trim();
            
            // Remove existing validation classes
            cancellationDate.classList.remove('is-invalid');
            
            // Check if cancellation date is empty
            if (!cancellationDateValue) {
                e.preventDefault();
                cancellationDate.classList.add('is-invalid');
                
                // Show validation message
                const invalidTooltip = cancellationDate.closest('.form-label-group').querySelector('.invalid-tooltip');
                if (invalidTooltip) {
                    invalidTooltip.style.display = 'block';
                }
            }
        });

        // Clear validation on input
        document.getElementById('cancellation_date').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const invalidTooltip = this.closest('.form-label-group').querySelector('.invalid-tooltip');
            if (invalidTooltip) {
                invalidTooltip.style.display = 'none';
            }
        });

</script>

<div id="requestApprovalFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Promotion Request Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="withdrawal-cancellation-form" method="POST"
                    action="{{ route('student-promotion-approval-store') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">From</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Academic Year :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->prev_academic_year->title }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Branch :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->prev_branch->br_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Class :</th>
                                                    <td class="text-muted">{{ $promoRequest->prev_class->class_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Section :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->prev_section->section_name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">To </h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Academic Year :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->cur_academic_year->title }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Branch :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->cur_branch->br_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Class :</th>
                                                    <td class="text-muted">{{ $promoRequest->cur_class->class_name ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Section :</th>
                                                    <td class="text-muted">
                                                        {{ $promoRequest->cur_section->section_name ?? '' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-6">
                            <div class="form-label-group in-border">
                                <input type="hidden" id="approvedBy" name="approved_by" value="{{ Auth::user()->id }}">
                                <input type="text"
                                    class="form-control @if ($errors->has('approved_by')) is-invalid @endif"
                                    id="approvedBy" name="" placeholder="Please enter"
                                    value="{{ Auth::user()->name }}" disabled>
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
                                        value="{{ old('approved_date') }}" name="approved_date" id="approved_date"
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
                                            Approved Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end col-->
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
                    <!--end card-body-->
                    <input type="hidden" name="record_id" value="{{ $promoRequest->id }}" />
                    {{ csrf_field() }}
                    <div class="hstack justify-content-end ">
                        <button type="submit" class="btn btn-success">
                            <i class="md md-content-save align-bottom me-1"></i>Submit
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#approved_date").flatpickr({
        minDate: "today",
        defaultDate: "today"
    });
</script>

<div id="transferDetailsModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Transfer Case Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body border-top border-top-dashed">
                            <div class="row  p-4  g-3">
                                <div class="col-sm-6">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Application Details</h6>
                                    <h6><span class="text-muted fw-normal">Application #:</span>
                                        {{ $transferInfo->application_id }}
                                    </h6>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">&nbsp;</h6>
                                    <h6><span class="text-muted fw-normal">Application Date:</span>
                                        {{ \Carbon\Carbon::parse($transferInfo->application_date)->format('d-m-Y') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top border-top-dashed">
                            <div class="row g-3 p-4 ">
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
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Branch / Class / Section</p>
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
                                        {{ $transferInfo->to_branch_model->br_name }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-top border-top-dashed">
                            <div class="row g-3 p-4 border-top border-top-dashed">
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Approved By</p>
                                    <h5 class="fs-14 mb-0">
                                        @if (isset($approvedInfo->user->name))
                                            {{ $approvedInfo->user->name }}
                                        @else
                                            None
                                        @endif
                                    </h5>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Approved On</p>
                                    <h5 class="fs-14 mb-0">
                                        {{ isset($transferInfo->approved_date) ? \Carbon\Carbon::parse($transferInfo->approved_date)->format('d-m-Y') : 'None' }}
                                    </h5>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Approval Remarks</p>
                                    <h5 class="fs-14 mb-0">
                                        {{ isset($transferInfo->approval_remarks) ? $transferInfo->approval_remarks : 'None' }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#approved_date").flatpickr({
        minDate: "today"
    });
</script>

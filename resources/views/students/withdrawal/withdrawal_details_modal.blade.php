<div id="withdrawalDetailsModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Withdrawal Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" id="withdrawal-cancellation-form" method="POST"
                    action="{{ route('student-withdrawal-approval-store') }}">

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

                                <div class="row g-3" style="margin-top: 10px">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Approved By</p>
                                        <h5 class="fs-14 mb-0">
                                            @if (isset($approvedInfo->preferred_name))
                                                {{ $approvedInfo->preferred_name }}
                                            @else
                                                None
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Approved On</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ \Carbon\Carbon::parse($withdrawalInfo->approved_date)->format('d-m-Y') }}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Approval Remarks</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $withdrawalInfo->approval_remarks }}
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <!--<div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Guardian Name</p>
                                        <h5 class="fs-14 mb-0">
                                            {{ $withdrawalInfo->guardian->guardian_name }}
                                        </h5>
                                    </div>-->
                                    <!--end col-->
                                    <!--end col-->
                                </div>
                                <!--end row-->

                            </div>

                            <!--end card-body-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    {{ csrf_field() }}
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

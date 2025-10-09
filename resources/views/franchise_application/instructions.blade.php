<div class="col-xl-12" id="card-none3">
    <div class="card">
        <div class="card-light card-header">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-0">Franchise Application Instructions</h6>
                </div>
                @if(isset($franchise) && auth()->user())
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm pull-right copyURL">Copy Public Link</a>
                    <a href="javascript:void(0);" class="btn btn-success btn-sm pull-right copiedBtn" style="display:none;">Copied</a>
                    <input type="text" value="{{route('guest-franchise-applications.edit',$franchise->id)}}" id="urlText" style="display: none">
                @endif
            </div>
        </div>
        <div class="card-body collapse show">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-fill text-success"></i>
                </div>
                <div class="flex-grow-1 ms-2 text-muted">
                    UCS exclusively reserves the rights to reject this application without reason.
                </div>
            </div>
            <div class="d-flex mt-2">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-fill text-success"></i>
                </div>
                <div class="flex-grow-1 ms-2 text-muted">
                    Approval of the application is subject to the post visit evaluation report of the UCS Team
                </div>
            </div>
            <div class="d-flex mt-2">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-fill text-success"></i>
                </div>
                <div class="flex-grow-1 ms-2 text-muted">
                    One franchise application is entertainable for each desired city/town
                </div>
            </div>
            <div class="d-flex mt-2">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-fill text-success"></i>
                </div>
                <div class="flex-grow-1 ms-2 text-muted">
                    <p>Important documents (To be annexed herewith):</p>
                    <ol>
                        <li>Bank Draft of Rs.5000 drawn in favour of the Educational Services Pvt.Ltd as non-refundable scrutiny/process fee against each application</li>
                        {{--<li>Original & Photocopy of the Franchise Application Form.</li>--}}
                        <li>Attach your updated CV and Business Card</li>
                        <li>Copy of computerised CNIC</li>
                        <li>Bank statement of last 6 months.</li>
                        <li>Copy of Board Resolution/Authority Letter in case of applying on behalf of company/firm</li>
                        <li>Copies of Ownership/Rental/Lease documents (In case availability of the site(s))</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane fade" id="v-pills-led-franchise" role="tabpanel" aria-labelledby="v-pills-led-franchise-tab">
    <div class="row g-3">
        <h4 class="flex-grow-1">Franchise Info</h4>
        <div class="flex-shrink-0">
            <button type="button" class="btn btn-success btn-label btn-sm" data-bs-toggle="modal" data-bs-target="#addFranchiseFormModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Record
            </button>
            <div class="table-responsive">
                <table class="table table-nowrap mb-0 " id="franchiseCompanyTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Company Name</th>
                            <th scope="col">Experience (No. of years):</th>
                            <th scope="col">Connected with any educational oganization ?</th>
                            <th scope="col">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="d-flex align-items-start gap-3 mt-4">
            <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-educational-organization-tab">
                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to Educational organization
            </button>
            <button type="button" class="btn btn-primary btn-label right ms-auto nexttab led-franchise-info " data-nexttab="v-pills-school-building-tab" >
                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to School Building
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="addFranchiseFormModal" tabindex="-1" aria-labelledby="addFranchiseFormModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFranchiseFormModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  method="POST" class="row g-3 needs-validation" novalidate id="addFranchiseForm">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="companyName" name="company_name" placeholder="NWA name" required>
                            <label for="companyName" class="form-label">Company Name</label>
                            <div class="invalid-tooltip">
                                Company name is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="experience" name="experience" placeholder="experience" required>
                            <label for="experience" class="form-label">Experience</label>
                            <div class="invalid-tooltip">
                                Experience is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <label for="text" class="form-label">Connected with any educational oganization ?</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                Yes  &nbsp;<input class="form-check-input mt-0"  name="connected" type="radio" value="Y" required> &nbsp;  &nbsp;
                                No &nbsp;  &nbsp;<input class="form-check-input mt-0" type="radio" value="N" name="connected" required>
                            </div>
                            <input type="text" class="form-control" placeholder="Remarks" name="remarks">
                        </div>
                    </div>
                    
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>







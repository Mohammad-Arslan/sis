<div id="addFranchiseFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  method="POST" class="row g-3 needs-validation" novalidate id="addFranchiseForm">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('company_name')) is-invalid @endif" id="companyName" name="company_name" placeholder="NWA name" value="{{ old('company_name') }}" >
                            <label for="companyName" class="form-label">Company Name</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('experience')) is-invalid @endif" id="experience" name="experience" placeholder="experience" value="{{ old('experience') }}" >
                            <label for="experience" class="form-label">Experience</label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <label for="text" class="form-label">Connected with any educational oganization ?</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                 Yes  &nbsp;<input class="form-check-input mt-0"  name="connected" type="radio" value="Y" > &nbsp;  &nbsp;No &nbsp;  &nbsp;<input class="form-check-input mt-0" type="radio" value="N" name="connected">
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


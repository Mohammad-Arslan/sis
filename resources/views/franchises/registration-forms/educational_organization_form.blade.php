<div class="tab-pane fade @if(isset($franchise)) active show @endif" id="v-pills-educational-organization" role="tabpanel" aria-labelledby="v-pills-educational-organization-tab">

    <form id="educationalOrgFormInfo" class="g-3 needs-validation" method="POST" novalidate>

        <div>
            <h5>Educational Organization</h5>
            <p class="text-muted">Fill all the required information below.</p>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="eduOrganizationName" name="edu_organization_name" placeholder="Organization Name:" value="{{ isset($franchise) ? $franchise->edu_organization_name : old('edu_organization_name') }}" required>
                    <label for="eduOrganizationName" class="form-label">Organization Name:</label>
                    <div class="invalid-tooltip">Organization name required!</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="inquirerEduDesignation" name="inquirer_edu_designation" placeholder="Designation" value="{{ isset($franchise) ? $franchise->inquirer_edu_designation : old('inquirer_edu_designation') }}" required>
                    <label for="inquirerEduDesignation" class="form-label">Designation</label>
                    <div class="invalid-tooltip">Designation required!</div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="inquirerExperience" name="inquirer_experience" placeholder="Import Export" value="{{ isset($franchise) ? $franchise->inquirer_experience : old('inquirer_experience') }}" required>
                    <label for="inquirerExperience" class="form-label">Experience (Years):</label>
                    <div class="invalid-tooltip">Experience (Years) is required!</div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <textarea class="form-control" id="eduOrganizationDesc" name="edu_organization_desc" placeholder="Description">{{ isset($franchise) ? $franchise->edu_organization_desc : old('edu_organization_desc') }}</textarea> 
                    <label for="eduOrganizationDesc" class="form-label">Description:</label>
                </div>
                
            </div>
        </div>


        <div class="d-flex align-items-start gap-3 mt-4">
            <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-personal-info-tab">
                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to Personal Info
            </button>

            <button type="submit" class="btn btn-primary btn-label right ms-auto save-educational-info" data-nexttab="v-pills-led-franchise-tab">
                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to Franchise info
            </button>
        </div>
    </form>

</div>
@push('header_scripts')


@endpush

@push('footer_scripts')

@endpush
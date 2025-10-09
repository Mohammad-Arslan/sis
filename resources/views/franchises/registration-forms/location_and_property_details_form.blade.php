<div class="tab-pane fade" id="v-pills-location-and-property-details" role="tabpanel" aria-labelledby="v-pills-location-and-property-details-tab">
    <div>
        <h5>Location and property details</h5>
        <p class="text-muted">Fill all the required information below.</p>
    </div>
    <form id="locationAndProperty" class="g-3 needs-validation" method="POST"  novalidate>
        <div class="row g-3">        
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">                                
                    <input type="text" class="form-control" id="timeRequired" name="time_required" placeholder="Time required for construction /renovation">
                    <label for="timeRequired" class="form-label">Time required for construction /renovation:</label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">                                
                    <input type="text" class="form-control" id="proposedInvestment" name="proposed_investment" placeholder="PKR" >
                    <label for="proposedInvestment" class="form-label">Proposed investment amount:PKR</label>
                </div>
            </div>

            <div class="col-md-6 col-sm-12  mt-3">
                <label for="eduOrganizationDesc" class="form-label">No of schools operating within catchment area:</label>
            </div>
            <div class="col-md-3 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="publicSchools" name="public_schools" placeholder="Public">
                    <label for="publicSchools" class="form-label">Public:</label>
                </div>
            </div>
            <div class="col-md-3 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="privateSchools" name="private_schools" placeholder="Private">
                    <label for="privateSchools" class="form-label">Private:</label>
                </div>
            </div>


            <div class="col-md-12 col-sm-12  mt-3">
                <label class="form-check-label" for="purposeBuildCampus">
                    Status of Properpty:&nbsp;&nbsp;&nbsp;
                </label>
                <div class="form-check mb-2 form-check-inline ">
                    <input class="form-check-input" type="radio" name="property_status" id="owned" value="Owned">
                    <label class="form-check-label" for="owned">Owned</label>
                </div>
                <div class="form-check mb-2 form-check-inline">
                    <input class="form-check-input" type="radio" name="property_status" id="rented" value="Rented">
                    <label class="form-check-label" for="rented">Rented</label>
                </div>

                <div class="form-check mb-2 form-check-inline">
                    <input class="form-check-input" type="radio" name="property_status" id="toBeArranged" value="To Be Arranged">
                    <label class="form-check-label" for="toBeArranged">To Be Arranged</label>
                </div>
            </div>

            <div class="col-md-12 col-sm-12  mt-3">
                <label class="form-check-label" for="purposeBuildCampus">
                    What is your plan for financing the franchise:&nbsp;&nbsp;&nbsp;
                </label>
                <div class="form-check mb-2 form-check-inline ">
                    <input class="form-check-input" type="radio" name="financing_plan" id="onMyOwned" value="On My Owned">
                    <label class="form-check-label" for="onMyOwned">On My Owned</label>
                </div>
                <div class="form-check mb-2 form-check-inline">
                    <input class="form-check-input" type="radio" name="financing_plan" id="partnership" value="Partnership">
                    <label class="form-check-label" for="partnership">Partnership</label>
                </div>
            </div>

            <div class="col-md-12 col-sm-12  mt-3">
                <ul class="ps-3">
                    <li>The UCS head office has the right to reject the application without mentioning any reason.</li>
                    <li>Approval of the application is subject to the post visit evalution report of the UCS Bussiness Development Team.</li>
                </ul>
            </div>
        </div>

        <div class="d-flex align-items-start gap-3 mt-4">
            <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-converting-building-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to Converting Existing Building</button>
            <button type="submit" class="btn btn-primary btn-label right ms-auto nexttab save-location-and-property" data-nexttab="v-pills-finish-tab" ><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Finish</button>
        </div>
    </form>
</div>

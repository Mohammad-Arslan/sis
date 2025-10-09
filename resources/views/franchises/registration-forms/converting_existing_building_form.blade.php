<div class="tab-pane fade" id="v-pills-converting-building" role="tabpanel" aria-labelledby="v-pills-converting-building-tab">
    <div>
        <h5>Converting Existing Building</h5>
        <p class="text-muted">Fill all the required information below.</p>

    </div>
    <form id="convertExistingBuilding" class="g-3 needs-validation" method="POST"  novalidate>
        <div class="row g-3">
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <select class="form-select" id="buildingStatus" name="building_status" aria-label="Building Status select">
                        <option value="">Please select</option>
                        <option value="new" > New </option>
                        <option value="old" > Old </option>
                    </select>
                    <label for="buildingStatus" class="form-label">Building Status </label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <select class="form-select" id="buildingOwnership" name="building_ownership" aria-label="Building Ownership select">
                        <option value="">Please select</option>
                        <option value="owned">Owned</option>
                        <option value="rented">Rented</option>
                    </select>
                    <label for="buildingOwnership" class="form-label">Building Ownership </label>
                </div>
            </div>


            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <select class="load-select form-select" id="buildingState" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}" aria-label="State select">
                        <option value="">Please select</option>
                        @if ($states)
                        @foreach ($states as $state)
                        <option value="{{ $state->id }}" >{{ $state->state_name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <label for="buildingState" class="form-label">State/Province</label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <select class="load-select form-select" id="buildingCityId" name="city_id" data-target="town_id" data-url="{{ route('list-towns') }}" aria-label="City select">
                        <option value="">Please select</option>
                    </select>
                    <label for="buildingCityId" class="form-label">City</label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select" id="buildingTown" name="town_id" aria-label="Town select">
                        <option value="">Please select</option>
                    </select>
                    <label for="buildingTown" class="form-label">Town</label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="buildingArea" name="building_area" placeholder="Area/Location" value="">
                    <label for="buildingArea" class="form-label">Area/Location:</label>
                </div>
            </div>

            <div class="col-md-12 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <textarea class="form-control" id="buildingAddress" name="building_address" placeholder="Address" ></textarea>
                    <label for="buildingAddress" class="form-label">Address:</label>
                </div>
            </div>



            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="buildingExistingAreaSize" name="building_existing_area_size" placeholder="Existing Area (Sq. Yards/Kanals)" >
                    <label for="buildingExistingAreaSize" class="form-label">Existing Area (Sq. Yards/Kanals)</label>
                </div>
            </div>
            <div class="col-md-6 col-sm-12  mt-3">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control" id="buildingCoveredArea" name="building_covered_area" placeholder="Total Covered Area" >
                    <label for="buildingCoveredArea" class="form-label">Total Covered Area:</label>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-start gap-3 mt-4">
            <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-school-building-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to School Building Info</button>
            <button type="submit" class="btn btn-primary btn-label right ms-auto nexttab save-convert-exist-building" data-nexttab="v-pills-location-and-property-details-tab" ><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to Location and Property Details</button>
        </div>
    </form>
</div>

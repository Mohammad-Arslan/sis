<div class="tab-pane fade" id="v-pills-school-building" role="tabpanel" aria-labelledby="v-pills-school-building-tab">
    <div class="row g-3">
        <h4 class="flex-grow-1">School Building</h4>
        <div class="flex-shrink-0">
            <button type="button" class="btn btn-success btn-label btn-sm" data-bs-toggle="modal" data-bs-target="#addSchoolBuildingFormModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Record
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-nowrap mb-0" id="schoolBuildingTable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th scope="col">City</th>
                        <th scope="col">Location</th>
                        <th scope="col">Area Size(Sq. Yards/Kanals)</th>
                        <th scope="col">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-led-franchise-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to Franchise Info</button>
        <button type="button" class="btn btn-primary btn-label right ms-auto
        nexttab school_building_btn" data-nexttab="v-pills-converting-building-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to Converting Existing Building</button>
    </div>
</div>

<div class="modal fade" id="addSchoolBuildingFormModal" tabindex="-1" aria-labelledby="addSchoolBuildingFormModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSchoolBuildingFormModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="addSchoolBuildingForm">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="cityId" name="city_id" aria-label="Region select" placeholder="City" required>
                                <option value="">Please select</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <label for="cityId" class="form-label">City</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="location" name="location" placeholder="location">
                            <label for="location" class="form-label">location</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="area_size" name="area_size" placeholder="area_size">
                            <label for="area_size" class="form-label"> Area Size(Sq. Yards/Kanals)</label>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="remarks" name="remarks" placeholder="remarks">
                            <label for="remarks" class="form-label">Remarks</label>
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


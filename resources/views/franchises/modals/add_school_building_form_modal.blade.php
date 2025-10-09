<div id="addSchoolBuildingFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="addSchoolBuildingForm">
                    @csrf
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
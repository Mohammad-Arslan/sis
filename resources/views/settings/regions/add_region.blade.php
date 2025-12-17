<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Region</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('regions.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('region_name')) is-invalid @endif" name="region_name" id="regionName" placeholder="Please enter region name" value="{{old('region_name')}}" required>
                            <label for="regionName" class="form-label">Region name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('region_name'))
                                {{ $errors->first('region_name') }}
                                @else
                                Regions name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="regionNameAbbr" placeholder="Please enter region abbreviation" value="{{old('abbreviation')}}" required>
                            <label for="regionNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="regionDescription" placeholder="Enter region description here...">{{old('description')}}</textarea>
                            <label for="regionDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
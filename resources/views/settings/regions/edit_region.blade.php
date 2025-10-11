<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Region</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('regions.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-region')
                    <a href="{{ route('regions.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Region
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('regions.update', $region->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('region_name')) is-invalid @endif"
                                id="regionName" name="region_name" placeholder="Please enter region name"
                                value="{{ $region->region_name }}" required>
                            <label for="regionName" class="form-label">Region name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('region_name'))
                                    {{ $errors->first('region_name') }}
                                @else
                                    Region name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="regionNameAbbr"
                                placeholder="Please enter abbreviation" value="{{ $region->abbreviation }}" required>
                            <label for="regionNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="regionDescription" data-value=""
                                placeholder="Enter region description here...">{{ $region->description }}</textarea>
                            <label for="regionDescription" class="form-label">Description</label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('regions.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

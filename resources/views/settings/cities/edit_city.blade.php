<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit City</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('cities.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-city')
                    <a href="{{ route('cities.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New City
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('cities.update', $city->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('city_name')) is-invalid @endif"
                                id="cityName" name="city_name" placeholder="Please enter city name"
                                value="{{ $city->city_name }}" required>
                            <label for="cityName" class="form-label">City name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('city_name'))
                                    {{ $errors->first('city_name') }}
                                @else
                                    City name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="cityNameAbbr"
                                placeholder="Please enter abbreviation" value="{{ $city->abbreviation }}" required>
                            <label for="cityNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="stateName" name="state_id" required>
                                <option value="" disabled selected>States options</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        @if ($city->state_id == $state->id) {{ 'selected' }} @endif>
                                        {{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <label for="stateName" class="form-label">States List</label>
                            <div class="invalid-tooltip">Kindly select the state name!</div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('cities.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

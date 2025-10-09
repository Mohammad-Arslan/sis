<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New City</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('cities.store') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('city_name')) is-invalid @endif" id="cityName" placeholder="City name" name="city_name" value="{{ old('city_name') }}" required>
                            <label for="cityName" class="form-label">City name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('city_name'))
                                {{ $errors->first('city_name') }}
                                @else
                                City name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="cityNameAbbr" placeholder="Abbreviation" name="abbreviation" value="{{ old('abbreviation') }}" required>
                            <label for="cityNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="stateName" name="state_id" required>
                                <option value="" disabled selected>States options</option>
                                @foreach($states as $state)
                                <option value="{{$state->id}}" @if (old('state_id')==$state->id) {{ 'selected' }} @endif>{{$state->state_name}}</option>
                                @endforeach
                            </select>
                            <label for="stateName" class="form-label">States List</label>
                            <div class="invalid-tooltip">Kindly select the state name!</div>
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
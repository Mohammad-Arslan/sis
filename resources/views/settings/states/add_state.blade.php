<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New State</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('states.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="stateName" name="state_name" placeholder="Please enter state name" value="{{old('state_name')}}" required>
                            <label for="stateName" class="form-label @if($errors->has('state_name')) is-invalid @endif">State name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('state_name'))
                                {{ $errors->first('state_name') }}
                                @else
                                Country name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" name="country_id" required>
                                <option value="" disabled selected>Countries options</option>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}" @if (old('country_id')==$country->id) {{ 'selected' }} @endif>{{$country->country_name}}</option>
                                @endforeach
                            </select>
                            <label for="countryName" class="form-label">Countries list</label>
                            <div class="invalid-tooltip">Select the country!</div>
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
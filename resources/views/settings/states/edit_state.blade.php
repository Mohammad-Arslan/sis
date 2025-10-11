<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit State</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('states.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-state')
                    <a href="{{ route('states.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New State
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('states.update', $state->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="stateName" name="state_name"
                                placeholder="Please enter state name" value="{{ $state->state_name }}" required>
                            <label for="stateName"
                                class="form-label @if ($errors->has('state_name')) is-invalid @endif">State
                                name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('state_name'))
                                    {{ $errors->first('state_name') }}
                                @else
                                    State name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" name="country_id" required>
                                <option value="" disabled selected>Countries options</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        @if ($state->country_id == $country->id) {{ 'selected' }} @endif>
                                        {{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <label for="countryName" class="form-label">Countries list</label>
                            <div class="invalid-tooltip">Select the country!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('states.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Town</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('towns.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('town_name')) is-invalid @endif" name="town_name" id="townName" placeholder="Please enter town name" value="{{old('town_name')}}" required>
                            <label for="townName" class="form-label">Town name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('town_name'))
                                {{ $errors->first('town_name') }}
                                @else
                                Town name is required!
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" name="city_id" required>
                                <option value="" disabled selected>Cities options</option>
                                @foreach($cities as $city)
                                <option value="{{$city->id}}" @if (old('city_id')==$city->id) {{ 'selected' }} @endif>{{$city->city_name}}</option>
                                @endforeach
                            </select>
                            <label for="cityName" class="form-label">Cities list</label>
                            <div class="invalid-tooltip">Select the city!</div>
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
<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Town</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('towns.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-town')
                    <a href="{{ route('towns.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Town
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('towns.update', $curriculum->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="town_name" id="townName"
                                placeholder="Please enter town name" value="{{ $curriculum->title }}" required>
                            <label for="townName" class="form-label">Town name</label>
                            <div class="invalid-tooltip">Town name is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" name="city_id" required>
                                <option value="" disabled selected>Cities options</option>
                                {{--@foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        @if ($town->city_id == $city->id) {{ 'selected' }} @endif>
                                        {{ $city->city_name }}</option>
                                @endforeach--}}
                            </select>
                            <label for="cityName" class="form-label">Cities list</label>
                            <div class="invalid-tooltip">Select the city!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('towns.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

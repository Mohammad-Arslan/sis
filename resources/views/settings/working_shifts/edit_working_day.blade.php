<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Working Day</h4>
            <div class="flex-shrink-0">
                @permission('add-working-day')
                <a href="{{ route('working-day.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New Working Day
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('working-day.update', $workingDay->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Please enter abbreviation" value="{{ $workingDay->abbreviation }}" required>
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Please enter name" value="{{ $workingDay->name }}" required>
                            <label for="name" class="form-label">Name</label>
                            <div class="invalid-tooltip">Name is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('sort_order')) is-invalid @endif" id="sort_order" name="sort_order" aria-label="Sort Order" required>
                                <option value="">Please select</option>
                                <option value="1" {{ $workingDay->sort_order == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $workingDay->sort_order == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $workingDay->sort_order == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $workingDay->sort_order == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ $workingDay->sort_order == '5' ? 'selected' : '' }}>5</option>
                                <option value="6" {{ $workingDay->sort_order == '6' ? 'selected' : '' }}>6</option>
                                <option value="7" {{ $workingDay->sort_order == '7' ? 'selected' : '' }}>7</option>
                            </select>
                            <label for="sort_order" class="form-label">Sort Order</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('sort_order'))
                                {{ $errors->first('sort_order') }}
                                @else
                                Sort order is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('working-day.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

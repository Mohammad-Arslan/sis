<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New National Holiday</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('official-leave-day.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="start_time" name="start_time" placeholder="hh:mm" value="{{ old('start_time') }}" required>
                            <label for="start_time" class="form-label">Start Time</label>
                            <div class="invalid-tooltip">Start time is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="end_time" name="end_time" placeholder="hh:mm" value="{{ old('end_time') }}" required>
                            <label for="end_time" class="form-label">End Time</label>
                            <div class="invalid-tooltip">End time is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('status')) is-invalid @endif" id="schedule_status" name="status" aria-label="Leave type select" required>
                                <option value="">Please select leave type</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Eid al-Fitar</option>
                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Eid al-Adha</option>
                                <option value="3" {{ old('status') == '3' ? 'selected' : '' }}>Pakistan Day</option>
                                <option value="4" {{ old('status') == '4' ? 'selected' : '' }}>Independence Day</option>
                                <option value="5" {{ old('status') == '5' ? 'selected' : '' }}>Quaid-e-Azam Day</option>
                                <option value="6" {{ old('status') == '6' ? 'selected' : '' }}>Labour Day</option>
                                <option value="7" {{ old('status') == '7' ? 'selected' : '' }}>Muharram</option>
                            </select>
                            <label for="status" class="form-label">Leave Type</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('status'))
                                {{ $errors->first('status') }}
                                @else
                                Leave type is required!
                                @endif
                            </div>
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

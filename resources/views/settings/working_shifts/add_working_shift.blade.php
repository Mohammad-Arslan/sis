<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Shift Schedule</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('working-shift.store') }}" method="post">
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
                            <select class="form-select @if($errors->has('status')) is-invalid @endif" id="schedule_status" name="status" aria-label="Schedule type select" required>
                                <option value="">Please select</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Working Shift</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Off Day</option>
                                
                            </select>
                            <label for="status" class="form-label">Schedule Type</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('status'))
                                {{ $errors->first('status') }}
                                @else
                                Schedule type is required!
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

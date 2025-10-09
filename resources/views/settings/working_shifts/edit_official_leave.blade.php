<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit National Holiday</h4>
            <div class="flex-shrink-0">
                @permission('add-working-shift')
                <a href="{{ route('working-shift.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New Offical Leave
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('official-leave-day.update', $officialLeaveDay->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="start_time" id="start_time" placeholder="hh:mm" value="{{ $officialLeaveDay->start_time }}" required>
                            <label for="start_time" class="form-label">Start time</label>
                            <div class="invalid-tooltip">Start time is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="end_time" name="end_time" placeholder="hh:mm" value="{{ $officialLeaveDay->end_time }}" required>
                            <label for="end_time" class="form-label">End time</label>
                            <div class="invalid-tooltip">End time is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('status')) is-invalid @endif" id="schedule_status" name="status" aria-label="Schedule type select" required>
                                <option value="">Please select</option>
                                <option value="1" {{ $officialLeaveDay->status == '1' ? 'selected' : '' }}>Eid al-Fitar</option>
                                <option value="2" {{ $officialLeaveDay->status == '2' ? 'selected' : '' }}>Eid al-Adha</option>
                                <option value="3" {{ $officialLeaveDay->status == '3' ? 'selected' : '' }}>Pakistan Day</option>
                                <option value="4" {{ $officialLeaveDay->status == '4' ? 'selected' : '' }}>Independence Day</option>
                                <option value="5" {{ $officialLeaveDay->status == '5' ? 'selected' : '' }}>Quaid-e-Azam Day</option>
                                <option value="6" {{ $officialLeaveDay->status == '6' ? 'selected' : '' }}>Labour Day</option>
                                <option value="7" {{ $officialLeaveDay->status == '7' ? 'selected' : '' }}>Muharram</option>
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
                        <a href="{{ route('official-leave-day.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

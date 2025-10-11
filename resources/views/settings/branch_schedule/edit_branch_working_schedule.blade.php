<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Branch Schedule</h4>
            <div class="flex-shrink-0">
                @permission('add--branch-schedule')
                <a href="{{ route('branch-working-shift.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add Branch Schedule
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('branch-working-shift.update', $branchWorkingShift->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-3 col-sm-3">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('name')) is-invalid @endif" id="name" name="name" aria-label="Terms" required>
                                <option value="">Please select</option>
                                <option value="During Term" {{ $branchWorkingShift->name == 'During Term' ? 'selected' : '' }}>During Term</option>
                                <option value="During Winter/Summer/Spring Break" {{ $branchWorkingShift->name == 'During Winter/Summer/Spring Break' ? 'selected' : '' }}>During Winter/Summer/Spring Break</option>
                                <option value="During Ramadan" {{ $branchWorkingShift->name == 'During Ramadan' ? 'selected' : '' }}>During Ramadan</option>
                            </select>
                            <label for="name" class="form-label">Schedule Term</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('name'))
                                {{ $errors->first('name') }}
                                @else
                                Term is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('staff_id')) is-invalid @endif" id="staff_id" name="staff_id" aria-label="Working Days" required>
                                <option value="">Please select</option>
                                @foreach ($staff_types as $staff_type)
                                    <option value="{{ $staff_type->id }}" {{ $branchWorkingShift->staff_id == $staff_type->id ? 'selected' : '' }}>{{ $staff_type->type_name }}</option>
                                @endforeach
                            </select>
                            <label for="staff_id" class="form-label">Staff Types</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('staff_id'))
                                {{ $errors->first('staff_id') }}
                                @else
                                Staff is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('working_day_id')) is-invalid @endif" id="workingday_id" name="working_day_id" aria-label="Working Days" data-placeholder="Choose Week Days" required>
                                <option value="">Please select</option>
                                @foreach ($working_days as $working_day)
                                    <option value="{{ $working_day->id }}" {{ $branchWorkingShift->working_day_id == $working_day->id ? 'selected' : '' }}>{{ $working_day->name }}</option>
                                @endforeach
                            </select>
                            <label for="working_day_id" class="form-label">Working Days</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('working_day_id'))
                                {{ $errors->first('working_day_id') }}
                                @else
                                Working day is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('working_shift_id')) is-invalid @endif" id="working_shift_id" name="working_shift_id" aria-label="Working Shifts" required>
                                <option value="">Please select</option>
                                @foreach ($working_shifts as $working_shift)
                                @if ($working_shift->status == '0')
                                    @php
                                        $Status = "Off Day";
                                    @endphp
                                @else
                                    @php
                                        $Status =  "Working Shift";
                                    @endphp
                                @endif
                                    <option value="{{ $working_shift->id }}" {{ $branchWorkingShift->working_shift_id == $working_shift->id ? 'selected' : '' }}>{{ $working_shift->start_time." - ".$working_shift->end_time. "  [".$Status."] " }}</option>
                                @endforeach
                            </select>
                            <label for="working_shift_id" class="form-label">Working Shifts</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('working_shift_id'))
                                {{ $errors->first('working_shift_id') }}
                                @else
                                Working shift is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">

                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('branch-working-shift.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

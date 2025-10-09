<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create Employee Working Shift</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('employee-shift.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('working_day_id')) is-invalid @endif" id="working_day_id" name="working_day_id" aria-label="Working Days" required>
                                <option value="">Please select</option>
                                @foreach ($working_days as $working_day)
                                    <option value="{{ $working_day->id }}" {{ old("working_day_id") == $working_day->id ? 'selected' : '' }}>{{ $working_day->name }}</option>
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

                    <div class="col-md-4 col-sm-4">
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
                                    <option value="{{ $working_shift->id }}" {{ old("working_shift_id") == $working_shift->id ? 'selected' : '' }}>{{ $working_shift->start_time." - ".$working_shift->end_time. "  [".$Status."] " }}</option>
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
                        @if(isset($employee->id))
                            <input type="hidden" name="employee_id"  value="{{ $employee->id }}" id="employee_id">
                        @elseif (isset($employee_id))
                            <input type="hidden" name="employee_id"  value="{{ $employee_id }}" id="employee_id">
                        @endif
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

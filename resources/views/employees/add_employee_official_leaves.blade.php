<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create Employee National Holiday</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('employee-official-leave-day.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('working_day_id')) is-invalid @endif" id="working_day_id" name="working_day_id" aria-label="Working Days" required>
                                <option value="">Please select</option>
                                @foreach ($working_days as $working_day)
                                    <option value="{{ $working_day->id }}" {{ old("working_day_id") == $working_day->id ? 'selected' : '' }}>{{ $working_day->name }}</option>
                                @endforeach
                            </select>
                            <label for="working_day_id" class="form-label">Select Day</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('working_day_id'))
                                {{ $errors->first('working_day_id') }}
                                @else
                                Day is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('official_leave_id')) is-invalid @endif" id="official_leave_id" name="official_leave_id" aria-label="Working Shifts" required>
                                <option value="">Please select</option>
                                @foreach ($official_leaves as $official_leave)
                                @if ($official_leave->status == '1')
                                    @php
                                        $Status = "Eid al-Fitar";
                                    @endphp
                                @elseif ($official_leave->status == '2')
                                    @php
                                        $Status =  "Eid al-Adha";
                                    @endphp
                                @elseif ($official_leave->status == '3')
                                @php
                                    $Status =  "Pakistan Day";
                                @endphp
                                @elseif ($official_leave->status == '4')
                                @php
                                    $Status =  "Independence Day";
                                @endphp
                                @elseif ($official_leave->status == '5')
                                @php
                                    $Status =  "Quaid-e-Azam Day";
                                @endphp
                                @elseif ($official_leave->status == '6')
                                @php
                                    $Status =  "Labour Day";
                                @endphp
                                @elseif ($official_leave->status == '7')
                                @php
                                    $Status =  "Muharram";
                                @endphp
                                @else
                                @php
                                    $Status =  "";
                                @endphp
                                @endif
                                    <option value="{{ $official_leave->id }}" {{ old("official_leave_id") == $official_leave->id ? 'selected' : '' }}>{{ $official_leave->start_time." - ".$official_leave->end_time. "  [".$Status."] " }}</option>
                                @endforeach
                            </select>
                            <label for="official_leave_id" class="form-label">National Holiday</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('working_shift_id'))
                                {{ $errors->first('working_shift_id') }}
                                @else
                                National Holiday is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('leave_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('leave_date') }}" name="leave_date" id="leave_date" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="leave_date" class="form-label">Leave Date</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('leave_date'))
                                {{ $errors->first('leave_date') }}
                                @else
                                Leave date is required!
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

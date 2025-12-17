<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add New Attendance</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('attendance-mark') }}" method="post">
                    @csrf
                    {{-- <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id" name="academic_year_id" aria-label="Academic Year select" required>
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}" {{ old("academic_year_id") == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('academic_year_id'))
                                {{ $errors->first('academic_year_id') }}
                                @else
                                Academic Year is required!
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id" name="academic_year_id"
                                aria-label="Academic Year select" required>
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}" {{ $academic_year->active == '1' ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('academic_year_id'))
                                {{ $errors->first('academic_year_id') }}
                                @else
                                Academic year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('employee_id')) is-invalid @endif" id="employee_id" name="employee_id"
                                aria-label="Employee select" required>
                                <option value="">Please select</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old("employee_id") == $employee->id ? 'selected' : '' }}>{{ $employee->user->name }}</option>
                                @endforeach
                            </select>
                            <label for="employee_id" class="form-label">Employee *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('employee_id'))
                                {{ $errors->first('employee_id') }}
                                @else
                                Employee is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group date form-label-group in-border">
                            <input type="text" class="clock-time form-control @if($errors->has('date_time')) is-invalid @endif" data-provider="flatpickr" data-deafult-date="" value="{{ old('date_time') }}" name="date_time" id="date_time">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="date_time" class="form-label">Date & Time *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('date_time'))
                                {{ $errors->first('date_time') }}
                                @else
                                Date & Time is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="type" name="type" aria-label="Type select">
                                <option value="">Please select</option>
                                <option value="in" {{ old("type") == 'in' ? 'selected' : '' }}>In</option>
                                <option value="out" {{ old("type") == 'out' ? 'selected' : '' }}>Out</option>
                            </select>
                            <label for="type" class="form-label">Clock Time *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('type'))
                                {{ $errors->first('type') }}
                                @else
                                Clock time is required!
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Mark Attendance</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>



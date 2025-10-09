<form class="row g-3 needs-validation" method="POST" action="{{ route('student-concession.store') }}" novalidate>
    @csrf

    <!-- Academic Year -->
    <div class="col-md-2 col-sm-12">
        <div class="form-label-group in-border position-relative">
            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id"
                name="academic_year_id" aria-label="Academic Year" required>
                <option value="">Please select a Academic Year</option>
                @foreach ($academic_years as $academic_year)
                    <option value="{{ $academic_year->academic_year_id }}"
                        @if (old('academic_year_id') == $academic_year->academic_year_id || $academic_year->academic_year->active == 1) selected @endif>
                        {{ $academic_year->academic_year->title }}
                    </option>
                @endforeach
            </select>
            <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                {{ $errors->first('academic_year_id') ?? 'Academic Year is required!' }}
            </div>
        </div>
    </div>

    <!-- Fee Charge -->
    <div class="col-md-3 col-sm-12">
        @if(isset($student_fee_package) && $student_fee_package)
            @if(count($fee_charges) > 0 && $fee_charges->first()->fee_package_id == $student_fee_package->fee_package_id)
                <div class="alert alert-info alert-sm mb-2">
                    <i class="ri-information-line me-1"></i>
                    Showing fee charges from student's active package: <strong>{{ $student_fee_package->fee_package->package_name }}</strong>
                </div>
            @endif
        @endif
        <div class="form-label-group in-border position-relative">
            <select class="form-select @if ($errors->has('fee_charge_id')) is-invalid @endif" id="chargeId"
                name="fee_charge_id" aria-label="Charge select" required>
                <option value="">Please select a charge</option>
                @if(count($fee_charges) > 0)
                    @foreach ($fee_charges as $fee_charge)
                        <option value="{{ $fee_charge->id }}" @if (old('fee_charge_id') == $fee_charge->id) selected @endif>
                            {{ $fee_charge->fee_charges_type->name . ' - (' . $fee_charge->amount . ' PKR)' }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>No fee charges available for this student's package</option>
                @endif
            </select>
            <label for="chargeId" class="form-label">Fee Charge <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                {{ $errors->first('fee_charge_id') ?? 'Fee Charge is required!' }}
            </div>
        </div>
    </div>

    <!-- Fee Concession -->
    <div class="col-md-3 col-sm-12">
        <div class="form-label-group in-border position-relative">
            <select class="form-select @if ($errors->has('fee_concession_id')) is-invalid @endif" id="concessionId"
                name="fee_concession_id" aria-label="Concession select" required>
                <option value="">Please select a concession</option>
                @foreach ($fee_concessions as $fee_concession)
                    <option value="{{ $fee_concession->id }}" @if (old('fee_concession_id') == $fee_concession->id) selected @endif>
                        {{ $fee_concession->fee_concession_type->name . ' (' . $fee_concession->concession_percentage . '%)' }}
                    </option>
                @endforeach
            </select>
            <label for="concessionId" class="form-label">Fee Concession <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                {{ $errors->first('fee_concession_id') ?? 'Fee Concession is required!' }}
            </div>
        </div>
    </div>

    <!-- Start Date -->
    <div class="col-md-2 col-sm-12">
        <div class="input-group form-label-group in-border position-relative">
            <input type="text" class="form-control @if ($errors->has('start_date')) is-invalid @endif"
                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                data-default-date="{{ old('start_date') }}" value="{{ old('start_date') }}" name="start_date"
                id="start_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="start_date" class="form-label">From Date</label>
            <div class="invalid-tooltip">
                {{ $errors->first('start_date') ?? 'From date is required!' }}
            </div>
        </div>
    </div>

    <!-- End Date -->
    <div class="col-md-2 col-sm-12">
        <div class="input-group form-label-group in-border position-relative">
            <input type="text" class="form-control @if ($errors->has('end_date')) is-invalid @endif"
                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                data-default-date="{{ old('end_date') }}" value="{{ old('end_date') }}" name="end_date"
                id="end_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="end_date" class="form-label">To Date</label>
            <div class="invalid-tooltip">
                {{ $errors->first('end_date') ?? 'To Date is required!' }}
            </div>
        </div>
    </div>

    <!-- Hidden Student ID -->
    <input type="hidden" name="student_id" id="studentID"
        value="{{ old('student_id', isset($student) ? $student->id : 0) }}" />

    <!-- Submit Buttons -->
    <div class="col-12 text-end mt-3">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

<form class="row g-3 needs-validation" method="POST" action="{{ route('student-concession.update',$student_concession['id']) }}" novalidate>
  @csrf
  @method('PATCH')
  <div class="col-md-2 col-sm-12">
    <div class="form-label-group in-border">
        <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id" name="academic_year_id" aria-label="Academic Year" required>
            <option value="">Please select a Academic Year</option>
            @foreach ($academic_years as $academic_year)
              <option @if($academic_year->academic_year->id == $student_concession->academic_year_id) selected @endif value="{{ $academic_year->academic_year_id }}">{{ $academic_year->academic_year->title }}</option>
            @endforeach
        </select>
        <label for="relationID" class="form-label">Academic Year <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if($errors->has('academic_year_id'))
            {{ $errors->first('academic_year_id') }}
            @else
            Academic Year is required!
            @endif
        </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-12">
    @if(isset($student_fee_package) && $student_fee_package)
        @if(count($fee_charges) > 0 && isset($fee_charges->first()->fee_package_id) && $fee_charges->first()->fee_package_id == $student_fee_package->fee_package_id)
            <div class="alert alert-info alert-sm mb-2">
                <i class="ri-information-line me-1"></i>
                Showing fee charges from student's active package: <strong>{{ $student_fee_package->fee_package->package_name }}</strong>
            </div>
        @endif
    @endif
    <div class="form-label-group in-border">
        <select class="form-select @if($errors->has('fee_charge_id')) is-invalid @endif" id="chargeId" name="fee_charge_id" aria-label="Charge select" required>
            <option value="">Please select a charge</option>
            @if(count($fee_charges) > 0)
                @foreach ($fee_charges as $fee_charge)
                  <option value="{{ $fee_charge->id }}" {{ $student_concession['fee_charge_id'] == $fee_charge->id ? 'selected' : '' }}>{{ $fee_charge->fee_charges_type->name . ' - (' . $fee_charge->amount . ' PKR)' }}</option>
                @endforeach
            @else
                <option value="" disabled>No fee charges available for this student's package</option>
            @endif
        </select>
        <label for="relationID" class="form-label">Fee Charge</label>
        <div class="invalid-tooltip">
            @if($errors->has('fee_charge_id'))
            {{ $errors->first('fee_charge_id') }}
            @else
            Fee Charge is required!
            @endif
        </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-12">
    <div class="form-label-group in-border">
        <select class="form-select @if($errors->has('fee_concession_id')) is-invalid @endif" id="concessionId" name="fee_concession_id" aria-label="Concession select" required>
            <option value="">Please select a concession</option>
            @foreach ($fee_concessions as $fee_concession)
              <option value="{{ $fee_concession->id }}" {{ $student_concession['fee_concession_id'] == $fee_concession->id ? 'selected' : '' }}>{{ $fee_concession->fee_concession_type->name . ' (' . $fee_concession->concession_percentage . '%)' }}</option>
            @endforeach
        </select>
        <label for="relationID" class="form-label">Fee Concession <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if($errors->has('fee_concession_id'))
            {{ $errors->first('fee_concession_id') }}
            @else
            Fee Concession is required!
            @endif
        </div>
    </div>
  </div>

  <div class="col-md-2 col-sm-12">
    <div class="input-group form-label-group in-border">
        <input type="text" class="form-control @if($errors->has('start_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ $student_concession['start_date'] }}" name="start_date" id="start_date">
        <div class="input-group-text bg-primary border-primary text-white">
            <i class="ri-calendar-2-line"></i>
        </div>
        <label for="start_date" class="form-label">From Date</label>

    </div>
    <div class="invalid-tooltip">
        @if($errors->has('start_date'))
        {{ $errors->first('start_date') }}
        @else
        From date is required!
        @endif
    </div>
  </div>

  <div class="col-md-2 col-sm-12">
    <div class="input-group form-label-group in-border">
        <input type="text" class="form-control @if($errors->has('end_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ $student_concession['end_date'] }}" name="end_date" id="end_date">
        <div class="input-group-text bg-primary border-primary text-white">
            <i class="ri-calendar-2-line"></i>
        </div>
        <label for="end_date" class="form-label">To Date</label>

    </div>
    <div class="invalid-tooltip">
        @if($errors->has('end_date'))
        {{ $errors->first('end_date') }}
        @else
        To Date required!
        @endif
    </div>
  </div>


  <input type="hidden" name="student_id" id="studentID" value="{{ isset($student) ? $student->id : 0 }}" />

  <div class="col-12 text-end">
      <button class="btn btn-primary" type="submit">Update Changes</button>
      <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
  </div>
</form>


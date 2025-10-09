<form class="row g-3 needs-validation" action="{{ route('student-fee-packages.store') }}" method="POST" novalidate>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('fee_package_id')) is-invalid @endif" id="feePackageId"
                name="fee_package_id" aria-label="Fee package select" required>
                <option value="">Please select a Fee Package</option>
                @if (isset($fee_packages))
                    @foreach ($fee_packages as $fee_package)
                        <option value="{{ $fee_package->id }}"
                            {{ old('fee_package_id') == $fee_package->id ? 'selected' : '' }}>
                            {{ $fee_package->package_name }}</option>
                    @endforeach
                @endif
            </select>
            <label for="feePackageId" class="form-label">Fee Package <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('fee_package_id'))
                    {{ $errors->first('fee_package_id') }}
                @else
                    Fee Package is required!
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('fee_concession_id')) is-iSnvalid @endif" id="feeConcessionId"
                name="fee_concession_id" aria-label="Concession select">
                <option value="">Please select a Fee Concession</option>
                @if (isset($fee_concessions))
                    @foreach ($fee_concessions as $fee_concession)
                        <option value="{{ $fee_concession->id }}"
                            {{ old('fee_concession_id') == '1' ? 'selected' : '' }}>
                            {{ $fee_concession->fee_concession_type->name . ' ' . $fee_concession->concession_percentage . '%' }}
                        </option>
                    @endforeach
                @endif
            </select>
            <label for="feeConcessionId" class="form-label">Fee Concession</label>
            <div class="invalid-tooltip">
                @if ($errors->has('fee_concession_id'))
                    {{ $errors->first('fee_concession_id') }}
                @else
                    Fee Concession is required!
                @endif
            </div>
        </div>
    </div> --}}
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academicYearId" name="academic_year_id" aria-label="Academic year select" required>
                <option value="">Please select a Academic year</option>
                @if (isset($academic_years))
                @foreach ($academic_years as $academic_year)
                <option value="{{ $academic_year->academic_year->id }}" {{ old('academic_year_id') == $academic_year->academic_year->id ? 'selected' : '' }}>{{ $academic_year->academic_year->title }}</option>
                @endforeach
                @endif
            </select>
            <label for="academic_year_id" class="form-label">Academic year</label>
            <div class="invalid-tooltip">
                @if ($errors->has('academic_year_id'))
                {{ $errors->first('academic_year_id') }}
                @else
                Academic year is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('com_class_id')) is-invalid @endif" id="comClassId" name="com_class_id" aria-label="com_class_id select" required>
                <option value="">Please select a class</option>
                @if (isset($classes))
                @foreach ($classes as $class)
                <option value="{{ $class->com_classes->id }}" {{ old('com_class_id') == $class->com_classes->id ? 'selected' : '' }}>{{ $class->com_classes->class_name }}</option>
                @endforeach
                @endif
            </select>
            <label for="comClassId" class="form-label">Class</label>
            <div class="invalid-tooltip">
                @if ($errors->has('com_class_id'))
                {{ $errors->first('com_class_id') }}
                @else
                Class is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('section_id')) is-invalid @endif" id="sectionId" name="section_id" aria-label="Section select" required>
                <option value="">Please select a section</option>
                @if (isset($classes))
                @foreach ($classes as $class)
                {{-- <option value="{{ $class->sections->id }}" {{ old('com_class_id') == $class->sections->id ? 'selected' : '' }}>{{ $class->sections->section_name }}</option> --}}
    {{-- @endforeach
                @endif
            </select>
            <label for="sectionId" class="form-label">Section</label>
            <div class="invalid-tooltip">
                @if ($errors->has('section_id'))
                {{ $errors->first('section_id') }}
                @else
                Section is required!
                @endif
            </div>
        </div>
    </div> --}}

    <input type="hidden" id="studentID" name="student_id" value="{{ isset($student) ? $student->id : 0 }}" />

    @csrf
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit" id="submitButton" onclick="submitForm(this);">Submit form</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
<script>
    function submitForm(btn) {
        // disable the button
        btn.disabled = true;
        // submit the form
        btn.form.submit();
    }
</script>

<form class="row g-3 needs-validation" novalidate>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('fee_package_id')) is-invalid @endif" id="feePackageId" name="fee_package_id" aria-label="Fee package select">
                <option value="">Please select a Fee Package</option>
                <option value="1" {{ old('fee_package_id') == '1' ? 'selected' : '' }}>Fee Package 01</option>
                <option value="2" {{ old('fee_package_id') == '2' ? 'selected' : '' }}>Fee Package 02</option>
                <option value="3" {{ old('fee_package_id') == '3' ? 'selected' : '' }}>Fee Package 03</option>
            </select>
            <label for="feePackageId" class="form-label">Fee Package</label>
            <div class="invalid-tooltip">
                @if($errors->has('fee_package_id'))
                {{ $errors->first('fee_package_id') }}
                @else
                Bank is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('fee_concession_id')) is-invalid @endif" id="feeConcessionId" name="fee_concession_id" aria-label="Concession select">
                <option value="">Please select a Fee Concession</option>
                <option value="1" {{ old('fee_concession_id') == '1' ? 'selected' : '' }}>Concession 01</option>
                <option value="2" {{ old('fee_concession_id') == '2' ? 'selected' : '' }}>Concession 02</option>
                <option value="3" {{ old('fee_concession_id') == '3' ? 'selected' : '' }}>Concession 03</option>
            </select>
            <label for="feeConcessionId" class="form-label">Fee Concession</label>
            <div class="invalid-tooltip">
                @if($errors->has('fee_concession_id'))
                {{ $errors->first('fee_concession_id') }}
                @else
                Bank is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academicYearId" name="academic_year_id" aria-label="Academic year select">
                <option value="">Please select a Academic year</option>
                <option value="1" {{ old('academic_year_id') == '1' ? 'selected' : '' }}>Academic year 01</option>
                <option value="2" {{ old('academic_year_id') == '2' ? 'selected' : '' }}>Academic year 02</option>
                <option value="3" {{ old('academic_year_id') == '3' ? 'selected' : '' }}>Academic year 03</option>
            </select>
            <label for="academic_year_id" class="form-label">Academic year</label>
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
            <select class="form-select @if($errors->has('com_class_id')) is-invalid @endif" id="comClassId" name="com_class_id" aria-label="com_class_id select">
                <option value="">Please select a class</option>
                <option value="1" {{ old('com_class_id') == '1' ? 'selected' : '' }}>Class 01</option>
                <option value="2" {{ old('com_class_id') == '2' ? 'selected' : '' }}>Class 02</option>
                <option value="3" {{ old('com_class_id') == '3' ? 'selected' : '' }}>Class 03</option>
            </select>
            <label for="comClassId" class="form-label">Class</label>
            <div class="invalid-tooltip">
                @if($errors->has('com_class_id'))
                {{ $errors->first('com_class_id') }}
                @else
                Class is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('section_id')) is-invalid @endif" id="sectionId" name="section_id" aria-label="Section select">
                <option value="">Please select a section</option>
                <option value="1" {{ old('section_id') == '1' ? 'selected' : '' }}>Section 01</option>
                <option value="2" {{ old('section_id') == '2' ? 'selected' : '' }}>Section 02</option>
                <option value="3" {{ old('section_id') == '3' ? 'selected' : '' }}>Section 03</option>
            </select>
            <label for="sectionId" class="form-label">Section</label>
            <div class="invalid-tooltip">
                @if($errors->has('section_id'))
                {{ $errors->first('section_id') }}
                @else
                section_id is required!
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" id="studentID" name="student_id" value="{{ isset($student) ? $student->id : 0 }}" />

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Submit form</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
<form class="row g-3 needs-validation" method="POST" action="{{ route('class-students.store') }}">
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academicYear"
                name="academic_year_id" aria-label="Academic year select" required>
                <option value="">Please select a academic year</option>
                @foreach ($academic_years as $academic_year)
                    <option value="{{ $academic_year->academic_year->id }}"
                        {{ old('academic_year_id') == '1' ? 'selected' : '' }}>
                        {{ $academic_year->academic_year->title }}</option>
                @endforeach
            </select>
            <label for="academicYear" class="form-label">Academic Year <span class="text-danger">*</span></label>
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
            <select class="load-select form-select @if ($errors->has('class_id')) is-invalid @endif" id="class"
                name="class_id" data-target="section_id" data-url="{{ 'list-sections/' . $student->branch_id . '' }}"
                aria-label="Class select" required>
                <option value="">Please select a class</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->com_classes->id }}"
                        {{ old('section_id') == $class->com_classes->id ? 'selected' : '' }}>
                        {{ $class->com_classes->class_name }}</option>
                @endforeach
            </select>
            <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('class_id'))
                    {{ $errors->first('class_id') }}
                @else
                    Class is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('section_id')) is-invalid @endif" id="section"
                name="section_id" aria-label="Section select" required>
                <option value="">Please select a section</option>
            </select>
            <label for="section" class="form-label">Section <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('section_id'))
                    {{ $errors->first('section_id') }}
                @else
                    Section is required!
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

    @csrf
    <input type="hidden" name="student_id" id="studentID" value="{{ isset($student) ? $student->id : 0 }}" />

    <div class="col-12 text-end">
        <button id="submitButton" class="btn btn-primary" type="submit" onclick="submitForm(this);">Submit
            form</button>
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

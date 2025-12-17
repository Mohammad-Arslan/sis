<form class="row g-3 needs-validation" method="POST" action="{{ route('branch-class-sections.store') }}" novalidate>
    @csrf
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('class_id')) is-invalid @endif" id="classID" name="class_id" aria-label="Relation select" required>
                <option value="">Please select a relation</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                @endforeach
            </select>
            <label for="classID" class="form-label">Class</label>
            <div class="invalid-tooltip">
                @if($errors->has('class_id'))
                {{ $errors->first('class_id') }}
                @else
                Class is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        {{ isset($error) ? $error : "" }}
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('section_id')) is-invalid @endif" id="sectionID" name="section_id" aria-label="Relation select">
                <option value="">Please select a section</option>
                @foreach($sections as $section)
                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                @endforeach
            </select>
            <label for="sectionID" class="form-label">Section</label>
            <div class="invalid-tooltip">
                @if($errors->has('section_id'))
                {{ $errors->first('section_id') }}
                @else
                Section is required!
                @endif
            </div>
        </div>
    </div>


    <input type="hidden" name="branch_id" id="branchID" value="{{ isset($branch) ? $branch->id : 0 }}" />

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
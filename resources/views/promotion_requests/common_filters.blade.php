<div class="col-md-3 col-sm-12">
    <div class="form-label-group in-border">
        <select class="form-select refresh-table @if ($errors->has('academic_year_id')) is-invalid @endif"
                id="academic_year_id" name="academic_year_id" required {{ isset($promotionRequest) ? 'disabled' : '' }}>
            <option value="">Please select Academic Year</option>
            @foreach ($academic_years as $academic_year)
                <option
                    value="{{ $academic_year->id }}"{{ (isset($promotionRequest) && $promotionRequest->prev_academic_year_id == $academic_year->id) ? 'selected' : (old('promoted_academic_year_id') == $academic_year->id ? 'selected' : '') }}>{{ $academic_year->title }}</option>
            @endforeach
        </select>
        <label for="section" class="form-label">Academic Year <span
                class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('academic_year_id'))
                {{ $errors->first('academic_year_id') }}
            @else
                Academic Year is required!
            @endif
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-12">
    <div class="form-label-group in-border">
        <select class="load-select refresh-table form-select @if ($errors->has('branch')) is-invalid @endif"
                data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id" name="branch_id"
                aria-label="Branch select" required {{ isset($promotionRequest) ? 'disabled' : '' }}>
            <option value="">Please select</option>
            @if (isset($branch))
                <option value="{{ $branch['id'] }}" selected>
                    {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
            @else
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ (isset($promotionRequest) && $promotionRequest->prev_branch_id == $branch->id) ? 'selected' : (old('promoted_branch_id') == $branch->id ? 'selected' : '') }}>
                        {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                @endforeach
            @endif
        </select>
        <label for="branch_id" class="form-label">Branch <span
                class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('branch'))
                {{ $errors->first('branch') }}
            @else
                Branch is required!
            @endif
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-12">
    <div class="form-label-group in-border">
        <select
            class="load-select refresh-table form-select @if ($errors->has('class_id')) is-invalid @endif"
            data-target="subject_id,section_id"
            data-url="{{ isset($promotionRequest) ? route('get-class-section-subjects', $promotionRequest->prev_branch_id) : '' }}"
            id="class_id" name="class_id" required {{ isset($promotionRequest) ? 'disabled' : '' }}>
            <option value="">Please select a class</option>
            @if(isset($promotionRequest))
                @foreach($prev_branch_classes as $branch_class)
                    <option
                        value="{{ $branch_class->id }}" {{ $branch_class->class_id == $promotionRequest->prev_class_id ? 'selected' : '' }}>{{ $branch_class->com_classes->class_name }}</option>
                @endforeach
            @endif
        </select>
        <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('class_id'))
                {{ $errors->first('class_id') }}
            @else
                Class is required!
            @endif
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-12">
    <div class="form-label-group in-border">
        <select
            class="form-select refresh-table @if ($errors->has('section_id')) is-invalid @endif"
            id="section_id" name="section_id" aria-label="Branch select"
            required {{ isset($promotionRequest) ? 'disabled' : '' }}>
            <option value="">Please select a section</option>
            @if(isset($promotionRequest))
                @foreach($prev_class_sections as $class_section)
                    <option
                        value="{{ $class_section->id }}" {{ $class_section->section_id == $promotionRequest->prev_section_id ? 'selected' : '' }}>{{ $class_section->sections->section_name }}</option>
                @endforeach
            @endif
        </select>
        <label class="form-label">Section <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('section_id'))
                {{ $errors->first('section_id') }}
            @else
                Section is required!
            @endif
        </div>
    </div>
</div>

@if(isset($promotionRequest))
    <input type="hidden" name="academic_year_id" value="{{ $promotionRequest->prev_academic_year_id }}">
    <input type="hidden" name="branch_id" value="{{ $promotionRequest->prev_branch_id }}">
    <input type="hidden" name="class_id" value="{{ $promotionRequest->prev_ }}">
    <input type="hidden" name="section_id" value="{{ $promotionRequest->prev_section_id }}">
@endif
@push('footer_scripts')

@endpush

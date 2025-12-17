<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Grade Book</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <div class="row g3">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select refresh-table @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="academic_year_id" name="academic_year_id" required>
                                <option value="">Please select Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
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
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select form-select refresh-table @if ($errors->has('branch_id')) is-invalid @endif"
                                data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id"
                                name="branch_id" aria-label="Branch select" required>
                                <option value="">Please select</option>
                                @if (isset($branch))
                                    <option value="{{ $branch['id'] }}">
                                        {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
                                @else
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="branchId" class="form-label">Branch <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select form-select refresh-table @if ($errors->has('class_id')) is-invalid @endif"
                                data-target="subject_id,section_id"
                                data-url="{{ isset($studentBehaviourSkill) ? route('get-class-section-subjects', $studentBehaviourSkill['branch_id']) : '' }}"
                                id="class_id" name="class_id" required>
                                <option value="">All classes</option>
                                @if (isset($branch_classes))
                                    @foreach ($branch_classes as $class)
                                        <option value="{{ $class->id }}">{{ $class['com_classes']['class_name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Class </label>
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
                            <select
                                class="form-select refresh-table @if ($errors->has('section_id')) is-invalid @endif"
                                id="section_id" name="section_id" aria-label="Branch select" required>
                                <option value="">All sections</option>
                                @if (isset($branch_class_sections))
                                    @foreach ($branch_class_sections as $section)
                                        <option value="{{ $section->id }}">{{ $section['sections']['section_name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <label class="form-label">Section </label>
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
                            <select class="form-select @if ($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Select Subject" required {{isset($studentBehaviourSkill) ? 'disabled' : ''}}>
                                <option value="">Please select a subject</option>
                                @if (isset($subjects))
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['subject_id'] == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="sectionID" class="form-label">Subject <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('subject_id'))
                                    {{ $errors->first('subject_id') }}
                                @else
                                    Subject is required!
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="form-select refresh-table @if ($errors->has('term_id')) is-invalid @endif"
                                id="term_id" name="term_id" required>
                                <option value="">Both Terms</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Term </label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('term_id'))
                                    {{ $errors->first('term_id') }}
                                @else
                                    Term is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_reports">Fetch Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });
        });
    </script>
@endpush

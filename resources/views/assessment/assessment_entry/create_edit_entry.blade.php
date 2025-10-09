<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($assessmentEntry) ? 'Update' : 'Add' }} Assessment Entry
            </h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <div class="row g3">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="academic_year_id" name="academic_year_id" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ isset($assessmentEntry) && $assessmentEntry['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>
                                        {{ $academic_year->title }}</option>
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
                            <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id"
                                name="branch_id" aria-label="Branch select" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select</option>
                                @if (isset($branch))
                                    <option value="{{ $branch['id'] }}">
                                        {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
                                @elseif (!isset($branch) && isset($assessmentEntry))
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['branch_id'] == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                @else
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                @endif
                                {{-- @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ isset($assessmentEntry) && $assessmentEntry['branch_id'] == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                @endforeach --}}
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
                            <select class="load-select form-select @if ($errors->has('class_id')) is-invalid @endif"
                                data-target="subject_id,section_id"
                                data-url="{{ isset($assessmentEntry) ? route('get-class-section-subjects', $assessmentEntry['branch_id']) : '' }}"
                                id="class_id" name="class_id" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a class</option>
                                @if (isset($branch_classes))
                                    @foreach ($branch_classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['class_id'] == $class->class_id ? 'selected' : '' }}>
                                            {{ $class['com_classes']['class_name'] }}</option>
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
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('section_id')) is-invalid @endif"
                                id="section_id" name="section_id" aria-label="Branch select" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a section</option>
                                @if (isset($branch_class_sections))
                                    @foreach ($branch_class_sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['section_id'] == $section->section_id ? 'selected' : '' }}>
                                            {{ $section['sections']['section_name'] }}</option>
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
                    {{--                    {{dd($subjects->toArray())}} --}}
                    <div class="col-md-4 col-sm-12">
                        <input type="hidden" id="subject_type"
                            value="{{ isset($subjectType) ? $subjectType : null }}">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('subject_id')) is-invalid @endif"
                                id="subject_id" name="subject_id" aria-label="Select Subject" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a subject</option>
                                @if (isset($subjects))
                                    @foreach ($subjects as $subject)
                                        <option data-subjectType="{{ $subject->subject_type }}"
                                            value="{{ $subject->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['subject_id'] == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject_name }}</option>
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
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('term_id')) is-invalid @endif"
                                id="term_id" name="term_id" required {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ isset($assessmentEntry) && $assessmentEntry['term_id'] == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Term <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('term_id'))
                                    {{ $errors->first('term_id') }}
                                @else
                                    Term is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('assessment_level_one_id')) is-invalid @endif"
                                data-target="assessment_level_two_id" data-url="{{ 'get-assessment-level-child' }}"
                                id="assessment_level_one_id" name="assessment_level_one_id" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a Level 1</option>
                                @foreach ($assessment_level_one as $level_one)
                                    <option value="{{ $level_one->id }}"
                                        {{ isset($assessmentEntry) && $assessmentEntry['assessment_level_one_id'] == $level_one->id ? 'selected' : '' }}>
                                        {{ $level_one->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Level 1 <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('assessment_level_one_id'))
                                    {{ $errors->first('assessment_level_one_id') }}
                                @else
                                    Level 1 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select form-select @if ($errors->has('assessment_level_two_id')) is-invalid @endif"
                                data-target="assessment_level_three_id" data-url="{{ 'get-assessment-level-child' }}"
                                id="assessment_level_two_id" name="assessment_level_two_id" required
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a Level 2</option>
                                @if (isset($assessment_level_two))
                                    @foreach ($assessment_level_two as $level_two)
                                        <option value="{{ $level_two->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['assessment_level_two_id'] == $level_two->id ? 'selected' : '' }}>
                                            {{ $level_two->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Level 2 <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('assessment_level_two_id'))
                                    {{ $errors->first('assessment_level_two_id') }}
                                @else
                                    Level 2 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('assessment_level_three_id')) is-invalid @endif"
                                id="assessment_level_three_id" name="assessment_level_three_id"
                                {{ isset($assessmentEntry) ? 'disabled' : '' }}>
                                <option value="">Please select a Level 3</option>
                                @if (isset($assessment_level_three))
                                    @foreach ($assessment_level_three as $level_three)
                                        <option value="{{ $level_three->id }}"
                                            {{ isset($assessmentEntry) && $assessmentEntry['assessment_level_three_id'] == $level_three->id ? 'selected' : '' }}>
                                            {{ $level_three->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Level 3 </label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('assessment_level_three_id'))
                                    {{ $errors->first('assessment_level_three_id') }}
                                @else
                                    Level 3 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('marks_type')) is-invalid @endif"
                                id="marks_type" name="marks_type" required>
                                <option value="">Please select a Mark Type</option>
                                <option value="marks"
                                    {{ isset($assessmentEntry) && $assessmentEntry['marks_type'] == 'marks' ? 'selected' : '' }}>
                                    Marks
                                </option>
                                <option value="grades"
                                    {{ isset($assessmentEntry) && $assessmentEntry['marks_type'] == 'grades' ? 'selected' : '' }}>
                                    Grades
                                </option>
                            </select>
                            <label for="section" class="form-label">Mark Type <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('marks_type'))
                                    {{ $errors->first('marks_type') }}
                                @else
                                    Mark Type is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input
                                type="{{ isset($assessmentEntry) && $assessmentEntry['marks_type'] == 'grades' ? 'text' : 'decimal' }}"
                                class="form-control" id="grade_marks" name="grade_marks" placeholder="Marks"
                                value="{{ isset($assessmentEntry) ? $assessmentEntry['grade_marks'] : '' }}" required>
                            <label for="grade_marks" class="form-label">Grade/Marks <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('grade_marks'))
                                    {{ $errors->first('grade_marks') }}
                                @else
                                    Marks is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control @if ($errors->has('assessment_date')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                    value="{{ isset($assessmentEntry) ? parse_date($assessmentEntry->assessment_date, 'd-m-Y') : '' }}"
                                    name="assessment_date" id="assessment_date" required>
                                <label for="dateOfBirth" class="form-label">Date <span
                                        class="text-danger">*</span></label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('assessment_date'))
                                        {{ $errors->first('assessment_date') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="address" name="remarks" rows="2" placeholder="Remarks">{{ old('remarks') ? old('remarks') : (isset($assessmentEntry['remarks']) ? $assessmentEntry['remarks'] : '') }}</textarea>
                            <label for="remarks" class="form-label">Remarks</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @else
                                    Remarks is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-sm btn-primary"
                            type="submit">{{ isset($assessmentEntry) ? 'Update' : 'Submit' }}</button>
                        @if (!isset($assessmentEntry))
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_students">Fetch
                                Students</a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_assessments">Fetch
                                Assessment</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // var subject_type = '';
            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });

            // $('#subject_id').change(function() {
            // var subject_type =  $("#subject_id option:selected").data('subject-type');
            var subject_type = $('#subject_type').val();
            // alert(subject_type);
            if (subject_type === 'Minor') {
                // $('#term_id').attr("disabled", true);
                $('#assessment_date').attr("disabled", true);
                $('#assessment_level_one_id').attr("disabled", true);
                $('#assessment_level_two_id').attr("disabled", true);
                $('#assessment_level_three_id').attr("disabled", true);
                $('#marks_type').attr("disabled", true);
                $('#grade_marks').attr("disabled", true);
            }
            // });

            $('.fetch_students').on('click', function() {
                let section_id = $('#section_id').val();
                let academic_year_id = $('#academic_year_id').val();;
                if (section_id == "") {
                    alert('Please select section');
                    return false;
                }

                $.ajax({
                    url: '{{ route('list-students') }}',
                    type: 'GET',
                    data: {
                        sections: [section_id],
                        academic_year_id: [academic_year_id],
                        calling_from: 'assessment_entry',
                    },
                    cache: false,
                    success: function(result) {
                        $('.studentListTable tbody').html(result.html)
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 14000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });
        });
    </script>
@endpush

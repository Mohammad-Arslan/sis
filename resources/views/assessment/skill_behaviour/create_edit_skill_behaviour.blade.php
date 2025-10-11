<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($studentBehaviourSkill) ? 'Update' : 'Add' }} Skill
                Behaviour</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <div class="row g3">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="academic_year_id" name="academic_year_id" required
                                {{ isset($studentBehaviourSkill) ? 'disabled' : '' }}>
                                <option value="">Please select Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>
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
                            <select
                                class="load-select refresh-table form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id"
                                name="branch_id" aria-label="Branch select" required
                                {{ isset($studentBehaviourSkill) ? 'disabled' : '' }}>
                                <option value="">Please select</option>
                                @if (isset($branch))
                                    <option value="{{ $branch['id'] }}">
                                        {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
                                @else
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['branch_id'] == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                @endif
                                {{-- @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['branch_id'] == $branch->id ? 'selected' : '' }}>
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
                            <select
                                class="load-select refresh-table form-select @if ($errors->has('class_id')) is-invalid @endif"
                                data-target="subject_id,section_id"
                                data-url="{{ isset($studentBehaviourSkill) ? route('get-class-section-subjects', $studentBehaviourSkill['branch_id']) : '' }}"
                                id="class_id" name="class_id" required
                                {{ isset($studentBehaviourSkill) ? 'disabled' : '' }}>
                                <option value="">Please select a class</option>
                                @if (isset($branch_classes))
                                    @foreach ($branch_classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['class_id'] == $class->class_id ? 'selected' : '' }}>
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
                            <select
                                class="form-select refresh-table @if ($errors->has('section_id')) is-invalid @endif"
                                id="section_id" name="section_id" aria-label="Branch select" required
                                {{ isset($studentBehaviourSkill) ? 'disabled' : '' }}>
                                <option value="">Please select a section</option>
                                @if (isset($branch_class_sections))
                                    @foreach ($branch_class_sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['section_id'] == $section->section_id ? 'selected' : '' }}>
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
                            <select class="form-select @if ($errors->has('term_id')) is-invalid @endif"
                                id="term_id" name="term_id" required
                                {{ isset($studentBehaviourSkill) ? 'disabled' : '' }}>
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ isset($studentBehaviourSkill) && $studentBehaviourSkill['term_id'] == $term->id ? 'selected' : '' }}>
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
                    <div class="col-12 text-end">
                        @if (!isset($studentBehaviourSkill))
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_students">Fetch
                                Students</a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_skill_behaviour">Fetch
                                Skill/Behaviour</a>
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
            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });

            $('.refresh-table').on('change', function() {
                $('.studentListTable tbody').html(`<tr class="text-center">
                        <td colspan="4">No Record Fetched Yet.</td>
                    </tr>`);
            });

            $('.fetch_students').on('click', function() {
                let section_id = $('#section_id').val();
                let academic_year_id = $('#academic_year_id').val();
                let selected_class = $("#class_id option:selected").text().toLowerCase();

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
                        calling_from: 'skill_behaviour_entry',
                        show_skill_btn: selected_class.includes('nursery') || selected_class
                            .includes('kg'),
                    },
                    cache: false,
                    success: function(result) {
                        $('.studentListTable tbody').html(result.html)
                    },
                    error: function(error) {
                        console.log("Sorry! Server error!");
                        console.log(error);
                    }
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });
        });
    </script>
@endpush

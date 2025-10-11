<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($subjectRemark) ? 'Update' : 'Add' }} Subject Remarks
            </h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <div class="row g3">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="academic_year_id" name="academic_year_id" required
                                {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ isset($subjectRemark) && $subjectRemark['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>
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
                                {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select</option>
                                @if (isset($branch))
                                    <option value="{{ $branch['id'] }}">
                                        {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
                                @else
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ isset($subjectRemark) && $subjectRemark['branch_id'] == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                @endif
                                {{-- @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ isset($subjectRemark) && $subjectRemark['branch_id'] == $branch->id ? 'selected' : '' }}>
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
                                data-url="{{ isset($subjectRemark) ? route('get-class-section-subjects', $subjectRemark['branch_id']) : '' }}"
                                id="class_id" name="class_id" required {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select a class</option>
                                @if (isset($branch_classes))
                                    @foreach ($branch_classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ isset($subjectRemark) && $subjectRemark['class_id'] == $class->class_id ? 'selected' : '' }}>
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
                                {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select a section</option>
                                @if (isset($branch_class_sections))
                                    @foreach ($branch_class_sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ isset($subjectRemark) && $subjectRemark['section_id'] == $section->section_id ? 'selected' : '' }}>
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
                                {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select a subject</option>
                                @if (isset($subjects))
                                    @foreach ($subjects as $subject)
                                        <option data-subjectType="{{ $subject->subject_type }}"
                                            value="{{ $subject->id }}"
                                            {{ isset($subjectRemark) && $subjectRemark['subject_id'] == $subject->id ? 'selected' : '' }}>
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
                                id="term_id" name="term_id" required {{ isset($subjectRemark) ? 'disabled' : '' }}>
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ isset($subjectRemark) && $subjectRemark['term_id'] == $term->id ? 'selected' : '' }}>
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

                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control @if ($errors->has('assessment_date')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                    value="{{ isset($subjectRemark) ? parse_date($subjectRemark->assessment_date, 'd-m-Y') : '' }}"
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

                    <div class="col-12 text-end">
                        @if(isset($subjectRemark))
                        <button class="btn btn-sm btn-primary"
                            type="submit">Update</button>
                            @else
                        <button class="btn btn-sm btn-primary"  id ='btn' disabled
                            type="submit">Submit</button>
                            @endif
                        @if (!isset($subjectRemark))
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_students">Fetch
                                Students</a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_assessments">Fetch
                                Subject Remarks</a>
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
            // document.getElementById("test").disabled = false;
            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });
            $('.fetch_students').on('click', function() {
                let section_id = $('#section_id').val();
                if (section_id == "") {
                    alert('Please select section');
                    return false;
                }

                $.ajax({
                    url: '{{ route('list-students') }}',
                    type: 'GET',
                    data: {
                        sections: [section_id],
                        calling_from: 'subject_remarks',
                    },
                    cache: false,
                    success: function(result) {
                        $('.studentListTable tbody').html(result.html);
                        $("#btn").attr("disabled", false);

                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    // timeout: 8000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });
        });
    </script>
@endpush

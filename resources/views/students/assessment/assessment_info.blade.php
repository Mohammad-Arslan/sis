<div class="col-lg-12">
    <div class="col-lg-12">
        <div class="card">
            <form class="needs-validation" method="POST" action="#" novalidate>
                <div class="card-body">
                    <div class="live-preview">
                        <div class="row g3">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                        id="academic_year_id" name="academic_year_id" required>
                                        <option value="">Please select Academic Year</option>
                                        @foreach ($all_academic_years ?? [] as $academic_year)
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
                                        name="branch_id" aria-label="Branch select" required>
                                        <option value="">Please select</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ isset($student->branch) && $student->branch->id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                            @endforeach
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
                                        data-url="{{ route('get-class-section-subjects', $student->branch->id ?? '') }}"
                                        id="class_id" name="class_id" required>
                                        <option value="">Please select a class</option>
                                        @if (isset($assessement_branch_classes))
                                            @foreach ($assessement_branch_classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ isset($student->active_class) && $student->active_class->branch_class_sections->class_id == $class->class_id ? 'selected' : '' }}>
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
                                        id="section_id" name="section_id" aria-label="Branch select" required>
                                        <option value="">Please select a section</option>
                                        @if (isset($assessment_branch_class_sections))
                                            @foreach ($assessment_branch_class_sections as $section)
                                                <option value="{{ $section->id }}"
                                                    {{ isset($assessment_branch_class_sections) && $section['sections']['id'] == $section->section_id ? 'selected' : '' }}>
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
                                        id="subject_id" name="subject_id" aria-label="Select Subject" required>
                                        <option value="">Please select a subject</option>
                                        @if (isset($assessment_subjects))
                                            @foreach ($assessment_subjects as $subject)
                                                <option data-subjectType="{{ $subject->subject_type }}"
                                                    value="{{ $subject->id }}">
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
                                        @foreach ($terms ?? [] as $term)
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
                                        @foreach ($assessment_level_one ?? [] as $level_one)
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
                                            @foreach ($assessment_level_two ?? [] as $level_two)
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
                                            @foreach ($assessment_level_three ?? [] as $level_three)
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
                            <div class="col-12 text-end">
                                @if (!isset($assessmentEntry))
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_assessments">Fetch
                                        Assessment</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Assessment List</h4>
            </div><!-- end card header -->

            <input type="hidden" value=@if (isset($student)) {{ $student->id }}@else 0 @endif
                id="student_id">

            {{-- <div class="card-body">
                <div class="live-preview">
                    <div class="row g3">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select
                                    class="form-select refresh-table @if ($errors->has('term_id')) is-invalid @endif"
                                    id="term_id" name="term_id" required>
                                    <option value="">Please select a Term</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
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
                                <select
                                    class="form-select refresh-table @if ($errors->has('subject_id')) is-invalid @endif"
                                    id="subject_id" name="subject_id" required>
                                    <option value="">Please select a Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->subject_id }}">{{ $subject->subject->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="section" class="form-label">Subject <span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('subject_id'))
                                        {{ $errors->first('subject_id') }}
                                    @else
                                        Subject is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_reports">Fetch Reports</a>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{-- <table id="assessment-entries-datatable"
                class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Branch</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Term</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Academic Year</th>
                        <th>Branch</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Term</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table> --}}
            <table id="assessment-entries-datatable"
                class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        {{-- <th>Academic Year</th>
                    <th>Branch</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th> --}}
                        <th>Term</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Subject</th>
                        <th>Marks/Grade</th>
                        <th>Remarks</th>
                        <th>Assessment Date</th>
                        <th>Created At</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        {{-- <th>Academic Year</th>
                    <th>Branch</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th> --}}
                        <th>Term</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Subject</th>
                        <th>Marks/Grade</th>
                        <th>Remarks</th>
                        <th>Assessment Date</th>
                        <th>Created At</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div id="progress-report-modal-div">
</div>
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#assessment-entries-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 50,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('student-assessments-list') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.student_id = $('#student_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.section_id = $('#section_id').val();
                        d.subject_id = $('#subject_id').val();
                        d.term_id = $('#term_id').val();
                    }
                },
                columns: [
                    {
                        data: 'term.name',
                        name: 'term.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_level_one.name',
                        name: 'assessment_level_one.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_level_two.name',
                        name: 'assessment_level_two.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'level_three_name',
                        name: 'level_three_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'subject',
                        name: 'subject',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'grade_marks',
                        name: 'grade_marks',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_date',
                        name: 'assessment_date',
                        defaultContent: 'N/A',
                        width: "15%"
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    }
                ]
            });

            $(document).on('click', '.fetch_assessments', function() {
                // if ($('#academic_year_id').val() == "") {
                //     alert('Please select academic year');
                //     return false;
                // } else if ($('#branch_id').val() == "") {
                //     alert('Please select branch');
                //     return false;
                // } else if ($('#class_id').val() == "") {
                //     alert('Please select class');
                //     return false;
                // } else if ($('#section_id').val() == "") {
                //     alert('Please select section');
                //     return false;
                // } else if ($('#subject_id').val() == "") {
                //     alert('Please select subject');
                //     return false;
                // }

                $('#assessment-entries-datatable').DataTable().ajax.reload(null, false);
            });

            $(document).on('click', '.show-progress-report-modal', function(e) {

                var target = $(this).data('target');
                var url = $(this).data('url');
                console.log('show modal', target, url);
                $.ajax({

                    url: url,
                    type: "GET",
                    // dataType: 'html',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        $('#progress-report-modal-div').html(data);
                        $(target).modal('show');
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            });
        });
    </script>
@endpush

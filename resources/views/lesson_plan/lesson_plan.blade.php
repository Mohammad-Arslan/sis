<form class="row g-2 needs-validation"
    action="{{ isset($lessonPlan) ? route('lesson-plans.update', $lessonPlan['id']) : route('lesson-plans.store') }}"
    method="POST" novalidate id="lesson_basicInfo_form">
    @csrf
    @if (isset($lessonPlan))
        @method('PATCH')
    @endif

    <style>
        /* Add your custom styles for the curriculum attainment targets section */
        #curriculum-attainment-targets {
            border: 1px solid #ddd; /* Add a border around the targets */
            padding: 10px; /* Add some padding to the targets */
            border-radius: 5px; /* Optional: Add rounded corners */
            margin-top: 10px; /* Optional: Add margin at the top */
            list-style-type: none; /* Remove list item bullets */
        }

        #curriculum-attainment-targets li {
            margin-bottom: 5px; /* Add some margin between each target */
        }
    </style>

    <div class="row mt-3">
        <div class="col-md-6 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('branch_id')) is-invalid @endif" id="branch_id"
                    name="branch_id" {{-- data-target="academic_year_id"
                        data-url="{{ route('list-academic-years') }}" --}}>
                    <option value="">Please select</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ isset($lessonPlan) && $branch->id == $lessonPlan['branch_id'] ? 'selected' : '' }}>
                            {{ $branch->br_name }}</option>
                    @endforeach
                </select>
                <label for="branch_id" class="form-label">Branch</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('branch_id'))
                        {{ $errors->first('branch_id') }}
                    @else
                        Branch is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('state_id')) is-invalid @endif" id="state_id"
                    name="state_id">
                    <option value="">Please select</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}"
                            {{ isset($lessonPlan) && $state->id == $lessonPlan['state_id'] ? 'selected' : '' }}>
                            {{ $state->state_name }}</option>
                    @endforeach
                </select>
                <label for="state_id" class="form-label">Province</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('state_id'))
                        {{ $errors->first('state_id') }}
                    @else
                        Province is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academicYear"
                    name="academic_year_id" aria-label="Academic year select" required>
                    <option value="">Please select a academic year</option>
                    @if (isset($academic_years))
                        @foreach ($academic_years as $academic_year)
                            <option value="{{ $academic_year->id }}"
                                {{ isset($lessonPlan) && $lessonPlan['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>
                                {{ $academic_year->title }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="academicYear" class="form-label">Academic Year</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('academic_class_id'))
                        {{ $errors->first('academic_class_id') }}
                    @else
                        Academic year is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="load-select form-select @if ($errors->has('class_id')) is-invalid @endif"
                    data-target="subject_id" data-url="{{ route('list-class-subjects') }}" id="class_id"
                    name="class_id" name="class_id" required>
                    <option value="">Please select a class</option>
                    @if (isset($classes))
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ isset($lessonPlan) && $lessonPlan['com_class_id'] == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="class" class="form-label">Class</label>
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
                <select class="form-select @if ($errors->has('subject_id')) is-invalid @endif" id="subject_id"
                    name="subject_id" aria-label="Section select" required>
                    <option value="">Please select a subject</option>
                    @if (isset($subjects))
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ $lessonPlan['subject_id'] == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject_name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="section" class="form-label">Subject</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('subject_id'))
                        {{ $errors->first('subject_id') }}
                    @else
                        Subject is required!
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('topic')) is-invalid @endif"
                    id="topic" name="topic" placeholder="Please enter topic"
                    value="{{ isset($lessonPlan) ? $lessonPlan['topic'] : '' }}" required>
                <label for="topic" class="form-label">Topic</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('topic'))
                        {{ $errors->first('topic') }}
                    @else
                        Topic is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-2">
            <div class="form-label-group in-border mb-1">
                <div class="input-group">
                    <input type="text" class="form-control @if ($errors->has('lesson_date')) is-invalid @endif"
                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                        value="{{ isset($lessonPlan) ? $lessonPlan['lesson_date'] : now() }}" name="lesson_date"
                        id="lesson_date">
                    <label for="lesson_date" class="form-label">Lesson Date</label>
                    <div class="input-group-text bg-primary border-primary text-white">
                        <i class="ri-calendar-2-line"></i>
                    </div>
                    <div class="invalid-tooltip">
                        @if ($errors->has('lesson_date'))
                            {{ $errors->first('lesson_date') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="load-select form-select @if ($errors->has('language')) is-invalid @endif"
                    id="language" name="language" required>
                    <option value="">Please select a language</option>
                    <option value="english"
                        {{ isset($lessonPlan) && $lessonPlan['language'] == 'english' ? 'selected' : '' }}>English
                    </option>
                    <option value="urdu"
                        {{ isset($lessonPlan) && $lessonPlan['language'] == 'urdu' ? 'selected' : '' }}>Urdu</option>
                </select>
                <label for="section" class="form-label">Language</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('language'))
                        {{ $errors->first('language') }}
                    @else
                        Language is required!
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="load-select form-select @if ($errors->has('term_id')) is-invalid @endif"
                    data-target="week_id" data-url="{{ route('list-term-weeks') }}" id="term_id" name="term_id"
                    required>
                    <option value="">Please select a term</option>
                    @if (isset($terms))
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}"
                                {{ isset($lessonPlan) && $lessonPlan['term_id'] == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="section" class="form-label">Term</label>
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
                <input type="text" class="form-control @if ($errors->has('theme')) is-invalid @endif"
                    id="theme" name="theme" placeholder="Please enter theme"
                    value="{{ isset($lessonPlan) ? $lessonPlan['theme'] : '' }}">
                <label for="theme" class="form-label">Theme</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('theme'))
                        {{ $errors->first('theme') }}
                    @else
                        Theme is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('chapter')) is-invalid @endif"
                    id="chapter" name="chapter" placeholder="Please enter chapter"
                    value="{{ isset($lessonPlan) ? $lessonPlan['chapter'] : '' }}">
                <label for="chapter" class="form-label">Unit/Chapter</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('chapter'))
                        {{ $errors->first('chapter') }}
                    @else
                        Chapter is required!
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('week_id')) is-invalid @endif" id="week_id"
                    name="week_id" required>
                    <option value="">Please select a week</option>
                    @if (isset($weeks))
                        @foreach ($weeks as $week)
                            <option value="{{ $week->id }}"
                                {{ isset($lessonPlan) && $lessonPlan['week_id'] == $week->id ? 'selected' : '' }}>
                                {{ $week->name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="section" class="form-label">Week</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('week_id'))
                        {{ $errors->first('week_id') }}
                    @else
                        Week is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('day')) is-invalid @endif" id="day"
                    name="day" required>
                    <option value="">Please select a day</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}"
                            {{ isset($lessonPlan) && $lessonPlan['day'] == $i ? 'selected' : '' }}>Day
                            {{ $i }}</option>
                    @endfor
                </select>
                <label for="section" class="form-label">Day</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('day'))
                        {{ $errors->first('day') }}
                    @else
                        Day is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('bocc_link')) is-invalid @endif"
                    id="bocc_link" name="bocc_link" placeholder="Please enter bocc link"
                    value="{{ isset($lessonPlan) ? $lessonPlan['bocc_link'] : '' }}">
                <label for="bocc_link" class="form-label">BOCC Link</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('bocc_link'))
                        {{ $errors->first('bocc_link') }}
                    @else
                        BOCC Link is required!
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5>Curriculum Attainment Targets</h5>
            <ul id="curriculum-attainment-targets">
            </ul>
        </div>
    </div>


    <div class="col-12 text-end">
        @if (auth()->user()->hasRole('subject_coordinator'))
            <input type="hidden" class="form-control" id="created_by" name="created_by"
                value="{{ auth()->user()->id }}">
        @endif
        <button class="btn btn-primary" type="submit" form="lesson_basicInfo_form">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change', '#branch_id', function(e) {
                /*$.ajax({
                    url: '{{ route('list-branch-classes') }}' + '?id=' + $(this).val(),
                    type: "GET",
                    cache: false,
                    success: function(data) {

                        var options = `<option value="">Please select a Class</option>`;

                        if (data) {
                            console.log(data)
                            $.each(data, function(index, value) {
                                options += '<option value="' + value.com_classes.id + '">' + value.com_classes.class_name + '</option>';
                            });
                        }

                        $('select[name="class_id"]').html(options).attr('disabled', false);
                    },
                    error: function() {

                    },
                    beforeSend: function() {
                        showLoading();
                    },
                    complete: function() {
                        hideLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('list-branch-terms') }}' + '?id=' + $(this).val(),
                    type: "GET",
                    cache: false,
                    success: function(data) {

                        var options = `<option value="">Please select a Term</option>`;

                        if (data) {
                            console.log(data)
                            $.each(data, function(index, value) {
                                options += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                        }

                        $('select[name="term_id"]').html(options).attr('disabled', false);
                    },
                    error: function() {

                    },
                    beforeSend: function() {
                        showLoading();
                    },
                    complete: function() {
                        hideLoading();
                    }
                });*/
            });



        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var classSelect = $('#class_id');
        var subjectSelect = $('#subject_id');
        var targetsContainer = $('#curriculum-attainment-targets');

        function updateTargets() {
            var classId = classSelect.val();
            var subjectId = subjectSelect.val();

            // Clear existing targets
            targetsContainer.empty();

            // Check if both class and subject are selected
            if (classId && subjectId) {
                // Make an AJAX request to fetch filtered targets
                $.ajax({
                    url: "{{ route('get-curriculum-attainment-targets') }}",
                    method: 'GET',
                    data: { class_id: classId, subject_id: subjectId },
                    success: function (data) {
                        if (data.length > 0) {
                            // Display the filtered targets
                            data.forEach(function (target) {
                                targetsContainer.append('<li>' + target.target + '</li>');
                            });
                        } else {
                            // Display a message if no targets are available
                            targetsContainer.append('<li>No curriculum attainment targets available for the selected class and subject.</li>');
                        }
                    },
                    error: function (error) {
                        console.error('Error fetching curriculum attainment targets:', error);
                    }
                });
            } else {
                // Display a message if class or subject is not selected
                targetsContainer.append('<li>Please select a class and a subject to view curriculum attainment targets.</li>');
            }
        }

        // Attach event listeners to class and subject selects
        classSelect.change(updateTargets);
        subjectSelect.change(updateTargets);

        // Initial update based on selected values
        updateTargets();
    });
</script>
@endpush

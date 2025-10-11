<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Student List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <!-- Small Tables -->
            <table class="table table-sm table-nowrap studentListTable">
                <thead>
                    <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">Student ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($studentBehaviourSkill))
                        @forelse($students as $student)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $student['roll_no'] }}</td>
                                <td>{{ view('students.student_image_tr', ['row' => $student]) }}</td>
                                <td>
                                    @if (str_contains(strtolower($studentBehaviourSkill['com_class']['class_name']), 'kg') ||
                                        str_contains(strtolower($studentBehaviourSkill['com_class']['class_name']), 'nursery'))
                                        <a href="javascript:void(0)"
                                            class="btn btn-sm badge bg-primary open-skill-modal"
                                            data-route="{{ route('student-behaviour-skill.skill-modal', ['student_id' => $student['id']]) }}">
                                            Skill
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            class="btn btn-sm badge bg-primary open-behaviour-modal"
                                            data-route="{{ route('student-behaviour-skill.behaviour-modal', ['student_id' => $student['id']]) }}">
                                            Behaviour
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Record Found</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No Record Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.open-behaviour-modal', function() {
                let route = $(this).data('route');

                let term_id = $('#term_id').val();
                if (term_id == "") {
                    alert('Please select TERM');
                    return false;
                }

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        academic_year_id: $('#academic_year_id').val(),
                        branch_id: $('#branch_id').val(),
                        class_id: $('#class_id').val(),
                        section_id: $('#section_id').val(),
                        term_id: term_id,
                    },
                    cache: false,
                    success: function(result) {
                        $('#behaviourModal .modal-body').html(result.html)
                        $('#behaviourModal').modal('show')
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 8000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });

            $(document).on('click', '.open-skill-modal', function() {
                let route = $(this).data('route');

                let term_id = $('#term_id').val();
                if (term_id == "") {
                    alert('Please select TERM');
                    return false;
                }

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        academic_year_id: $('#academic_year_id').val(),
                        branch_id: $('#branch_id').val(),
                        class_id: $('#class_id').val(),
                        section_id: $('#section_id').val(),
                        term_id: term_id,
                    },
                    cache: false,
                    success: function(result) {
                        $('#skillModal .modal-body').html(result.html)
                        $('#skillModal').modal('show')
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 8000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });

            $(document).on('click', '.submit_behaviour', function() {
                let student_id = $(this).data('student-id');
                let behaviours = [];

                $(".behaviours").each(function() {
                    behaviours[$(this).data('behaviour_id')] = $(this).val();
                });

                $.ajax({
                    url: '{{ route('student-behaviour-skill.store') }}',
                    type: 'POST',
                    data: {
                        academic_year_id: $('#academic_year_id').val(),
                        branch_id: $('#branch_id').val(),
                        class_id: $('#class_id').val(),
                        section_id: $('#section_id').val(),
                        term_id: $('#term_id').val(),
                        skill_behaviours: behaviours,
                        submission_type: 'behaviour',
                        student_id: student_id,
                        teacher_comments: $('#teacher_comment_behaviour').val(),
                        schoolhead_comments: $('#schoolhead_comment_behaviour').val(),
                        is_promoted: $('#is_promoted_behaviour').is(':checked') ? 1 : 0,
                        parent_meeting_attended: $('#parents_meeting_behaviour').is(':checked') ?
                            1 : 0,
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(result) {
                        $('#behaviourModal').modal('hide')
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 8000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });

            $(document).on('click', '.submit_skill', function() {
                let student_id = $(this).data('student-id');
                let skills = [];

                $(".skills").each(function() {
                    if ($(this).attr('type') === 'checkbox')
                        skills[$(this).data('skill_id')] = $(this).is(':checked') ? 'checked' : '';
                    else
                        skills[$(this).data('skill_id')] = $(this).val();
                });

                $.ajax({
                    url: '{{ route('student-behaviour-skill.store') }}',
                    type: 'POST',
                    data: {
                        academic_year_id: $('#academic_year_id').val(),
                        branch_id: $('#branch_id').val(),
                        class_id: $('#class_id').val(),
                        section_id: $('#section_id').val(),
                        term_id: $('#term_id').val(),
                        skill_behaviours: skills,
                        submission_type: 'skill',
                        student_id: student_id,
                        teacher_comments: $('#teacher_comment_skill').val(),
                        schoolhead_comments: $('#schoolhead_comment_skill').val(),
                        is_promoted: $('#is_promoted_skill').is(':checked') ? 1 : 0,
                        parent_meeting_attended: $('#parents_meeting_skill').is(':checked') ? 1 : 0,
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(result) {
                        $('#skillModal').modal('hide')
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 3000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });
        });
    </script>
@endpush

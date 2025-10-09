<div class="alert alert-success text-primary fade show" role="alert">
    <strong>Grading Key:</strong>
    <div class="row">
        @foreach ($grading_keys as $key)
            <div class="col-md-6">
                <strong>{{ $key['grading_key'] }}: {{ $key['title'] }}</strong>
                <p>{{ $key['description'] }}</p>
            </div>
        @endforeach
    </div>
</div>

@foreach ($skills as $level)
    @include('assessment.skill_behaviour.skill_levels', ['level' => $level])
@endforeach


<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Teacher Comments</label>
        <textarea class="form-control" id="teacher_comment_skill" placeholder="Enter Comments" rows="3" spellcheck="false">{{ isset($student_skill['student_behaviour_skill_remark']) ? $student_skill['student_behaviour_skill_remark']['teacher_comments'] : '' }}</textarea>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">School Head Comments</label>
        <textarea class="form-control" id="schoolhead_comment_skill" placeholder="Enter Comments" rows="3"
            spellcheck="false">{{ isset($student_skill['student_behaviour_skill_remark']) ? $student_skill['student_behaviour_skill_remark']['schoolhead_comments'] : '' }}</textarea>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Parent-Teacher Meeting Attended</label>
        <input type="checkbox" id="parents_meeting_skill"
            {{ isset($student_skill['student_behaviour_skill_remark']) && $student_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ? 'checked' : '' }}>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Promoted</label>
        <input type="checkbox" id="is_promoted_skill"
            {{ isset($student_skill['student_behaviour_skill_remark']) && $student_skill['student_behaviour_skill_remark']['is_promoted'] ? 'checked' : '' }}>
    </div>
</div>

<div class="text-end mt-3">
    <a href="javascript:void(0)" data-student-id="{{ $student_id }}" class="btn btn-primary submit_skill">Submit</a>
</div>

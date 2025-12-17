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
<!-- Variants -->
<table class="table table-sm table-bordered border-2">
    <thead>
        <tr class="bg-primary text-white">
            <th scope="col" style="width: 80%"></th>
            <th scope="col" style="width: 20%">Grade</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($general_behaviours as $behaviour)
            <tr class="text-white" style="background: #00A78D">
                <th colspan="2">{{ $behaviour['title'] }}</th>
            </tr>
            @foreach ($behaviour['children'] as $child)

                <tr>
                    <td>{{ $child['title'] }}</td>
                    <td>
                        {{-- <input type="text" class="form-control form-control-sm behaviours"
                            data-behaviour_id="{{ $child['id'] }}" placeholder="Enter Grade"
                            value="{{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) ? $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] : '' }}"> --}}

                            <select class="pull-right form-control form-select form-control-sm behaviours"
                            data-behaviour_id="{{ $child['id'] }}">
                            <option value="" selected>Enter Grade</option>
                            @foreach ($grading_keys as $grading_key)
                                <option
                                data-behaviour_id="{{ $child['id'] }}"
                                {{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) && $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] == $grading_key->grading_key ? 'selected' : '' }}
                                {{-- {{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) ? $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] : '' }} --}}
                                value="{{ $grading_key->grading_key }}">{{ $grading_key->grading_key }}
                                </option>
                            @endforeach

                        </select>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Teacher Comments</label>
        <textarea class="form-control" id="teacher_comment_behaviour" placeholder="Enter Comments" rows="3"
            spellcheck="false">{{ isset($student_behaviour['student_behaviour_skill_remark']) ? $student_behaviour['student_behaviour_skill_remark']['teacher_comments'] : '' }}</textarea>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">School Head Comments</label>
        <textarea class="form-control" id="schoolhead_comment_behaviour" placeholder="Enter Comments" rows="3"
            spellcheck="false">{{ isset($student_behaviour['student_behaviour_skill_remark']) ? $student_behaviour['student_behaviour_skill_remark']['schoolhead_comments'] : '' }}</textarea>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Parent-Teacher Meeting Attended</label>
        <input type="checkbox" id="parents_meeting_behaviour"
            {{ isset($student_behaviour['student_behaviour_skill_remark']) && $student_behaviour['student_behaviour_skill_remark']['parent_meeting_attended'] ? 'checked' : '' }}>
    </div>
</div>
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Promoted</label>
        <input type="checkbox" id="is_promoted_behaviour"
            {{ isset($student_behaviour['student_behaviour_skill_remark']) && $student_behaviour['student_behaviour_skill_remark']['is_promoted'] ? 'checked' : '' }}>
    </div>
</div>

<div class="text-end">
    <a href="javascript:void(0)" data-student-id="{{ $student_id }}"
        class="btn btn-primary submit_behaviour">Submit</a>
</div>

{{--substr($student_activity['assessment'],0,150).'...
(str_contains($student_activity['assessment'],'<iframe') || str_contains($student_activity['assessment'],'<img') || str_contains($student_activity['assessment'],'</a>') ? $student_activity['assessment'] : $student_activity['assessment'] )--}}

<tr class="remove_slo{{$slo_no}}">
    <td>
        <div class="open_editor_modal methodology_{{$slo_no}}_{{$activity_no}}" data-class-name="methodology_{{$slo_no}}_{{$activity_no}}">
            {!! isset($student_activity) && !empty($student_activity['methodology']) ? $student_activity['methodology'] : 'Click here to add content' !!}
        </div>
        <div style="display:none" class="methodology_{{$slo_no}}_{{$activity_no}}_text">{!! isset($student_activity) && !empty($student_activity['methodology']) ? $student_activity['methodology'] : '' !!}</div>
    </td>
    <td>
        <input type="text" class="form-control student_activity_duration" data-class-name="duration_{{$slo_no}}_{{$activity_no}}"
        value="{{isset($student_activity) && !empty($student_activity['duration']) ? $student_activity['duration'] : ''}}">
    </td>
    <td>
        <div class="open_editor_modal resource_{{$slo_no}}_{{$activity_no}}" data-class-name="resource_{{$slo_no}}_{{$activity_no}}">
            {!! isset($student_activity) && !empty($student_activity['resource']) ? $student_activity['resource'] : 'Click here to add content' !!}
        </div>
        <div style="display:none" class="resource_{{$slo_no}}_{{$activity_no}}_text">{!! isset($student_activity) && !empty($student_activity['resource']) ? $student_activity['resource'] : '' !!}</div>
    </td>
    <td>
        <div class="open_editor_modal assessment_{{$slo_no}}_{{$activity_no}}" data-class-name="assessment_{{$slo_no}}_{{$activity_no}}">
            {!! isset($student_activity) && !empty($student_activity['assessment']) ? $student_activity['assessment'] : 'Click here to add content' !!}
        </div>
        <div style="display:none" class="assessment_{{$slo_no}}_{{$activity_no}}_text">{!! isset($student_activity) && !empty($student_activity['assessment']) ? $student_activity['assessment'] : '' !!}</div>
    </td>
    <td>
        <div class="btn btn-sm btn-danger btn-icon waves-effect remove-activity" data-slo-no="{{$slo_no}}" data-activity-no="{{$activity_no}}">
            <i class="ri-delete-bin-5-line"></i>
        </div>
    </td>
</tr>
<input type="hidden" class="student_activity_id_{{$slo_no}}_{{$activity_no}}" value="{{isset($student_activity) ? $student_activity['id'] : 0}}">

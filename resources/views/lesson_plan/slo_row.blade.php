<tr class="remove_slo{{$slo_count}}">
    <td colspan="6" class="table-light">
        <div style="display: flex;justify-content: space-between">
            <b>{{__('SLO')}} {{$slo_count}}</b>
            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-icon waves-effect remove-slo" data-slo-no="{{$slo_count}}">
                <i class="ri-delete-bin-5-line"></i>
            </a>
        </div>
    </td>
</tr>
<tr class="remove_slo{{$slo_count}}">
    <td rowspan="{{isset($SLO['student_activities']) ? count($SLO['student_activities']) + 1 : '2'}}" class="slo_td{{$slo_count}}">
        <div class="open_editor_modal teacher-activity_{{$slo_count}}" data-class-name="teacher-activity_{{$slo_count}}">
            {!! isset($SLO) && !empty($SLO['teacher_activity']) ? $SLO['teacher_activity'] : 'Click here to add content' !!}
        </div>
        <div style="display:none" class="teacher-activity_{{$slo_count}}_text">{!! isset($SLO) && !empty($SLO['teacher_activity']) ? $SLO['teacher_activity'] : '' !!}</div>
    </td>
</tr>
@if(isset($SLO['student_activities']))
    @foreach($SLO['student_activities'] as $inner_index => $activity)
        @include('lesson_plan.activity_row',['activity_no' => $inner_index + 1,'slo_no' => $slo_count,'student_activity' => $activity])
    @endforeach
@else
    @include('lesson_plan.activity_row',['activity_no' => 1,'slo_no' => $slo_count])
@endif

<tr class="add_activity_row{{$slo_count}} remove_slo{{$slo_count}}">
    <td colspan="6" style="text-align: end">
        <a href="javascript:void(0)" class="btn btn-success btn-sm add-activity" data-slo-no="{{$slo_count}}"><i class="ri-add-line align-bottom me-1"></i>Add Activity</a>
    </td>
</tr>
<input type="hidden" class="slo_activity_count{{$slo_count}}" value="{{isset($SLO['student_activities']) && !empty($SLO['student_activities']) ? count($SLO['student_activities']) : '1'}}">
<input type="hidden" class="student_learning_outcome_id_{{$slo_count}}" value="{{isset($SLO) ? $SLO['id'] : '0'}}">

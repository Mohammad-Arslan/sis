<div style="display: flex;justify-content: space-between">
    @php($state = isset($row['state']) ? ' - '.$row['state']['state_name'] : '')
    <a href="javascript:void(0)" class="show_lesson_modal" data-lessonplan-id="{{$row->id}}" data-getweekdays-route="{{route('lesson-plans.get-week-days',$row->id)}}">{{$row['term']['name'] .' - '. $row['week']['name'] .' - '. $row['com_class']['class_name'] .' - '. $row['subject']['subject_name']. $state }}</a>
    <span class="badge bg-info">{{$row->approved_count .'/'. $row->day_count}}</span>
</div>

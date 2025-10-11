@if($row->approval_status == 'pending_for_approval')
    @if(auth()->user()->hasPermission('approve-lesson-plan'))
        <a class="btn btn-sm btn-success change_lessonplan_status" data-status="approved" data-approval-for="week" data-lessonplan-id="{{$row->id}}">Pending for approval</a>
    @else
        <a class="btn btn-sm btn-warning">Pending for approval</a>
    @endif
@elseif($row->approval_status == 'approved')
    @if(auth()->user()->hasPermission('publish-lesson-plan'))
        <a class="btn btn-sm btn-warning change_lessonplan_status" data-status="publish" data-approval-for="week" data-lessonplan-id="{{$row->id}}">Pending to Publish</a>
    @else
        <a class="btn btn-sm btn-warning">Pending to publish</a>
    @endif
@elseif($row->approval_status == 'publish')
    @if(auth()->user()->hasPermission('publish-lesson-plan'))
        <a class="btn btn-sm btn-secondary change_lessonplan_status" data-status="approved" data-approval-for="week" data-lessonplan-id="{{$row->id}}">Unpublish</a>
    @else
    <a class="btn btn-sm btn-secondary">Published</a>
    @endif
@elseif(auth()->user()->hasPermission('sendfor-approval-lessonplan'))
    <a class="btn btn-sm btn-primary change_lessonplan_status" data-status="pending_for_approval" data-approval-for="week" data-lessonplan-id="{{$row->id}}">Send for approval</a>
@else
    {{-- <a class="btn btn-sm btn-info">Draft</a> --}}
@endif

@if(isset($request->from_branch_setup_student))
    <a href="{{route('class-section-students',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Manage Students">
        <i class="mdi mdi-account-group"></i>
    </a>
@elseif(isset($request->from_branch_setup_teacher))
    <a href="{{route('class-teacher-subjects',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Manage Teachers">
        <i class="mdi mdi-account-tie"></i>
    </a>
@endif

<a href="{{ route('branch-class-sections.destroy', $row->id) }}" 
   data-table="branch-classes-table" 
   class="btn btn-sm btn-danger btn-icon waves-effect delete-record" 
   title="Delete Class-Section Relationship">
    <i class="ri-delete-bin-5-line"></i>
</a> 
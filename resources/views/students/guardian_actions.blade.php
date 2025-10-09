@if(isset($request->from_branch_setup_student))
    <a href="{{route('class-section-students',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@elseif(isset($request->from_branch_setup_teacher))
    <a href="{{route('class-teacher-subjects',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@else
    @permission('edit-student-parent-info')
    <a href="{{route('guardians.edit',$row->id).'?tab=guardian'}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light {{isset($request->guardian_id) && $request->guardian_id == $row->id ? 'disabled' : ''}}">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
    @endpermission
    @permission('delete-student-parent-info')
    <a href="{{route('guardians.destroy',$row->id)}}" data-table="guardian-data-table" class="btn btn-sm btn-danger btn-icon waves-effect delete-record {{isset($request->guardian_id) && $request->guardian_id == $row->id ? 'disabled' : ''}}">
        <i class="ri-delete-bin-5-line"></i>
    </a>
    @endpermission
@endif

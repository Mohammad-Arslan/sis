
@permission('edit-admission-inquiry')
    <a href="{{ route('admission-query.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i></a>
@endpermission
<a href="{{ route('admissionFollowUp.index', ['admission_query_id'=>$row->id]) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="ri-user-settings-line"></i></a>



{{-- @permission('delete-student')
     <a href="javascript:void(0);"
        class="btn btn-sm btn-success btn-icon waves-effect">
        <i class="mdi mdi-eye-circle"></i>
    </a>
@endpermission --}}


{{-- @if (!auth()->user()->hasPermission('edit-admission-quries') &&
    !auth()->user()->hasPermission('delete-admission-quries'))
    <span>N/A</span>
@endif --}}

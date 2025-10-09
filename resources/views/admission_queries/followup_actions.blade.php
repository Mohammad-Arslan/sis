
@permission('edit-admission-follow-up')
    <a href="{{ route('admissionFollowUp.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i></a>
@endpermission



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

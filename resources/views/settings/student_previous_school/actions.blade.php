@permission('edit-student-previous-schools')
    <a href="{{ route('student-previous-school.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-student-previous-schools')
    <a href="{{ route('student-previous-school.destroy', $row->id) }}" data-table="student-previous-school"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-student-previous-schools') &&
    !auth()->user()->hasPermission('delete-student-previous-schools'))
    <span>N/A</span>
@endif

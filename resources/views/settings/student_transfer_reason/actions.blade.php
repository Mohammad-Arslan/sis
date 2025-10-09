@permission('edit-transfer-reason')
    <a href="{{ route('student-transfer-reason.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-transfer-reason')
    <a href="{{ route('student-transfer-reason.destroy', $row->id) }}" data-table="student-transfer-reason-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-transfer-reason') && !auth()->user()->hasPermission('delete-transfer-reason'))
    <span>N/A</span>
@endif

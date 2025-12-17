@permission('edit-department')
    <a href="{{ route('departments.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-department')
    <a href="{{ route('departments.destroy', $row->id) }}" data-table="department-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission
@if (!auth()->user()->hasPermission('edit-department') &&
    !auth()->user()->hasPermission('delete-department'))
    <span>N/A</span>
@endif

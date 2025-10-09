@permission('edit-branch-security')
    <a href="{{ route('branch-securities.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-branch-security')
    <a href="{{ route('branch-securities.destroy', $row->id) }}" data-table="branch-securities-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-branch-security') &&
    !auth()->user()->hasPermission('delete-branch-security'))
    <span>N/A</span>
@endif

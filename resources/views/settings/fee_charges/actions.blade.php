@permission('edit-fee-charge')
    <a href="{{ route('fee-charges.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-fee-charge')
    <a href="{{ route('fee-charges.destroy', $row->id) }}" data-table="fee-charges-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-charge') &&
    !auth()->user()->hasPermission('delete-fee-charge'))
    <span>N/A</span>
@endif

@permission('edit-fee-concession')
    <a href="{{ route('fee-concessions.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-fee-concession')
    <a href="{{ route('fee-concessions.destroy', $row->id) }}" data-table="fee-concessions-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-concession') &&
    !auth()->user()->hasPermission('delete-fee-concession'))
    <span>N/A</span>
@endif

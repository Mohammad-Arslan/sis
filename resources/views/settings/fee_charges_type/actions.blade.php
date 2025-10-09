@permission('edit-fee-charge-type')
    <a href="{{ route('fee-charges-type.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-fee-charge-type')
    <a href="{{ route('fee-charges-type.destroy', $row->id) }}" data-table="fee-charges-types-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-charge-type') &&
    !auth()->user()->hasPermission('delete-fee-charge-type'))
    <span>N/A</span>
@endif

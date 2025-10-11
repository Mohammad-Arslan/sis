@permission('edit-fee-concession-type')
    <a href="{{ route('fee-concessions-type.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-fee-concession-type')
    <a href="{{ route('fee-concessions-type.destroy', $row->id) }}" data-table="fee-concessions-types-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-concession-type') &&
    !auth()->user()->hasPermission('delete-fee-concession-type'))
    <span>N/A</span>
@endif
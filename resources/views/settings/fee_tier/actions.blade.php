@permission('edit-fee-tier')
    <a href="{{ route('fee-tier.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-fee-tier')
    <a href="{{ route('fee-tier.destroy', $row->id) }}" data-table="fee-tier-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-tier') && !auth()->user()->hasPermission('delete-fee-tier'))
    <span>N/A</span>
@endif

@permission('edit-fee-period')
    <a href="{{ route('fee-period.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-fee-period')
    <a href="{{ route('fee-period.destroy', $row->id) }}" data-table="fee-period-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-period') && !auth()->user()->hasPermission('delete-fee-period'))
    <span>N/A</span>
@endif

@permission('edit-withdrawal-reason')
    <a href="{{ route('withdrawal-reason.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-withdrawal-reason')
    <a href="{{ route('withdrawal-reason.destroy', $row->id) }}" data-table="withdrawal-reason-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-withdrawal-reason') && !auth()->user()->hasPermission('delete-withdrawal-reason'))
    <span>N/A</span>
@endif

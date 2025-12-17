@permission('edit-withdrawal-cancellation')
    <a href="{{ route('withdrawal-cancellation-reason.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-withdrawal-cancellation')
    <a href="{{ route('withdrawal-cancellation-reason.destroy', $row->id) }}" data-table="withdrawal-cancellation-reason-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-withdrawal-cancellation') && !auth()->user()->hasPermission('delete-withdrawal-cancellation'))
    <span>N/A</span>
@endif

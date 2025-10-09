@permission('edit-state')
    <a href="{{ route('states.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-state')
    <a href="{{ route('states.destroy', $row->id) }}" data-table="state-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-state') &&
    !auth()->user()->hasPermission('delete-state'))
    <span>N/A</span>
@endif

@permission('edit-region')
    <a href="{{ route('regions.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i></a>
@endpermission

@permission('delete-region')
    <a href="{{ route('regions.destroy', $row->id) }}" data-table="region-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-region') &&
    !auth()->user()->hasPermission('delete-region'))
    <span>N/A</span>
@endif

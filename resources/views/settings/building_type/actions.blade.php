@permission('edit-building-type')
    <a href="{{ route('building-type.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-building-type')
    <a href="{{ route('building-type.destroy', $row->id) }}" data-table="building-type-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-building-type') && !auth()->user()->hasPermission('delete-building-type'))
    <span>N/A</span>
@endif

@permission('edit-town')
    <a href="{{ route('towns.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i></a>
@endpermission
@permission('delete-town')
    <a href="{{ route('towns.destroy', $row->id) }}" data-table="town-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-town') &&
    !auth()->user()->hasPermission('delete-town'))
    <span>N/A</span>
@endif

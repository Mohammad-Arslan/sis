@permission('edit-designation')
    <a href="{{ route('designations.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-designation')
    <a href="{{ route('designations.destroy', $row->id) }}" data-table="designation-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission
@if (!auth()->user()->hasPermission('edit-designation') &&
    !auth()->user()->hasPermission('delete-designation'))
    <span>N/A</span>
@endif

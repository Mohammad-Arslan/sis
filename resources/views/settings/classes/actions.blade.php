@permission('edit-class')
    <a href="{{ route('classes.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-class')
    <a href="{{ route('classes.destroy', $row->id) }}" data-table="classes-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-class') &&
    !auth()->user()->hasPermission('delete-class'))
    <span>N/A</span>
@endif

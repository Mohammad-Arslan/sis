@permission('edit-subject')
    <a href="{{ route('subjects.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-subject')
    <a href="{{ route('subjects.destroy', $row->id) }}" data-table="subjects-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-subject') &&
    !auth()->user()->hasPermission('delete-subject'))
    <span>N/A</span>
@endif

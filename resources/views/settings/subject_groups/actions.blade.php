@permission('edit-subject-group')
    <a href="{{ route('subject-groups.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-subject-group')
    <a href="{{ route('subject-groups.destroy', $row->id) }}" data-table="subject-groups-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-subject-group') &&
    !auth()->user()->hasPermission('delete-subject-group'))
    <span>N/A</span>
@endif

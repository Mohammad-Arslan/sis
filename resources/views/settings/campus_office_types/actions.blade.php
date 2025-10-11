@permission('edit-campus-type')
        <a href="{{ route('campusOfficeType.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
            <i class="mdi mdi-lead-pencil"></i></a>
@endpermission
@permission('delete-campus-type')
        <a href="{{ route('campusOfficeType.destroy', $row->id) }}" data-table="campus-type-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
            <i class="ri-delete-bin-5-line"></i>
        </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-campus-type') &&
    !auth()->user()->hasPermission('delete-campus-type'))
    <span>N/A</span>
@endif

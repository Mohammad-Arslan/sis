@permission('edit-application-type')
    <a href="{{ route('application-type.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-application-type')
    <a href="{{ route('application-type.destroy', $row->id) }}" data-table="application-type-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-application-type') && !auth()->user()->hasPermission('delete-application-type'))
    <span>N/A</span>
@endif

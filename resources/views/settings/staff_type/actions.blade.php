@permission('edit-staff-type')
    <a href="{{ route('staff-type.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-staff-type')
    <a href="{{ route('staff-type.destroy', $row->id) }}" data-table="staff-type-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-staff-type') && !auth()->user()->hasPermission('delete-staff-type'))
    <span>N/A</span>
@endif

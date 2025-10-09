@permission('edit-working-shift')
    <a href="{{ route('working-shift.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-working-shift')
    <a href="{{ route('working-shift.destroy', $row->id) }}" data-table="working-shift-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-working-shift') && !auth()->user()->hasPermission('delete-working-shift'))
    <span>N/A</span>
@endif

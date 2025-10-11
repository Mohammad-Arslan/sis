@permission('edit-branch-schedule')
    <a href="{{ route('branch-working-shift.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-branch-schedule')
    <a href="{{ route('branch-working-shift.destroy', $row->id) }}" data-table="branch-schedule-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-branch-schedule') && !auth()->user()->hasPermission('delete-branch-schedule'))
    <span>N/A</span>
@endif

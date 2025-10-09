@permission('edit-working-day')
    <a href="{{ route('working-day.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-working-day')
    <a href="{{ route('working-day.destroy', $row->id) }}" data-table="working-day-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-working-day') && !auth()->user()->hasPermission('delete-working-day'))
    <span>N/A</span>
@endif

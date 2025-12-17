@permission('edit-official-leave')
    <a href="{{ route('official-leave-day.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-official-leave')
    <a href="{{ route('official-leave-day.destroy', $row->id) }}" data-table="employee-official-leave-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-official-leave') && !auth()->user()->hasPermission('delete-official-leave'))
    <span>N/A</span>
@endif

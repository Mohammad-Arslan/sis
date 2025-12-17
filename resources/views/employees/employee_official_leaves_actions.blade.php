@permission('edit-employee-official-leave')
{{--  edit-employee--}}
    <a href="{{ route('employee-official-leave-day.edit', [$row->id, 'id' => $row->id,'employee_id' => $row->employee_id]) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-employee-official-leave')
    <a href="{{ route('employee-official-leave-day.destroy', $row->id) }}" data-table="employee-official-leave-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-employee-official-leave') && !auth()->user()->hasPermission('delete-employee-official-leave'))
    <span>N/A</span>
@endif

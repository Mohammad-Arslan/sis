@permission('edit-employee-shift')
{{--  edit-employee--}}
    <a href="{{ route('employee-shift.edit', [$row->id, 'id' => $row->id,'employee_id' => $row->employee_id]) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-employee-shift')
    <a href="{{ route('employee-shift.destroy', $row->id) }}" data-table="employee-working-shifts-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-employee-shift') && !auth()->user()->hasPermission('delete-employee-shift'))
    <span>N/A</span>
@endif

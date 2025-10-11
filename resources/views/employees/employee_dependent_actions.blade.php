<a href="{{route('employee-dependent.edit',[$row->id, 'id' => $row->id,'employee_id' => $row->employee_id])}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="ri-settings-4-line"></i>
</a>
<a href="{{route('employee-dependent.destroy',$row->id)}}" data-table="employee-dependent-data-table" class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

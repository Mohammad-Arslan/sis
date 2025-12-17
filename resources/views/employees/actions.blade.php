@permission('generate-salary-slip')
    <a href="{{ route('employee.salary-slip', $row->id) }}" target="_blank" title="Generate Salary Slip" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="ri-article-line"></i>
    </a>
@endpermission
@permission('view-employee-profile')
@if($row->id)
<button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" title="View Profile" data-url="{{route('employees.show', $row->id)}}" data-target="#profileEmpModal"><i class="mdi mdi-account"></i></button>
@else
<button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light" title="View Profile" disabled><i class="mdi mdi-account"></i></button>
@endif
@endpermission
@permission(['edit-employee-basic-info' , 'edit-employee-service-info' , 'edit-employee-company-info'])
<a href="{{route('edit-employee',$row->id)}}?tab=basic_info" title="Edit" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="ri-user-settings-line"></i>
</a>
@endpermission
@permission('edit-employee-password')
@if($row->user_id)
<button type="button" class="btn btn-sm btn-warning btn-icon waves-effect waves-light show-modal" title="Change Password" data-url="{{route('edit-employee-password', $row->user_id)}}" data-target="#editEmpPasswordModal"><i class="mdi mdi-form-textbox-password"></i></button>
@else
<button type="button" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Change Password" disabled><i class="mdi mdi-form-textbox-password"></i></button>
@endif
@endpermission
@permission('delete-employee')
<a href="{{route('employees.destroy',$row->id)}}" data-table="employee-table" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
@endpermission

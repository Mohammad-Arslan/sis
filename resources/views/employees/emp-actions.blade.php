@permission('view-employee-profile')
<button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" data-url="{{route('employees.show', $row->id)}}" data-target="#profileEmpModal"><i class="mdi mdi-account"></i></button>
@endpermission
@permission(['edit-employee-basic-info' , 'edit-employee-service-info' , 'edit-employee-company-info'])
<a href="{{route('edit-employee',$row->id)}}?tab=basic_info" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
@endpermission

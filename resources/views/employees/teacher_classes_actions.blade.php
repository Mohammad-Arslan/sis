<button type="button" class="btn btn-sm btn-success btn-icon waves-effect waves-light show-modal" data-url="{{ route('class-teachers-type').'?class_teacher_id='.$row->id }}" data-target="#teacherTypeModal"><i class="mdi mdi-pencil"></i></button>
@permission('delete-class-teacher')
<a href="{{ route('class-teachers.destroy', $row->id) }}" data-table="class-section-teachers-data-table"
   class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
@endpermission

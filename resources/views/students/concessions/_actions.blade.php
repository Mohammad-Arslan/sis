 <a href="{{ route('student-concession.edit', $row->id) }}"
    class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
@permission('delete-student-concession')
<a href="{{ route('student-concession.destroy', $row->id) }}" data-table="student-concession-datatable"
  class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
  <i class="ri-delete-bin-5-line"></i>
</a>
@endpermission

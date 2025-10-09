<a href="{{ route('subject-remarks.edit', $row->subject_remark_id) }}"
    class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
<a href="{{ route('subject-remarks.destroy', $row->subject_remark_id) }}" data-table="subject-remarks-datatable"
    class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

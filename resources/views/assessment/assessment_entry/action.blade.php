<a href="{{ route('assessment-entry.edit', $row->assessment_entry_id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
<a href="{{route('assessment-entry.destroy',$row->assessment_entry_id)}}" data-table="assessment-entries-datatable" class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

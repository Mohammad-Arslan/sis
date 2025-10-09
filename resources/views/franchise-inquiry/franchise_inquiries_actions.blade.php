<a href="{{route('franchises-inquiry.edit',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
<a href="{{route('franchises-inquiry.destroy',$row->id)}}" data-table="franchise-inquiries-data-table" class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

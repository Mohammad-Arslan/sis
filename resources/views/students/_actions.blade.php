<a href="{{route('guardians.edit',$row->id).'?tab=guardian'}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
<a href="{{route('guardians.destroy',$row->id)}}" data-table="guardian-data-table" class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
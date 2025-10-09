<a href="javascript:void(0);" title="Approve" data-guardian-info-update-id="{{ $row->id }}"
    class="btn btn-sm btn-success btn-icon waves-effect waves-light button-update-guardian-info">
    <i class="mdi mdi-check"></i>
</a>
<a href="{{ route('guardian-info-update.destroy', $row->id) }}" title="Delete" data-table="guardian-info-data-table"
    class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

@permission('edit-beams-challan')
    <a href="{{ route('beams-challans.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-beams-challan')
    <a href="{{ route('beams-challans.destroy', $row->id) }}" data-table="beams-challans-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission
@if (!auth()->user()->hasPermission('edit-beams-challan') &&
    !auth()->user()->hasPermission('delete-beams-challan'))
    <span>N/A</span>
@endif

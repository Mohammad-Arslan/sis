@permission('edit-city')
    <a href="{{ route('cities.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-city')
    <a href="{{ route('cities.destroy', $row->id) }}" data-table="city-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-city') &&
    !auth()->user()->hasPermission('delete-city'))
    <span>N/A</span>
@endif

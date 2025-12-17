@permission('edit-company')
    <a href="{{ route('countries.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-company')
    <a href="{{ route('countries.destroy', $row->id) }}" data-table="country-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-country') &&
    !auth()->user()->hasPermission('delete-country'))
    <span>N/A</span>
@endif

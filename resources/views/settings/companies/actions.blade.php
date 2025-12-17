@permission('edit-company')
    <a href="{{ route('companies.edit', $row->id). '?tab=basic' }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i></a>
@endpermission

@permission('delete-company')
    <a href="{{ route('companies.destroy', $row->id) }}" data-table="company-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-company') &&
    !auth()->user()->hasPermission('delete-company'))
    <span>N/A</span>
@endif

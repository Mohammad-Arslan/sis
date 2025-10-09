@permission('edit-academic-year')
    <a href="{{ route('academic-year.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-academic-year')
    <a href="{{ route('academic-year.destroy', $row->id) }}" data-table="academic-year-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-academic-year') && !auth()->user()->hasPermission('delete-academic-year'))
    <span>N/A</span>
@endif

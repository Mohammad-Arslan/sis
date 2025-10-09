@permission('edit-section')
    <a href="{{ route('sections.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-section')
    <a href="{{ route('sections.destroy', $row->id) }}" data-table="section-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-section') &&
    !auth()->user()->hasPermission('delete-section'))
    <span>N/A</span>
@endif

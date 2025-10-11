@permission('edit-academic-year-working-days')
    <a href="{{ route('academic-year-working-days.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-academic-year-working-days')
    <a href="{{ route('academic-year-working-days.destroy', $row->id) }}" data-table="academic-year-working-days-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-academic-year-working-days') &&
    !auth()->user()->hasPermission('delete-academic-year-working-days'))
    <span>N/A</span>
@endif

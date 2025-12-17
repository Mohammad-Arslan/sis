@if (auth()->user()->hasRole('super_admin'))
    <a href="{{ route('branch-academic-year.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
    <a href="{{ route('branch-academic-year.destroy', $row->id) }}" data-table="branch-academic-year-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@else
    <span>N/A</span>
@endif

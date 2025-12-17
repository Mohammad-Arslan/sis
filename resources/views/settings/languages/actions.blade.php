@permission('edit-language')
    <a href="{{ route('language.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-language')
    <a href="{{ route('language.destroy', $row->id) }}" data-table="language-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-language') && !auth()->user()->hasPermission('delete-language'))
    <span>N/A</span>
@endif

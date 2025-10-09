@permission('edit-follow-up-type')
        <a href="{{ route('followUpType.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
            <i class="mdi mdi-lead-pencil"></i></a>
@endpermission
@permission('delete-follow-up-type')
        <a href="{{ route('followUpType.destroy', $row->id) }}" data-table="followup-type-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
            <i class="ri-delete-bin-5-line"></i>
        </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-follow-up-type') &&
    !auth()->user()->hasPermission('delete-follow-up-type'))
    <span>N/A</span>
@endif

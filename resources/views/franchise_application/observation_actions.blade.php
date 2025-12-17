@permission('edit-franchise-app-remarks')
    @if($row->user_id == auth()->user()->id)
        <a href="{{ route('frachiseApplicationRemark.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
            <i class="mdi mdi-lead-pencil"></i></a>
    @endif
@endpermission
@permission('delete-franchise-app-remarks')
        <a href="{{ route('frachiseApplicationRemark.destroy', $row->id) }}" data-table="observation-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
            <i class="ri-delete-bin-5-line"></i>
        </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-app-remarks') &&
    !auth()->user()->hasPermission('delete-franchise-app-remarks'))
    <span>N/A</span>
@endif

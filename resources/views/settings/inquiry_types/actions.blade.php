@permission('edit-inquiry-type')
    @if($row->id != 1)
        <a href="{{ route('inquiry-type.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
            <i class="mdi mdi-lead-pencil"></i></a>
    @endif
@endpermission
@permission('delete-inquiry-type')
    @if($row->id != 1)
        <a href="{{ route('inquiry-type.destroy', $row->id) }}" data-table="inquiry-type-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endif
@endpermission

@if (!auth()->user()->hasPermission('edit-inquiry-type') &&
    !auth()->user()->hasPermission('delete-inquiry-type'))
    <span>N/A</span>
@endif

@permission('add-classes-class-group')
    <button type="button" id="{{ $row->id }}" class="btn btn-info btn-sm popup"><span>Add Class</span></button>
@endpermission

@permission('edit-class-group')
    <a href="{{ route('class-groups.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-class-group')
    <a href="{{ route('class-groups.destroy', $row->id) }}" data-table="class-groups-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-class-group') &&
    !auth()->user()->hasPermission('delete-class-group') &&
    !auth()->user()->hasPermission('add-classes-class-group'))
    <span>N/A</span>
@endif

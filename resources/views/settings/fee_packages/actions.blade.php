@permission('add-charges-fee-packages')
    <button type="button" id="{{ $row->id }}"  class="btn btn-info btn-sm popup"><span>Add Charges</span></button>
@endpermission

@permission('edit-fee-package')
    <a href="{{ route('fee-packages.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-fee-package')
    <a href="{{ route('fee-packages.destroy', $row->id) }}" data-table="fee-packages-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-fee-package') &&
    !auth()->user()->hasPermission('delete-fee-package') &&
    !auth()->user()->hasPermission('add-charges-fee-packages'))
    <span>N/A</span>
@endif

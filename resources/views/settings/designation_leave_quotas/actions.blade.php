@permission('edit-designation-leave-quota')
    <a href="{{ route('designation-leave-quota.edit', $row->id) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('delete-designation-leave-quota')
    <a href="javascript:;" data-table="example" data-id="{{ $row->id }}"
        data-route="{{ route('designation-leave-quota.delete') }}"
        class="btn btn-sm btn-danger btn-icon waves-effect  delete-leave-quota">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

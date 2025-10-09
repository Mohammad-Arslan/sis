{{-- @permission('edit-system-module') --}}
<a href="{{ route('notification-logs', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="ri-notification-line"></i>
</a>
{{-- @endpermission --}}
{{-- @permission('delete-system-module')
    <a href="{{ route('system-modules.destroy', $row->id) }}" data-table="system-modules-data-table"
        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-system-module') &&
    !auth()->user()->hasPermission('delete-system-module'))
    <span>N/A</span>
@endif --}}

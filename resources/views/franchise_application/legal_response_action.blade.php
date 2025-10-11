@permission('edit-franchise-application-legal')
    <a href="{{route('franchise-application-la.edit', $row->id)}}" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
@endpermission
@permission('delete-franchise-app-legal')
    <a href="{{route('franchise-application-la.destroy', $row->id)}}" class="link-danger fs-15 delete-record" data-table="legal-data-table" data-id = "{{$row->id}}"><i class="ri-delete-bin-line"></i></a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-application-legal') && !auth()->user()->hasPermission('delete-franchise-app-legal'))
    <span>N/A</span>
@endif

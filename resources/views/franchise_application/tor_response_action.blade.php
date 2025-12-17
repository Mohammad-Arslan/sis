@permission('edit-franchise-application-tor')
    <a href="{{route('franchise-application-tors.edit', $row->id)}}" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
@endpermission

@permission('delete-franchise-application-tor')
    <a href="{{route('franchise-application-tors.destroy', $row->id)}}" class="link-danger fs-15 delete-record" data-table="tors-data-table" data-id = "{{$row->id}}"><i class="ri-delete-bin-line"></i></a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-application-tor') && !auth()->user()->hasPermission('delete-franchise-application-tor'))
    <span>N/A</span>
@endif

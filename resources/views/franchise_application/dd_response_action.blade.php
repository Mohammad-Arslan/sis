@permission('edit-franchise-application-dd')
    <a href="{{route('franchise-application-dd.edit', $row->id)}}" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
@endpermission
@permission('delete-franchise-application-dd')
    <a href="{{route('franchise-application-dd.destroy', $row->id)}}" class="link-danger fs-15 delete-record" data-table="dd-data-table" data-id = "{{$row->id}}"><i class="ri-delete-bin-line"></i></a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-application-dd') && !auth()->user()->hasPermission('delete-franchise-application-dd'))
    <span>N/A</span>
@endif

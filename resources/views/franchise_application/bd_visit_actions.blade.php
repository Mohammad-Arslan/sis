@permission('edit-franchise-application-bd')
    <a href="{{route('franchise-application-bd.edit', $row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
      <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-application-bd'))
    <span>N/A</span>
@endif

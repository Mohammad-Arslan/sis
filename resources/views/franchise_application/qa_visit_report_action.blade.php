@permission('edit-franchise-application-qa')
    <a href="{{route('franchise-application-qa.edit',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Edit">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('view-franchise-app-qa-report')
<a href="{{route('franchise-application-qa.view_report', $row->id)}}" class="btn btn-sm btn-info btn-icon waves-effect waves-light" title="View QA Report"><i class="mdi mdi-eye"></i></a>
@endpermission
@permission('download-pdf-franchise-app-qa')
    <a href="{{route('franchise-application-qa.create_pdf',$row->id)}}" class="btn btn-sm btn-primary" title="Download QA Report">
        Download PDF
    </a>
@endpermission

@if (!auth()->user()->hasPermission('edit-franchise-application-qa') &&
!auth()->user()->hasPermission('download-pdf-franchise-app-qa'))
    <span>N/A</span>
@endif

{{--
<div class="dropdown text-right">
    <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-2-fill h4 text-muted"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{route('franchise-application-qa.edit',$row->id)}}">Edit</a>
        <a class="dropdown-item" href="{{route('franchise-application-qa.create_pdf',$row->id)}}">Download PDF</a>
    </div>
</div>
--}}

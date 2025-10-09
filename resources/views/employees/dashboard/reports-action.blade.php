
<a href="{{ route('franchise-application-iasf-form', $row->id) }}"  target="_blank" class="btn btn-sm btn-primary pull-right ml-3">
    View IASF Report
</a>

{{-- @if ($row->franchise_application_qa && auth()->user()->hasPermission('download-pdf-franchise-app-qa'))
<a class="btn btn-sm btn-success btn-label waves-effect waves-light" href="{{ route('franchise-application-qa.create_pdf', $row->franchise_application_qa->id) }}" target="_blank"><i
class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> QA Report PDF
</a>
@endif --}}

@permission('add-franchapp-upload-doc')
<a class="btn btn-sm btn-success btn-label waves-effect waves-light show-modal uploadDocument" data-url="{{ route('franchise-application-docs',['id'=> $row['id']]) }}" data-target="#uploadDocumentModal" >
    <i
    class="mdi mdi-eye label-icon align-middle fs-16 me-2"></i>
     Documents</a>
@endpermission
@if ($row->franchise_application_qa && auth()->user()->hasPermission('view-franchise-app-qa-report'))
<a class="btn btn-sm btn-success btn-label waves-effect waves-light" href="{{route('franchise-application-qa.view_report',  $row->franchise_application_qa->id)}}" target="_blank"><i
    class="mdi mdi-eye label-icon align-middle fs-16 me-2"></i> QA Report
    </a>
{{-- <a href="{{route('franchise-application-qa.view_report',  $row->franchise_application_qa->id)}}" class="btn btn-sm btn-success btn-label waves-effect waves-light" title="View QA Report" target="_blank"><i class="mdi mdi-eye label-icon align-middle fs-16 me-2">QA Report</i></a> --}}
@endif


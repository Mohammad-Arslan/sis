<div class="dropdown text-right">
    <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-2-fill h4 text-muted"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @permission('add-franchapp-upload-doc')
            <a class="dropdown-item uploadDocument" data-target="#uploadDocumentModal" data-franchise_application_id="{{$row->id}}">Upload Documents</a>
        @endpermission
        @permission('edit-franchise-application')
            <a class="dropdown-item" href="{{route('franchise-applications.edit', $row->id)}}">Edit</a>
        @endpermission
        @if($row->agreement_type == 'FA' || $row->agreement_type == 'MOU')
        @permission('add-franchise-application-bd')
            <a class="dropdown-item" href="{{route('franchise-application-bd.create', $row->id)}}">BD Site Details </a>
        @endpermission
        @permission('add-franchise-application-qa')
            <a class="dropdown-item" href="{{route('franchise-application-qa.create', $row->id)}}">QA Site Report</a>
        @endpermission
        {{-- <a class="dropdown-item" href="{{route('applications','bd')}}">BD Application</a> --}}
        {{-- <a class="dropdown-item" href="{{route('franchise-applications.bdVisitFormView')}}">BD Visit</a> --}}
        {{-- <a class="dropdown-item" href="{{route('franchise-application-qa.create',$row->id)}}">QA Application</a> --}}

        @permission('add-franchise-application-tor')
            <a class="dropdown-item" href="{{route('franchise-application-tors.create',$row->id)}}"
                title="Franchise Application & Approved TOR Checklist"
                >FA & TOR Checklist</a>
        @endpermission
        @permission('add-franchise-application-legal')
            <a class="dropdown-item" href="{{route('franchise-application-la.create',$row->id)}}">Legal Review</a>
        @endpermission
        @permission('add-franchise-application-dd')
            <a class="dropdown-item" href="{{route('franchise-application-dd.create',$row->id)}}">DD Review / Approval</a>
        @endpermission
        @permission('add-franchise-app-remarks')
            <a class="dropdown-item" href="{{route('application_observations',['franchise_application_id' => $row->id])}}">Observations</a>
        @endpermission

        {{-- @permission('inquiry-assessment-survey') --}}
            <a class="dropdown-item" href="{{route('franchise-application-iasf-form',$row->id)}}">IASF Report</a>
        {{-- @endpermission --}}
        @endif
    </div>
</div>

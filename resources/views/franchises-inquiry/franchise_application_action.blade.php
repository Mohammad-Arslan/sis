<div class="dropdown text-right">
    <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-2-fill h4 text-muted"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item uploadDocument" data-target="#uploadDocumentModal" data-inquiry_id="{{$row->id}}">Upload Documents</a>
        <a class="dropdown-item" href="{{route('franchises.edit', $row->id)}}">Edit</a>
        <a class="dropdown-item" href="{{route('applications','qa')}}">QA Application</a>
        <a class="dropdown-item" href="{{route('applications','bd')}}">BD Application</a>
        <a class="dropdown-item" href="{{route('applications','legal')}}">Legal Application</a>
        <a class="dropdown-item" href="{{route('applications','dd')}}">DD Application</a>
    </div>
</div>

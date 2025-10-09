@if(isset($request->student_id))
    <a href="{{$row->document_type == 'file' ? get_file_from_s3('general_documents/' . $row->attachment_type_id.'/'.$row->file_name) : $row->file_name}}" target="_blank" class="btn btn-sm btn-info btn-icon waves-effect waves-light {{isset($request->general_document_id) && $request->general_document_id == $row->id ? 'disabled' : ''}}">
        <i class="mdi mdi-eye"></i>
    </a>
    <a href="{{route('students.edit',$request->student_id).'?tab=student_image&general_document_id='.$row->id}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light {{isset($request->general_document_id) && $request->general_document_id == $row->id ? 'disabled' : ''}}">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
    <a href="{{route('general-document.destroy',$row->id)}}" data-table="generalDocumentDatatable" class="btn btn-sm btn-danger btn-icon waves-effect delete-record {{isset($request->general_document_id) && $request->general_document_id == $row->id ? 'disabled' : ''}}">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@else
    @permission('view-attachments')
    <a href="{{$row->document_type == 'file' ? get_file_from_s3('general_documents/' . $row->attachment_type_id.'/'.$row->file_name) : $row->file_name}}" target="_blank" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
        <i class="mdi mdi-eye"></i>
    </a>
    @endpermission
    @permission('edit-attachments')
    <a href="{{route('general-document.edit',$row->id)}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
    @endpermission
    @permission('delete-attachments')
    <a href="{{route('general-document.destroy',$row->id)}}" data-table="generalDocumentDatatable" class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
    @endpermission
@endif

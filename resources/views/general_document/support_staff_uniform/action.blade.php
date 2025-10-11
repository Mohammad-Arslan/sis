<a href="{{$row->document_type == 'file' ? get_file_from_s3('general_documents/' . $row->attachment_type_id.'/'.$row->file_name) : $row->file_name}}" target="_blank" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
    <i class="mdi mdi-eye"></i>
</a>

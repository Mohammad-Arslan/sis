@foreach($Files as $File)
    <tr>
        <td class="col-md-10"><a href="{{ get_file_from_s3('images/'.$File['file_name']) }}" class="avatar-group-item" target="_blank">{{$File['file_name']}}</a></td>
        <td class="col-md-2"><a href="{{ route('delete-homework-detail-attachment', $File['id']) }}" data-table="homework-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-attachment"  title="Delete">
            <i class="ri-delete-bin-5-line"></i>
        </a></td>
    </tr>
@endforeach

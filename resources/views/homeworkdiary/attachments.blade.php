<table class="table table-borderless align-middle">
@foreach($Files as $File)
    <tr>
        <td><a href="{{ get_file_from_s3('images/'.$File['file_name']) }}" class="avatar-group-item" target="_blank">{{$File['file_name']}}</a></td>
    </tr>
@endforeach
</table>

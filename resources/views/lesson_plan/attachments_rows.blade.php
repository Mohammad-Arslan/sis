@foreach($lessonPlan['attachments'] as $attachment)
    <tr>
        <td>{{$attachment->file_name}}</td>
        <td>
            <a href="{{ get_file_from_s3('images/'.$attachment['file_name']) }}" class="avatar-group-item" target="_blank">View</a>
        </td>
    </tr>
@endforeach

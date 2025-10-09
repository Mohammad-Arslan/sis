@forelse($franchise_application_attachments as $attachment)
<tr>
    <td>
        <a href="{{ get_file_from_s3('franchise_application_attachments/'.$attachment['franchise_application_id'].'/'.$attachment->file_name) }}" class="avatar-group-item" target="_blank">{{$attachment->file_name}}</a>
    </td>
    <td>{{$attachment['attachment_type']['name']}}</td>
    @if(auth()->user())
        <td>{{ !empty($attachment['user']) ? $attachment['user']['name'] : ($attachment['franchise_application']['appl_name'] . ' ' . $attachment['franchise_application']['appl_last_name'] . ' (Applicant)') }}</td>
    @endif
    <td>{{\Carbon\Carbon::parse($attachment['uploaded_date'])->format('d-m-Y')}}</td>
    <td>{{$attachment['details']}}</td>
    <td class="text-center">
        @if(auth()->user() && auth()->user()->hasPermission('delete-franchapp-upload-doc') || !auth()->user())
            <a href="javascript:void(0);" class="link-danger fs-15 remove_attachment" data-table="schoolBuildingTable" data-id="{{$attachment['id']}}" data-route="{{route('remove-franchise-application-docs',$attachment['id'])}}" data-franchise_application_id="{{$attachment['franchise_application_id']}}"><i class="ri-delete-bin-line"></i></a>
        @else
            N/A
        @endif
    </td>
</tr>
@empty
    <tr class="text-center">
        <td colspan="5">No Record Found</td>
    </tr>
@endforelse

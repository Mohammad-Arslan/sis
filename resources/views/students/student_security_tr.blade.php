<div class="d-flex gap-2 align-items-center">
    <div class="flex-grow-1">
        @if(isset($row->security_deposit) && $row->security_amount != null)
            <a href="{{ route('download-security-challan',$row['id']) }}" target="_blank"> {{ $row['security_amount'] }} </a>
        @elseif(!isset($row->security_deposit) && $row->security_amount == null)
        <a href="javascript:void(0);"> NULL </a>
        @else
        <a href="{{ route('download-security-challan',$row['id']) }}" target="_blank"> {{ get_student_security($row['branch']['id'],$row['admission_year_id']) }} </a>
        @endif
    </div>
</div>

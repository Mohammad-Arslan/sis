<a href="{{route('student-withdrawal.create',['request'=>$row->id])}}" title="Approve" data-guardian-info-update-id="{{$row->id}}" class="btn btn-sm btn-success btn-icon waves-effect waves-light button-update-guardian-info">
    <i class="mdi mdi-check"></i>
</a>
<a href="{{route('students-withdrawal-requests-delete',['id' => $row->id])}}" title="Delete Request" data-table="withdrawal-requests-data-table" class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>

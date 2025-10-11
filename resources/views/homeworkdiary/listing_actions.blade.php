
{{-- @permission('edit-homework-diary') --}}
        <a href="{{ route('homeWorkDiary.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Edit">
            <i class="mdi mdi-lead-pencil"></i></a>
{{-- @endpermission --}}
{{-- @permission('add-homework-diary') --}}
    <a data-url="{{route('add-homework-detail', $row->id)}}" data-target="#diaryAddModal" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" title="Add Home Work"><i class="mdi mdi-home-account"></i></a>
    @if(inUse('HomeWorkDiary',$row->id))
        <a data-url="{{route('view-homework-detail', $row->id)}}" data-target="#diaryViewModal" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" title="View Home Work"><i class="mdi mdi-eye"></i></a>
    @endif
{{-- @endpermission --}}

{{-- @permission('delete-homework-diary') --}}
    @if(!inUse('HomeWorkDiary',$row->id))
        <a href="{{ route('homeWorkDiary.destroy', $row->id) }}" data-table="homework-list-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record"  title="Delete">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endif
{{-- @endpermission --}}

@if (!auth()->user()->hasPermission('edit-homework-diary') &&
    !auth()->user()->hasPermission('delete-homework-diary'))
    <span>N/A</span>
@endif

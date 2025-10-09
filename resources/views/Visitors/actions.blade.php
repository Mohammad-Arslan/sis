@if(isSuperAdmin() || $row->approved_by == Auth::user()->id)
        <a data-url="{{route('edit-visit-status', $row->id)}}" data-target="#visitStatusModal" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" title="Approve Request">
            <i class="ri-article-line"></i></a>
@endif

@permission('edit-visit')
    @if(isSuperAdmin() && $row->approval_status == 'pending')
        <a href="{{ route('visitDetail.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Edit">
            <i class="mdi mdi-lead-pencil"></i></a>
    @elseif($row->user_id == Auth::user()->id  && $row->approval_status == 'pending')
        <a href="{{ route('visitDetail.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Edit">
            <i class="mdi mdi-lead-pencil"></i></a>
    @endif
@endpermission
@permission('delete-visit')
    @if($row->approval_status == 'pending')
        <a href="{{ route('visitDetail.destroy', $row->id) }}" data-table="visits-data-table"
            class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record"  title="Delete">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endif
@endpermission

@if (!auth()->user()->hasPermission('edit-visit') &&
    !auth()->user()->hasPermission('delete-visit'))
    <span>N/A</span>
@endif

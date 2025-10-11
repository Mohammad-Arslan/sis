<a href="{{ route('curriculum.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i></a>
<a href="{{ route('curriculum.destroy', $row->id) }}" data-table="town-data-table"
    class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
{{--@if (!auth()->user()->hasPermission('edit-curriculum') &&
    !auth()->user()->hasPermission('delete-curriculum'))
    <span>N/A</span>
@endif--}}

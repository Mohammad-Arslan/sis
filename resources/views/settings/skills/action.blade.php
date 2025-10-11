@permission('edit-skill')
    <a href="{{ route('skill.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('delete-skill')
    <a href="{{ route('skill.destroy', $row->id) }}" data-table="skill-datatable"
        class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

{{--  && !auth()->user()->hasPermission('delete-skill') --}}
@if (!auth()->user()->hasPermission('edit-skill'))
    <span>N/A</span>
@endif

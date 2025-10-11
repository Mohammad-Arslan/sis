<a href="{{ $row->status == 'PENDING' ?  route('promotion-requests.edit', $row->id) : 'javascript:void(0)' }}" title="Edit Student Promotion Request"
    class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="ri-pencil-fill"></i>
</a>

<button data-url="{{ route('promotion-requests.list-promotion-request', $row->id) }}" type="button"
    title="Show Students list" class="btn btn-sm btn-info btn-icon waves-effect waves-light show_promotion_requests">
    <i class=" ri-folder-user-fill"></i>
</button>

<button type="button" class="btn btn-sm btn-success show-modal"
    data-url="{{ route('student-promotion-approval-form') . '?id=' . $row->id . '&type=modal' }}"
    {{ $row->status != 'PENDING' ? 'disabled' : '' }} class="btn btn-sm btn-danger btn-icon waves-effect"
    data-target="#requestApprovalFormModal" title="Approve Request">
    <i class="mdi mdi-check"></i>
</button>

<button type="button" class="btn btn-sm btn-warning show-modal" {{ $row->status != 'PENDING' ? 'disabled' : '' }}
    data-url="{{ route('student-promotion-rejection-form') . '?id=' . $row->id . '&type=modal' }}"
    data-target="#requestRejectFormModal" title="Cancel Request">
    <i class="mdi mdi-close-thick"></i>
</button>

<a href="{{ $row->status == 'PENDING' ? route('promotion-requests.destroy', $row->id) : 'javascript:void(0)' }}"
    data-table="promotion-request-list" {{ $row->status != 'PENDING' ? 'disabled' : '' }}
    class="btn btn-sm btn-danger btn-icon waves-effect {{ $row->status == 'PENDING' ? 'delete-record' : '' }}"
    title="Delete">
    <i class="ri-delete-bin-5-line"></i>
</a>

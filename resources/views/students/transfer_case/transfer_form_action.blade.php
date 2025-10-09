@permission('edit-student-transfer-case')
    <a href="{{ $row->status == 'PENDING' ? route('student-transfer-case.edit', $row->id) : 'javascript:void(0)' }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light" title="Edit">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
@permission('print-student-transfer-case')
    @if ($row->status == 'APPROVED')
        <button type="button" class="btn btn-sm btn-info show-modal"
            data-url="{{ route('students-transfer-form-create') . '?id=' . $row->id . '&type=modal' }}"
            class="btn btn-sm btn-danger btn-icon waves-effect" data-target="#transferFormModal" title="Transfer Letter">
            <i class="ri-printer-fill"></i>
        </button>
    @endif
@endpermission

@permission('approve-student-transfer-case')
    <button type="button" class="btn btn-sm btn-success show-modal"
        data-url="{{ route('student-transfer-approval-form') . '?id=' . $row->id . '&type=modal' }}"
        {{ $row->status != 'PENDING' ? 'disabled' : '' }} class="btn btn-sm btn-danger btn-icon waves-effect"
        data-target="#transferApprovalFormModal" title="Approve Request">
        <i class="mdi mdi-check"></i>
    </button>
@endpermission

@permission('cancel-student-transfer-case')
    <button type="button" class="btn btn-sm btn-warning show-modal" {{ $row->status != 'PENDING' ? 'disabled' : '' }}
        data-url="{{ route('student-transfer-cancellation-form') . '?id=' . $row->id . '&type=modal' }}"
        data-target="#transferCancellationFormModal" title="Cancel Request">
        <i class="mdi mdi-close-thick"></i>
    </button>
@endpermission

@permission('delete-student-transfer-case')
    @if ($row->status == 'PENDING')
        <a id="delete-record" href="{{ route('student-transfer-case.destroy', $row->id) }}"
            class="btn btn-sm btn-danger btn-icon waves-effect delete-record" data-id="{{ $row->id }}"
            data-table="transfer-form-list" title="Delete">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endif
@endpermission

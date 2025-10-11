@permission('edit-withdrawal')
    <a href="{{ route('student-withdrawal.edit', $row->id) . '?tab=guardian' }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light {{ isset($request->guardian_id) && $request->guardian_id == $row->id ? 'disabled' : '' }} {{ isset($row->approved_by) ? 'disabled' : '' }}"
        title="Edit Withdrawal">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission
<button type="button" class="btn btn-sm btn-info show-modal {{ isset($row->approved_by) ? 'disabled' : '' }}"
    data-url="{{ route('students-withdrawal-form-create') . '?id=' . $row->id . '&type=modal' }}"
    class="btn btn-sm btn-danger btn-icon waves-effect" data-target="#withdrawalFormModal" title="Print Withdrawal Form">
    <i class="ri-printer-fill"></i>
</button>
@permission('approve-withdrawal')
<button type="button" class="btn btn-sm btn-success show-modal {{ isset($row->approved_by) ? 'disabled' : '' }}"
    data-url="{{ route('student-withdrawal-approval-form') . '?id=' . $row->id . '&type=modal' }}"
    class="btn btn-sm btn-danger btn-icon waves-effect" data-target="#withdrawalApprovalFormModal"
    title="Approve Withdrawal">
    <i class="mdi mdi-check"></i>
</button>
@endpermission
@permission('cancel-withdrawal')
<button type="button" class="btn btn-sm btn-warning show-modal"
    data-url="{{ route('student-withdrawal-cancellation-form') . '?id=' . $row->id . '&type=modal' }}"
    data-target="#withdrawalCancellationFormModal" title="Cancel Withdrawal">
    <i class="mdi mdi-close-thick"></i>
</button>
@endpermission
<button type="button" class="btn btn-sm btn-info show-modal {{ isset($row->approved_by) ? 'disabled' : '' }}"
    data-url="{{ route('student-withdrawal-refund-form') . '?id=' . $row->id . '&type=modal' }}"
    data-target="#withdrawalRefundFormModal" title="Refund Withdrawal">
    <i class="mdi mdi-cash"></i>
</button>
@permission('delete-withdrawal')
    <a href="{{ route('student-withdrawal.destroy', $row->id) }}" data-table="withdrawal-form-list"
        class="btn btn-sm btn-danger btn-icon waves-effect delete-record {{ isset($request->guardian_id) && $request->guardian_id == $row->id ? 'disabled' : '' }}"
        title="Delete Withdrawal">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

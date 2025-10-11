@permission('edit-student')
    <a href="{{ route('students.edit', $row->id) . '?tab=personal' }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endpermission

@permission('edit-student')
    <a href="{{ route('students.edit', $row->id) . '?tab=invoice' }}"
        class="btn btn-sm btn-secondary btn-icon waves-effect waves-light">
        <i class="ri-file-info-fill"></i>
    </a>
@endpermission

    @if ($row->status == 'left')
        <button type="button" class="btn btn-sm btn-info show-modal"
            data-url="{{ route('students-leaving-certificate-create') . '?id=' . $row->id . '&type=modal' }}"
            class="btn btn-sm btn-danger btn-icon waves-effect" data-target="#LeavingCertificateFormModal" title="School Leaving Certificate">
            <i class="ri-printer-fill"></i>
        </button>
    @endif

@permission('delete-student')
    <a href="{{ route('students.destroy', $row->id) }}" data-table="example"
        class="btn btn-sm btn-danger btn-icon waves-effect delete-record">
        <i class="ri-delete-bin-5-line"></i>
    </a>
@endpermission

<button type="button" class="btn btn-sm btn-info show-modal"
    data-url="{{ route('students.create_registration_slip', [$row->id, 'modal']) }}" data-target="#registrationSlipModal"
    {{ isset($row['guardian']) && isset($row['student_address']) && isset($row['active_class']) ? '' : 'disabled' }}>
    Registration Slip
</button>

@if (!auth()->user()->hasPermission('edit-student') &&
    !auth()->user()->hasPermission('delete-student'))
    <span>N/A</span>
@endif

{{-- <button type="button" class="btn btn-sm btn-primary btn-icon waves-effect waves-light show-modal"
    data-url="{{ route('student-invoices.show', $row['id']) }}" data-target="#invoiceDetailModal"><i
        class="ri-eye-fill"></i></button> --}}
@if ($row['bank_payment_status'] == 'unpaid')
    <a href="{{ route('student-invoices.edit', $row['id']) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
    @permission('update-payment-status')
        <button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal"
            data-url="{{ route('payment-status-modal', $row['id']) }}" data-target="#changeInvoiceStatusModal"><i
                class="ri-flag-fill"></i></button>
    @endpermission
@else
    @role('super_admin')
        <a href="{{ route('student-invoices.edit', $row['id']) }}"
        class="btn btn-sm btn-success btn-icon waves-effect waves-light">
        <i class="mdi mdi-lead-pencil"></i>
        </a>

    @endrole

    <a href="{{ route('student-invoices.edit', $row['id']) }}"
        class="btn btn-sm btn-success bg-soft-primary btn-icon waves-effect waves-light disabled border-0">
        <i class="mdi mdi-lead-pencil"></i>
    </a>
@endif

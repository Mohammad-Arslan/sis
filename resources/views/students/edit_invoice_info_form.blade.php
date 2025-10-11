@extends('layouts.master')
@section('content')
    <div id='feePackageAlert' role="alert"></div>

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Update Invoice</h4>
            <div class="flex-shrink-0">

            </div>
        </div>
        <div class="card-body">
            <form class="row g-3 needs-validation mt-3" method="POST"
                action="{{ route('student-invoices.update', $student_invoice->id) }}" novalidate>
                @method('PUT')
                @csrf
                <input type="hidden" name="invoice_type_id" id="invoiceTypeId" value="1" />
                <input type="hidden" name="student_id" id="studentID" value="{{ $student_invoice->student->id }}" />
                <input type="hidden" name="student_fee_package_id" id="studentFeePackageId"
                    value="{{ $student_invoice->student_fee_package->id }}" />

                <div class="row">
                    <div class="col-md-4 col-sm-12 mt-2">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('issue_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ $student_invoice->issue_date }}" name="issue_date" id="issueDate" readonly>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="issueDate" class="form-label">Issue Date</label>

                            <div class="invalid-tooltip">
                                @if ($errors->has('issue_date'))
                                    {{ $errors->first('issue_date') }}
                                @else
                                    Issue Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-2">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('due_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ $student_invoice->due_date }}" name="due_date" id="dueDate">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="dueDate" class="form-label">Due Date</label>

                            <div class="invalid-tooltip">
                                @if ($errors->has('due_date'))
                                    {{ $errors->first('due_date') }}
                                @else
                                    Due Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-2">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('validity_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ $student_invoice->validity_date }}" name="validity_date" id="validDate">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="validDate" class="form-label">Validity Date</label>

                            <div class="invalid-tooltip">
                                @if ($errors->has('validity_date'))
                                    {{ $errors->first('validity_date') }}
                                @else
                                    Valid Date is required!
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border mt-3 mb-2 border-dashed"></div>
                <div class="card-body px-4 pt-2">
                    <div class="row g-3">
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold">Academic Year</p>
                            <h5 class="fs-14 mb-0">{{ $student_invoice->student_fee_package->academic_year->title }}</h5>
                        </div>
                        <div class="col-lg-2 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold">Class</p>
                            <h5 class="fs-14 mb-0">{{ $student_invoice->student_fee_package->com_class->class_name }}</h5>
                        </div>
                        <div class="col-lg-2 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold">Section</p>
                            <h5 class="fs-14 mb-0">{{ $student_invoice->student_fee_package->section->section_name }}</h5>
                        </div>
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold">Fee Package</p>
                            <h5 class="fs-14 mb-0">{{ $student_invoice->student_fee_package->fee_package->package_name }}
                            </h5>
                        </div>
                        @if ($student_invoice->student_fee_package->fee_concession)
                            <div class="col-lg-2 col-6">
                                <p class="mb-2 text-muted fw-semibold text-uppercase">Fee Concession</p>
                                <h5 class="mb-0 fs-14">
                                    {{ $student_invoice->student_fee_package->fee_concession->fee_concession_type->name }}
                                    ({{ $student_invoice->student_fee_package->fee_concession->concession_percentage }}%)
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-2 table-responsive">
                    @if ($errors->has('fee_charges_items'))
                        <div class="alert alert-danger" role="alert">
                            No fee charges selected.
                        </div>
                    @endif

                    <table class="table mb-0 text-center align-middle table-borderless table-nowrap">
                        <thead>
                            <tr class="table-active">
                                <th scope="col" style="width: 50px;">#</th>
                                <th scope="col" class="text-start">Charges Detail</th>
                                <th scope="col" class="text-start">Description</th>
                                <th scope="col" class="text-end">Actual Amount</th>
                                <th scope="col" class="text-end">Discount Detail</th>
                                <th scope="col" class="text-end">Final Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Package Charges --}}
                            @if ($student_package_charges->count())
                                <tr class="table-light">
                                    <td colspan="6" class="text-start fw-bold text-uppercase">Package Charges</td>
                                </tr>
                                @foreach ($student_package_charges as $student_package_charge)
                                    @php
                                        $chargeId = $student_package_charge['fee_charges']['id'];
                                        $chargeType =
                                            $student_package_charge['fee_charges']['fee_charges_type'] ?? null;
                                        $originalAmount = $student_package_charge['fee_charges']['amount'] ?? 0;
                                        $studentConcession =
                                            $student_package_charge['fee_charges']['student_concessions'][0] ?? null;
                                        $discount = $studentConcession['fee_concession']['concession_percentage'] ?? 0;
                                        $discountAmount = $discount > 0 ? ($originalAmount * $discount) / 100 : 0;
                                        $finalAmount = $originalAmount - $discountAmount;
                                        $isLocked =
                                            !isSuperAdmin() &&
                                            !isHeadOfficeEmp() &&
                                            $student_invoice->student_fee_package->fee_package->fee_package_type
                                                ->name == 'Admission' &&
                                            in_array($chargeType['abbreviation'] ?? '', ['TF', 'AF']);
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="fee_charges_items[]"
                                                value="{{ $chargeId }}" id="formCheck{{ $chargeId }}"
                                                {{ $student_package_charge['selected'] ? 'checked' : '' }}
                                                {{ $isLocked ? 'disabled' : '' }}>
                                        </td>
                                        <td class="text-start fw-medium">{{ $chargeType['name'] ?? 'N/A' }}</td>
                                        <td class="text-start">
                                            @if (!empty($chargeType['description']))
                                                <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ $chargeType['description'] }}"></i>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-end">Rs. {{ number_format($originalAmount, 2) }}</td>
                                        <td class="text-end">Rs. {{ number_format($discountAmount, 2) }}</td>
                                        <td class="text-end">Rs. {{ number_format($finalAmount, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Additional Charges --}}
                            @if ($additional_charges)
                                @php
                                    $unique_additional_charges = collect($additional_charges)->unique(
                                        fn($charge) => $charge['fee_charges_type_id'] ??
                                            ($charge['fee_charges_type']['name'] ?? null),
                                    );
                                @endphp
                                <tr class="table-light">
                                    <td colspan="6" class="text-start fw-bold text-uppercase">Optional Additional
                                        Charges</td>
                                </tr>
                                @foreach ($unique_additional_charges as $additional_charge)
                                    @php
                                        $chargeId = $additional_charge['id'];
                                        $chargeType = $additional_charge['fee_charges_type'] ?? null;
                                        $originalAmount = $additional_charge['amount'] ?? 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="fee_charges_items[]"
                                                value="{{ $chargeId }}" id="formCheck{{ $chargeId }}"
                                                {{ $additional_charge['selected'] ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-start fw-medium">{{ $chargeType['name'] ?? 'N/A' }}</td>
                                        <td class="text-start">
                                            @if (!empty($chargeType['description']))
                                                <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ $chargeType['description'] }}"></i>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-end">Rs. {{ number_format($originalAmount, 2) }}</td>
                                        <td class="text-end">Rs. 0.00</td>
                                        <td class="text-end">Rs. {{ number_format($originalAmount, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Summary Section --}}
                <div class="px-4 pt-3 card-body border-top">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-end">
                                <p class="mb-1 text-muted fw-semibold">Subtotal:</p>
                                <h5 class="mb-0 fs-16" id="subtotal-display">0.00</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-end">
                                <p class="mb-1 text-muted fw-semibold">Total Discount:</p>
                                <h5 class="mb-0 fs-16 text-success" id="discount-display">0.00</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-end">
                                <p class="mb-1 text-muted fw-semibold">Total Payable:</p>
                                <h5 class="mb-0 fs-18 fw-bold text-primary" id="payable-display">0.00</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-end">
                                <button class="btn btn-primary" type="submit" id="submit-btn">Update Invoice</button>
                                <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Unpaid/Adjusted Invoices Section (unchanged) --}}
                @role('super_admin|network_associate')
                    <div class="card-header align-items-center d-flex">
                        <h3 class="card-title mb-0 flex-grow-1">Unpaid Invoices List</h3>
                    </div>
                    <table id="invoices-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Invoice no.</th>
                                <th>Month</th>
                                <th>Due Date</th>
                                <th>Payment Status</th>
                                <th>Remarks <span class="text-danger">*</span></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            @foreach ($unpaid_invoice as $unpaid_invoice)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="unpaid_invoice_row[]"
                                            id="unpaid_invoice_row_{{ $unpaid_invoice['id'] }}"
                                            value="{{ $unpaid_invoice['id'] }}">
                                    </td>
                                    <td>{{ $unpaid_invoice['invoice_no'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($unpaid_invoice['fee_period']['from_date'])->format('M') }}
                                    </td>
                                    <td>{{ $unpaid_invoice['due_date'] }}</td>
                                    <td>{{ $unpaid_invoice['bank_payment_status'] }}</td>
                                    <td>
                                        <div class="form-label-group in-border">
                                            <input type="text"
                                                class="form-control @if ($errors->has('payment_remarks')) is-invalid @endif"
                                                id="payment_remarks" name="payment_remarks"
                                                placeholder="Please enter first name" value="{{ old('payment_remarks') }}"
                                                required>
                                            <label for="payment_remarks" class="form-label">Remarks</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tfoot>
                    </table>

                    <div class="card-header align-items-center d-flex">
                        <h3 class="card-title mb-0 flex-grow-1">Adjusted Invoices List</h3>
                    </div>
                    <table id="invoices-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Invoice no.</th>
                                <th>Month</th>
                                <th>Due Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            @foreach ($adjusted_invoice as $adjusted_invoice)
                                <tr>
                                    @if ($student_invoice['bank_payment_status'] == 'paid')
                                        <td>
                                            <input class="form-check-input" type="checkbox" checked disabled>
                                        </td>
                                    @else
                                        <td>
                                            <input class="form-check-input" type="checkbox" name="adjusted_invoice_row[]"
                                                id="adjusted_invoice_row_{{ $adjusted_invoice['id'] }}"
                                                value="{{ $adjusted_invoice['id'] }}">
                                        </td>
                                    @endif
                                    <td>{{ $adjusted_invoice['invoice_no'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($adjusted_invoice['fee_period']['from_date'])->format('M') }}
                                    </td>
                                    <td>{{ $adjusted_invoice['due_date'] }}</td>
                                    <td>{{ $adjusted_invoice['bank_payment_status'] }}</td>
                                </tr>
                            @endforeach
                        </tfoot>
                    </table>
                @endrole
                <input type="text" class="form-control" id="current_invoice_id" name="current_invoice_id"
                    value="{{ $student_invoice->id }}" hidden>
            </form>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            calculateTotals();
            document.querySelectorAll('input[name="fee_charges_items[]"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', calculateTotals);
            });
        });

        function calculateTotals() {
            let subtotal = 0;
            let totalDiscount = 0;
            let totalPayable = 0;
            const checkedBoxes = document.querySelectorAll('input[name="fee_charges_items[]"]:checked');
            checkedBoxes.forEach(function(checkbox) {
                const row = checkbox.closest('tr');
                const actualAmountCell = row.querySelector('td:nth-child(4)');
                const discountCell = row.querySelector('td:nth-child(5)');
                const finalAmountCell = row.querySelector('td:nth-child(6)');
                if (actualAmountCell && discountCell && finalAmountCell) {
                    const actualAmountText = actualAmountCell.textContent.trim();
                    const discountText = discountCell.textContent.trim();
                    const finalAmountText = finalAmountCell.textContent.trim();
                    const actualAmount = parseFloat(actualAmountText.replace('Rs. ', '').replace(',', '')) || 0;
                    const discountAmount = parseFloat(discountText.replace('Rs. ', '').replace(',', '')) || 0;
                    const finalAmount = parseFloat(finalAmountText.replace('Rs. ', '').replace(',', '')) || 0;
                    subtotal += actualAmount;
                    totalDiscount += discountAmount;
                    totalPayable += finalAmount;
                }
            });
            document.getElementById('subtotal-display').textContent = 'Rs ' + subtotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('discount-display').textContent = 'Rs ' + totalDiscount.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('payable-display').textContent = 'Rs ' + totalPayable.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            const submitBtn = document.getElementById('submit-btn');
            if (checkedBoxes.length === 0) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select Items First';
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Invoice';
            }
        }
    </script>
@endpush

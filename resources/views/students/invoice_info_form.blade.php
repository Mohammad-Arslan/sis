<form class="mt-3 row g-3 needs-validation" method="POST" action="{{ route('student-invoices.store') }}" novalidate>
    @csrf

    <input type="hidden" name="invoice_type" value="{{ $student_fee_package->fee_package->fee_package_type->name }}" />
    <input type="hidden" name="invoice_type_id" value="1" />
    <input type="hidden" name="student_id" value="{{ $student->id }}" />
    <input type="hidden" name="student_fee_package_id" value="{{ $student_fee_package->id }}" />

    @if (in_array($student_fee_package->fee_package->fee_package_type->name, ['Monthly', 'Admission']))
        @php
            $now = \Carbon\Carbon::now();
        @endphp
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="load-select form-select @if ($errors->has('fee_period_id')) is-invalid @endif"
                    id="feePackageId" name="fee_period_id" data-target="fee_period_dates"
                    data-url="{{ route('get-fee-period') }}" aria-label="Fee period select" required>
                    <option value="">Please select a fee package</option>
                    @foreach ($fee_periods as $fee_period)
                        <option value="{{ $fee_period->id }}"
                            @if (is_null($last_invoice_month)) {{-- {{ in_array($fee_period->id, $existing_fee_period_invoice) || (!Auth::user()->hasRole('super_admin') && \Carbon\Carbon::parse($fee_period->from_date)->format('m') != (int) $student->admission_wef->format('m')) ? 'disabled' : '' }} --}}
                        {{ in_array($fee_period->id, $existing_fee_period_invoice) || !Auth::user()->hasRole('super_admin') }}
                        {{ old('fee_period_id') == $fee_period->id ? 'selected' : '' }}>
                        {{ $fee_period->period_name }}
                        ({{ \Carbon\Carbon::parse($fee_period->from_date)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($fee_period->to_date)->format('d-m-Y') }})
                        @else
                        {{-- Changes --}}
                        {{-- <p> {{  \Carbon\Carbon::parse($fee_period->from_date)->format('m')  }} </p> --}}
                        {{ in_array($fee_period->id, $existing_fee_period_invoice) || !Auth::user()->hasRole('super_admin') }}
                        {{-- && \Carbon\Carbon::parse($fee_period->from_date)->format('m') == (int) $last_invoice_month ) ? 'disabled' : '' }} --}}
                        {{ old('fee_period_id') == $fee_period->id ? 'selected' : '' }}>
                        {{ $fee_period->period_name }}
                        ({{ \Carbon\Carbon::parse($fee_period->from_date)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($fee_period->to_date)->format('d-m-Y') }}) @endif
                            </option>
                    @endforeach
                </select>
                <label for="invoiceTypeId" class="form-label">Fee Period <span class="text-danger">*</span></label>
                <div class="invalid-tooltip">
                    @if ($errors->has('fee_period_id'))
                        {{ $errors->first('fee_period_id') }}
                    @else
                        Fee Period is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8 col-sm-12"></div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('issue_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                    value="{{ old('issue_date') }}" name="issue_date" id="loadIssueDate" required
                    style="pointer-events: none">
                <div class="text-white input-group-text bg-primary border-primary">
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

        <div class="col-md-4 col-sm-12">
            <div class="input-group form-label-group in-border">
                <input type="text"
                    class="form-control disable-min-date @if ($errors->has('due_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                    value="{{ old('due_date') }}" name="due_date" id="loadDueDate" required>
                <div class="text-white input-group-text bg-primary border-primary">
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

        <div class="col-md-4 col-sm-12">
            <div class="input-group form-label-group in-border">
                <input type="text"
                    class="form-control disable-min-date @if ($errors->has('validity_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                    value="{{ old('validity_date') }}" name="validity_date" id="loadValidDate" required>
                <div class="text-white input-group-text bg-primary border-primary">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label for="validDate" class="form-label">Valid Date</label>

                <div class="invalid-tooltip">
                    @if ($errors->has('validity_date'))
                        {{ $errors->first('validity_date') }}
                    @else
                        Valid Date is required!
                    @endif
                </div>
            </div>
        </div>

    @endif

    {{-- Student & Fee Info --}}
    <div class="px-4 pt-2 card-body">
        <div class="row g-3">
            <div class="col-lg-3 col-6">
                <p class="mb-2 text-muted fw-semibold text-uppercase">Academic Year</p>
                <h5 class="mb-0 fs-14">{{ $student_fee_package->academic_year->title }}</h5>
            </div>
            <div class="col-lg-2 col-6">
                <p class="mb-2 text-muted fw-semibold text-uppercase">Class</p>
                <h5 class="mb-0 fs-14">{{ $student_fee_package->com_class->class_name }}</h5>
            </div>
            <div class="col-lg-2 col-6">
                <p class="mb-2 text-muted fw-semibold text-uppercase">Section</p>
                <h5 class="mb-0 fs-14">{{ $student_fee_package->section->section_name }}</h5>
            </div>
            <div class="col-lg-3 col-6">
                <p class="mb-2 text-muted fw-semibold text-uppercase">Fee Package</p>
                <h5 class="mb-0 fs-14">{{ $student_fee_package->fee_package->package_name }}</h5>
            </div>
            @if ($student_fee_package->fee_concession)
                <div class="col-lg-2 col-6">
                    <p class="mb-2 text-muted fw-semibold text-uppercase">Fee Concession</p>
                    <h5 class="mb-0 fs-14">
                        {{ $student_fee_package->fee_concession->fee_concession_type->name }}
                        ({{ $student_fee_package->fee_concession->concession_percentage }}%)
                    </h5>
                </div>
            @endif
        </div>
    </div>

    {{-- Fee Charges Table --}}
    <div class="px-2 table-responsive">
        @if ($errors->has('fee_charges_items'))
            <div class="alert alert-danger">No fee charges selected.</div>
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
                    {{-- New Column --}}
                </tr>
            </thead>
            <tbody>
                {{-- Student Fee Package Charges --}}
                @if ($student_package_charges->count())
                    <tr class="table-light">
                        <td colspan="6" class="text-start fw-bold text-uppercase">Package Charges</td>
                    </tr>
                    @foreach ($student_package_charges as $student_package_charge)
                        @php
                            $chargeId = $student_package_charge->fee_charges->id;
                            $chargeType = $student_package_charge->fee_charges?->fee_charges_type;
                            $originalAmount = $student_package_charge->fee_charges->amount ?? 0;
            
                            $studentConcession = $student_package_charge->fee_charges->student_concessions->first();
                            $discount = $studentConcession?->fee_concession?->concession_percentage ?? 0;
            
                            $discountAmount = ($discount > 0) ? ($originalAmount * $discount) / 100 : 0;
                            $finalAmount = $originalAmount - $discountAmount;
            
                            $isLocked =
                                !isSuperAdmin() &&
                                !isHeadOfficeEmp() &&
                                $isAdmission &&
                                in_array($chargeType?->abbreviation, ['TF', 'AF']);
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input" name="fee_charges_items[]"
                                    value="{{ $chargeId }}" id="formCheck{{ $chargeId }}" checked
                                    {{ $isLocked ? 'disabled' : '' }}>
                            </td>
                            <td class="text-start fw-medium">{{ $chargeType?->name ?? 'N/A' }}</td>
                            <td class="text-start">
                                @if (!empty($chargeType?->description))
                                    <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $chargeType->description }}"></i>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-end">
                                Rs. {{ number_format($originalAmount, 2) }}
                            </td>
                            <td class="text-end">
                                Rs. {{ number_format($discountAmount, 2) }}
                            </td>
                            <td class="text-end">
                                Rs. {{ number_format($finalAmount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            
                {{-- Additional Charges --}}
                @if ($additional_charges->count())
                    @php
                        $unique_additional_charges = $additional_charges->unique(
                            fn($charge) => $charge->fee_charges_type_id ?? $charge->fee_charges_type?->name,
                        );
                    @endphp
            
                    <tr class="table-light">
                        <td colspan="6" class="text-start fw-bold text-uppercase">Optional Additional Charges</td>
                    </tr>
            
                    @foreach ($unique_additional_charges as $additional_charge)
                        @php
                            $chargeId = $additional_charge->id;
                            $chargeType = $additional_charge->fee_charges_type;
                            $originalAmount = $additional_charge->amount ?? 0;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input" name="fee_charges_items[]"
                                    value="{{ $chargeId }}" id="formCheck{{ $chargeId }}">
                            </td>
                            <td class="text-start fw-medium">{{ $chargeType?->name ?? 'N/A' }}</td>
                            <td class="text-start">
                                @if (!empty($chargeType?->description))
                                    <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $chargeType->description }}"></i>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-end">
                                Rs. {{ number_format($originalAmount, 2) }}
                            </td>
                            <td class="text-end">
                                Rs. 0.00
                            </td>
                            <td class="text-end">
                                Rs. {{ number_format($originalAmount, 2) }}
                            </td>
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
                    <button class="btn btn-primary" type="submit" id="submit-btn">Generate Invoice</button>
                    <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('footer_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialize calculations
            calculateTotals();

            // Add event listeners to checkboxes
            document.querySelectorAll('input[name="fee_charges_items[]"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', calculateTotals);
            });
        });

        function calculateTotals() {
            let subtotal = 0;
            let totalDiscount = 0;
            let totalPayable = 0;

            // Get all checked checkboxes
            const checkedBoxes = document.querySelectorAll('input[name="fee_charges_items[]"]:checked');

            checkedBoxes.forEach(function(checkbox) {
                const row = checkbox.closest('tr');
                const actualAmountCell = row.querySelector('td:nth-child(4)'); // Actual Amount column
                const discountCell = row.querySelector('td:nth-child(5)'); // Discount Detail column
                const finalAmountCell = row.querySelector('td:nth-child(6)'); // Final Amount column

                if (actualAmountCell && discountCell && finalAmountCell) {
                    // Extract amounts from the text (remove "Rs. " and parse)
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

            // Update display
            document.getElementById('subtotal-display').textContent = 'Rs ' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('discount-display').textContent = 'Rs ' + totalDiscount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('payable-display').textContent = 'Rs ' + totalPayable.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Disable submit button if no items selected
            const submitBtn = document.getElementById('submit-btn');
            if (checkedBoxes.length === 0) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select Items First';
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Generate Invoice';
            }
        }
    </script>
@endpush

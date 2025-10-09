<div id="invoiceDetailModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Invoice Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-sm-flex">
                                <div class="flex-grow-1">
                                    <img src="{{ asset('ucs-logo.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="100">
                                    <img src="{{ asset('ucs-logo.png') }}" class="card-logo card-logo-light" alt="logo light" height="100">
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 border-top border-top-dashed">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Student Details</h6>
                                    <h6><span class="text-muted fw-normal">Student ID:</span>
                                        {{ $student_invoice['student']['roll_no'] ?? $student_invoice['student']['registration_no'] ?? $student_invoice['student']['id'] ?? '-' }}</h6>
                                    <h6><span class="text-muted fw-normal">Name:</span>
                                        {{ $student_invoice['student']['first_name'] . ' ' . $student_invoice['student']['last_name'] }}</h6>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Package Details</h6>
                                    <h6><span class="text-muted fw-normal">Academic Year:</span> {{ $student_invoice->student_fee_package->academic_year->title }}</h6>
                                    <h6><span class="text-muted fw-normal">Class:</span> {{ $student_invoice->student_fee_package->com_class->class_name }}</h6>
                                    <h6><span class="text-muted fw-normal">Section:</span> {{ $student_invoice->student_fee_package->section->section_name }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 border-top border-top-dashed">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Invoice No</p>
                                    <h5 class="fs-14 mb-0">{{ $student_invoice->invoice_no }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Issue Date</p>
                                    <h5 class="fs-14 mb-0">{{ optional($student_invoice['issue_date'])->format('d-m-Y') }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Due Date</p>
                                    <h5 class="fs-14 mb-0">{{ optional($student_invoice['due_date'])->format('d-m-Y') }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Validity Date</p>
                                    <h5 class="fs-14 mb-0">{{ optional($student_invoice['validity_date'])->format('d-m-Y') }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Amount</p>
                                    <h5 class="fs-14 mb-0">Rs. {{ number_format($total['total'] ?? 0) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payable After Due Date</p>
                                    <h5 class="fs-14 mb-0 text-danger">Rs. {{ number_format($total['after_due_date'] ?? 0) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                    @php $status = $student_invoice->bank_payment_status @endphp
                                    <span class="badge bg-{{ $status === 'paid' ? 'success' : ($status === 'unpaid' ? 'warning' : ($status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($status) }}</span>
                                </div>
                                @if (!empty($total['arrear_months']))
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Arrears Month(s)</p>
                                        <h5 class="fs-14 mb-0">{{ implode(', ', $total['arrear_months']) }}</h5>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th>#</th>
                                            <th class="text-start">Charges Detail</th>
                                            <th class="text-start">Description</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student_invoice->student_invoice_items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-start">
                                                    {{ $item->fee_charges->fee_charges_type->name }}
                                                    @if ($item->concession)
                                                        <i class="fas fa-info-circle text-primary ms-1" data-bs-toggle="tooltip" title="{{ $item->concession }}% Concession"></i>
                                                    @endif
                                                </td>
                                                <td class="text-start text-muted">{{ $item->fee_charges->fee_charges_type->description ?? '-' }}</td>
                                                <td class="text-end">
                                                    Rs. {{ number_format($item->debit ?? $item->credit ?? 0) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="border-top border-top-dashed">
                                            <td colspan="3" class="text-end fw-bold">Sub Total</td>
                                            <td class="text-end">Rs. {{ number_format($total['sub_total'] ?? 0) }}</td>
                                        </tr>
                                        @if (!empty($total['concession_discount']))
                                            <tr>
                                                <td colspan="3" class="text-end">Concession</td>
                                                <td class="text-end">Rs. {{ number_format($total['concession_discount']) }}</td>
                                            </tr>
                                        @endif
                                        @if (!empty($total['promo_discount']))
                                            <tr>
                                                <td colspan="3" class="text-end">Promo Discount</td>
                                                <td class="text-end">Rs. {{ number_format($total['promo_discount']) }}</td>
                                            </tr>
                                        @endif
                                        @if (!empty($total['arrears']))
                                            <tr>
                                                <td colspan="3" class="text-end">Arrears</td>
                                                <td class="text-end">Rs. {{ number_format($total['arrears']) }}</td>
                                            </tr>
                                        @endif
                                        @if (!empty($total['fine_applied']) && $student_invoice->due_date && now()->gt($student_invoice->due_date))
                                            <tr>
                                                <td colspan="3" class="text-end text-danger">Fine</td>
                                                <td class="text-end text-danger">Rs. {{ number_format($total['fine_applied']) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="border-top border-top-dashed">
                                            <td colspan="3" class="text-end fw-bold">Total Payable</td>
                                            <td class="text-end fw-bold">
                                                Rs. {{ number_format(($student_invoice->due_date && now()->gt($student_invoice->due_date)) ? $total['after_due_date'] : $total['total']) }}
                                            </td>
                                        </tr>
                                        @php
                                            $totalPaidIncludingAdvance = $total['paid_amount'] + ($total['advance_payment'] ?? 0);
                                            $payableAmount = ($student_invoice->due_date && now()->gt($student_invoice->due_date)) ? $total['after_due_date'] : $total['total'];
                                            
                                            // If arrears are already included in total payable, don't subtract them again
                                            $finalBalance = $totalPaidIncludingAdvance - $payableAmount;
                                        @endphp
                                        @if ($total['paid_amount'] > 0)
                                            <tr class="border-top border-top-dashed">
                                                <td colspan="3" class="text-end fw-bold text-success">Paid Amount</td>
                                                <td class="text-end fw-bold text-success">Rs. {{ number_format($total['paid_amount']) }}</td>
                                            </tr>
                                        @endif
                                        @if (!empty($total['advance_payment']) && $total['advance_payment'] > 0)
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold text-info">Advance Applied</td>
                                                <td class="text-end fw-bold text-info">Rs. {{ number_format($total['advance_payment']) }}</td>
                                            </tr>
                                        @endif
                                        @if ($totalPaidIncludingAdvance > 0)
                                            <tr class="border-top border-top-dashed">
                                                <td colspan="3" class="text-end fw-bold text-primary">Total Paid (Including Advance)</td>
                                                <td class="text-end fw-bold text-primary">Rs. {{ number_format($totalPaidIncludingAdvance) }}</td>
                                            </tr>
                                            @if ($finalBalance != 0)
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold {{ $finalBalance > 0 ? 'text-info' : 'text-danger' }}">
                                                        {{ $finalBalance > 0 ? 'Overpaid Amount' : 'Balance Due' }}
                                                    </td>
                                                    <td class="text-end fw-bold {{ $finalBalance > 0 ? 'text-info' : 'text-danger' }}">
                                                        Rs. {{ number_format(abs($finalBalance)) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Arrears Details Section --}}
                            @if (!empty($total['arrears_breakdown']))
                                <div class="mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Arrears Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>From Month</th>
                                                    <th class="text-end">Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($total['arrears_breakdown'] as $index => $arrear)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $arrear['from_month_label'] }}</td>
                                                        <td class="text-end">Rs. {{ number_format($arrear['amount']) }}</td>
                                                        <td>
                                                            @if ($arrear['cleared_date'])
                                                                <span class="badge bg-success">Cleared</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Payment Details Section --}}
                            @if ($total['paid_amount'] > 0 || (!empty($total['advance_payment']) && $total['advance_payment'] > 0))
                                <div class="mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Payment Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Date</th>
                                                    <th class="text-end">Amount</th>
                                                    <th>Method</th>
                                                    <th>Reference</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $payment_index = 1; @endphp
                                                @foreach ($student_invoice->payments as $payment)
                                                    <tr>
                                                        <td>{{ $payment_index++ }}</td>
                                                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-' }}</td>
                                                        <td class="text-end">Rs. {{ number_format($payment->amount) }}</td>
                                                        <td>{{ ucfirst($payment->method ?? 'N/A') }}</td>
                                                        <td>{{ $payment->reference ?? '-' }}</td>
                                                        <td>
                                                            @if ($payment->method === 'advance')
                                                                <span class="badge bg-info">Advance Applied</span>
                                                            @else
                                                                <span class="badge bg-success">Direct Payment</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if (!empty($total['advance_breakdown']))
                                                    @foreach ($total['advance_breakdown'] as $advance)
                                                        <tr class="table-info">
                                                            <td>{{ $payment_index++ }}</td>
                                                            <td>{{ $advance['carried_date'] ? \Carbon\Carbon::parse($advance['carried_date'])->format('d-m-Y') : '-' }}</td>
                                                            <td class="text-end">Rs. {{ number_format($advance['amount']) }}</td>
                                                            <td>Advance</td>
                                                            <td>From {{ $advance['from_month_label'] }}</td>
                                                            <td><span class="badge bg-info">Advance Applied</span></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if (!empty($total['fine_applied']) && $student_invoice->due_date && now()->gt($student_invoice->due_date))
                                                    <tr class="table-warning">
                                                        <td>{{ $payment_index++ }}</td>
                                                        <td>-</td>
                                                        <td class="text-end text-danger">Rs. {{ number_format($total['fine_applied']) }}</td>
                                                        <td>Fine</td>
                                                        <td>Due Date Penalty</td>
                                                        <td><span class="badge bg-warning">Fine Applied</span></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Advance Payment Details Section --}}
                            @if (!empty($total['advance_breakdown']))
                                <div class="mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Advance Payment Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>From Month</th>
                                                    <th class="text-end">Amount</th>
                                                    <th>Carried Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($total['advance_breakdown'] as $index => $advance)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $advance['from_month_label'] }}</td>
                                                        <td class="text-end">Rs. {{ number_format($advance['amount']) }}</td>
                                                        <td>{{ $advance['carried_date'] ? \Carbon\Carbon::parse($advance['carried_date'])->format('d-m-Y') : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <p class="mb-0 fw-semibold">NOTE:</p>
                                    <p class="mb-0">All challans are to be paid within the due date.</p>
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <a href="{{ route('download-challan', $student_invoice->id) }}" target="_blank" class="btn btn-success">
                                    <i class="ri-printer-line align-bottom me-1"></i> Generate Challan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
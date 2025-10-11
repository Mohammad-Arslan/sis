@extends('layouts.master')
@section('content')
<div class="container-fluid py-4" style="background: #f8f9fb; min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('payrolls.create') }}" class="btn btn-outline-dark btn-sm me-3">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                        <div>
                            <h5 class="mb-0">Payroll Preview</h5>
                            <small class="text-muted">Review payroll details before processing</small>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ $company_name ?? 'Company Name' }}</h4>
                            <div class="mb-1">Salary Slip For {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                {{ $year }}</div>
                        </div>
                        
                        @if(!$allowances_applicable)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="ri-information-line me-2"></i>
                            <strong>Probation Period:</strong> Employee is currently on probation (ends: {{ $probation_end_date ? \Carbon\Carbon::parse($probation_end_date)->format('d M Y') : 'Not Set' }}). 
                            Permanent allowances (House Rent, Medical, Transport, etc.) are not applicable during probation. Only Basic Salary + Temporary allowances apply.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        
                        @if(isset($leave_debug_info))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ri-bug-line me-2"></i>
                            <strong>DEBUG - Leave Deduction Information:</strong>
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Leave Quota Info:</strong><br>
                                            Available Leave Types: {{ implode(', ', $leave_debug_info['all_leave_types'] ?? []) }}<br>
                                            Casual Leave Type: {{ $leave_debug_info['casual_leave_type_name'] ?? 'Not Found' }}<br>
                                            Leave Quota Found: {{ $leave_debug_info['leave_quota_found'] ?? 'No' }}<br>
                                            Leave Quota Allowed: {{ $leave_debug_info['leave_quota_allowed'] ?? '0' }}<br>
                                            Leave Quota Acquired (Used): {{ $leave_debug_info['leave_quota_acquired'] ?? '0' }}<br>
                                            Leave Quota Available: {{ $leave_debug_info['leave_quota_available'] ?? '0' }}<br>
                                            Calculation: {{ $leave_debug_info['calculation'] ?? 'Not calculated' }}<br>
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Late Calculation:</strong><br>
                                            Single Day Late: {{ $leave_debug_info['single_day_late_full_day'] ?? '0' }} days<br>
                                            Monthly Late Accum: {{ $leave_debug_info['monthly_late_accum'] ?? '0' }} minutes<br>
                                            Monthly Late Days: {{ $leave_debug_info['monthly_late_days'] ?? '0' }} days<br>
                                            Total Late Days: {{ $leave_debug_info['total_late_days'] ?? '0' }} days<br>
                                            Per Day Salary: {{ number_format($leave_debug_info['per_day_salary'] ?? 0, 2) }}<br>
                                        </small>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <small>
                                            <strong>Deduction Breakdown:</strong><br>
                                            Deduct from Leave: {{ $leave_debug_info['late_deduct_from_leave'] ?? '0' }} days<br>
                                            Deduct from Salary: {{ $leave_debug_info['late_deduct_from_salary'] ?? '0' }} days<br>
                                            Final Late Deduction: {{ number_format($leave_debug_info['final_late_deduction'] ?? 0, 2) }}<br>
                                        </small>
                                    </div>
                                </div>
                                @if(isset($leave_debug_info['all_employee_leave_quotas']))
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <small>
                                            <strong>ALL Employee Leave Quotas (Dashboard Data):</strong><br>
                                            @foreach($leave_debug_info['all_employee_leave_quotas'] as $quota)
                                                {{ $quota['leave_type_name'] }}: {{ $quota['allowed'] }} allowed, {{ $quota['balance'] }} acquired, {{ $quota['allowed'] - $quota['balance'] }} available (ID: {{ $quota['leave_type_id'] }})<br>
                                            @endforeach
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="mb-1"><strong>{{ $employee->employee_id }}</strong> -
                                    {{ $employee->user->name ?? $employee->preferred_name }}</div>
                                <div class="mb-1">Designation: <span
                                        class="fw-normal">{{ $employee->designation->designation_name ?? '-' }}</span></div>
                                <div class="mb-1">Department: <span
                                        class="fw-normal">{{ $employee->department->department_name ?? '-' }}</span></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td class="text-end">{{ number_format($basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Permanent Allowances</td>
                                        <td class="text-end {{ !$allowances_applicable ? 'text-muted' : '' }}">
                                            {{ isset($permanent_allowances) && count($permanent_allowances) > 0 ? number_format(collect($permanent_allowances)->sum('amount'), 2) : '0.00' }}
                                            @if(!$allowances_applicable)
                                                <small class="text-muted">(Probation Period)</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @if(isset($temporary_allowances_total) && $temporary_allowances_total > 0)
                                    <tr>
                                        <td>Temporary Allowances <span class="badge bg-success text-white">Non-Taxable</span></td>
                                        <td class="text-end">{{ number_format($temporary_allowances_total, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-light">
                                        <td class="fw-bold">Total Gross Salary</td>
                                        <td class="text-end fw-bold">{{ number_format($gross_salary, 2) }}</td>
                                    </tr>
                                    @if(isset($taxable_gross_salary))
                                    <tr class="border-top">
                                        <td><small class="text-muted">Taxable Amount (Basic + Permanent)</small></td>
                                        <td class="text-end"><small class="text-muted">{{ number_format($taxable_gross_salary, 2) }}</small></td>
                                    </tr>
                                    @endif
                                </table>
                                @if ($presents == 0)
                                    <small class="text-muted">Note: Salary is 0 as there are no present days</small>
                                @else
                                    <small class="text-muted">Note: Salary calculated based on {{ $presents }} present days out of 30 days (standard calculation)</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td>Provident Fund (Employee)</td>
                                        <td class="text-end">
                                            {{ $pf_applicable ? number_format($provident_fund, 2) : 'Not Applicable (Probation)' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Provident Fund (Employer)</td>
                                        <td class="text-end">
                                            {{ $pf_applicable ? number_format($pf_employer, 2) : 'Not Applicable (Probation)' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Income Tax <i class="ri-information-line text-info" title="Calculated on Basic + Permanent allowances only"></i></td>
                                        <td class="text-end">{{ number_format($income_tax, 2) }}</td>
                                    </tr>
                                </table>
                                <small class="text-muted">Note: Tax calculated on Basic + Permanent allowances. Temporary allowances are non-taxable.</small>
                            </div>
                        </div>
                        <!-- Attendance/Leave Summary Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="fw-bold mb-2">Attendance & Leave Summary</div>
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Present Days</th>
                                            <th>Absent Days</th>
                                            <th>Late Minutes</th>
                                            <th>Extra Hours</th>
                                            <th>Approved Leaves</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $presents ?? '-' }}</td>
                                            <td>{{ $absents ?? '-' }}</td>
                                            <td>{{ $late_minutes ?? '-' }}</td>
                                            <td>{{ $extra_hours ?? '-' }}</td>
                                            <td>{{ $approved_leaves ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="fw-bold mb-2">Allowance Detail(s)</div>
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr. #</th>
                                            <th>Allowance Type</th>
                                            <th>Type</th>
                                            <th>Allowance Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($allowances_applicable && isset($permanent_allowances) && is_array($permanent_allowances) && count($permanent_allowances) > 0)
                                            @foreach ($permanent_allowances as $i => $a)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $a['label'] }}</td>
                                                    <td><span class="badge bg-primary">Permanent</span></td>
                                                    <td>{{ number_format($a['amount'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @elseif(!$allowances_applicable)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <i class="ri-information-line me-1"></i>
                                                    No permanent allowances (Employee on probation)
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($temporary_allowances) && is_array($temporary_allowances))
                                            @php $offset = ($allowances_applicable && isset($permanent_allowances)) ? count($permanent_allowances) : 0; @endphp
                                            @foreach ($temporary_allowances as $i => $a)
                                                <tr>
                                                    <td>{{ $offset + $i + 1 }}</td>
                                                    <td>{{ $a['label'] }}</td>
                                                    <td><span class="badge bg-success">Temporary</span></td>
                                                    <td>{{ number_format($a['amount'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold mb-2">Other Deduction Detail(s)</div>
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr. #</th>
                                            <th>Deduction Type</th>
                                            <th>Deduction Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sr = 1; @endphp
                                        @foreach ($deductions as $d)
                                            @if ($d['amount'] > 0)
                                                <tr>
                                                    <td>{{ $sr++ }}</td>
                                                    <td>{{ $d['label'] }}</td>
                                                    <td>{{ number_format($d['amount'], 2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-2">Salary Calculation Breakdown</div>
                            <div class="border rounded p-2 bg-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Salary Calculation (30-Day Standard):</strong><br>
                                        Total Days in Month: 30 (Standard)<br>
                                        Present Days: {{ $presents }}<br>
                                        Per Day Salary: {{ number_format(($basic_salary + collect($allowances)->sum('amount')) / 30, 2) }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tax & PF Details:</strong><br>
                                        {{ $tax_slab_info }}<br>
                                        Provident Fund: Employee {{ number_format($pf_applicable ? $provident_fund : 0, 2) }} |
                                        Employer {{ number_format($pf_applicable ? $pf_employer : 0, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Net Salary:</strong> <span
                                        class="h4 fw-bold text-success">{{ number_format($net_salary, 2) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Take Home Salary:</strong> <span
                                        class="h4 fw-bold text-primary">{{ number_format($net_salary, 2) }}</span>
                                    <small class="text-muted d-block">After all deductions</small>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('payrolls.store') }}">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="basic_salary" value="{{ $basic_salary }}">
                            <input type="hidden" name="income_tax" value="{{ $income_tax }}">
                            <input type="hidden" name="late_deduction" value="{{ $late_deduction }}">
                            <input type="hidden" name="confirm" value="1">

                            <!-- Hidden fields for allowances -->
                            @if(is_array($allowances))
                                @foreach ($allowances as $index => $allowance)
                                    @if (!empty($allowance['label']) && !empty($allowance['amount']))
                                        <input type="hidden" name="allowances[{{ $index }}][label]"
                                            value="{{ $allowance['label'] }}">
                                        <input type="hidden" name="allowances[{{ $index }}][amount]"
                                            value="{{ $allowance['amount'] }}">
                                        <input type="hidden" name="allowances[{{ $index }}][is_permanent]"
                                            value="{{ $allowance['is_permanent'] ?? '0' }}">
                                    @endif
                                @endforeach
                            @endif

                            <!-- Hidden fields for deductions are now handled by the combined deductions array -->

                            <button type="submit" class="btn btn-success w-100 mb-2">Confirm & Process Payroll</button>
                        </form>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">Back to Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

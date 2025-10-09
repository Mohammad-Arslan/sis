@extends('layouts.master')

@section('title', 'Salary History - ' . $employee->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-history-line"></i> Salary History - {{ $employee->full_name }}
                        </h5>
                        <a href="{{ route('payrolls.salary.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Back to Salary Management
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Employee Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6><i class="ri-user-line"></i> Employee Information</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Name:</strong><br>
                                            {{ $employee->full_name }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Employee ID:</strong><br>
                                            {{ $employee->employee_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <strong>Department:</strong><br>
                                            {{ $employee->department->department_name ?? 'N/A' }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Designation:</strong><br>
                                            {{ $employee->designation->designation_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6><i class="ri-money-dollar-circle-line"></i> Current Salary</h6>
                                    @if($employee->currentSalaryStructure)
                                        <div class="row">
                                            <div class="col-6">
                                                <strong>Basic Salary:</strong><br>
                                                {{ number_format($employee->currentSalaryStructure->basic_salary, 2) }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Gross Salary:</strong><br>
                                                {{ number_format($employee->currentSalaryStructure->gross_salary, 2) }}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <strong>Effective From:</strong><br>
                                                {{ $employee->currentSalaryStructure->effective_from->format('M d, Y') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="ri-alert-line"></i> No salary structure found
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary History Table -->
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="ri-time-line"></i> Salary History</h6>
                        </div>
                        <div class="card-body">
                            @if($employee->salaryHistory->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-default">
                                            <tr>
                                                <th>Period</th>
                                                <th>Basic Salary</th>
                                                <th>HRA</th>
                                                <th>Medical</th>
                                                <th>Transport</th>
                                                <th>Other</th>
                                                <th>Gross Salary</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee->salaryHistory as $salary)
                                                <tr class="{{ $salary->is_active ? 'table-success' : '' }}">
                                                    <td>
                                                        <div>
                                                            <strong>From:</strong> {{ $salary->effective_from->format('M d, Y') }}
                                                        </div>
                                                        @if($salary->effective_to)
                                                            <div>
                                                                <strong>To:</strong> {{ $salary->effective_to->format('M d, Y') }}
                                                            </div>
                                                        @else
                                                            <div>
                                                                <strong>To:</strong> <span class="text-muted">Ongoing</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($salary->basic_salary, 2) }}</td>
                                                    <td>{{ number_format($salary->house_rent_allowance, 2) }}</td>
                                                    <td>{{ number_format($salary->medical_allowance, 2) }}</td>
                                                    <td>{{ number_format($salary->transport_allowance, 2) }}</td>
                                                    <td>{{ number_format($salary->other_allowances, 2) }}</td>
                                                    <td>
                                                        <strong>{{ number_format($salary->gross_salary, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        @if($salary->is_active)
                                                            <span class="badge bg-success">
                                                                <i class="ri-check-line"></i> Active
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="ri-time-line"></i> Inactive
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($salary->notes)
                                                            <span class="text-muted" title="{{ $salary->notes }}">
                                                                {{ Str::limit($salary->notes, 30) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-history-line text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-2">No Salary History</h5>
                                    <p class="text-muted">This employee has no salary history records.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tax and Deduction Preferences -->
                    @if($employee->taxPreferences || $employee->deductionPreferences->count() > 0)
                        <div class="row mt-4">
                            <!-- Tax Preferences -->
                            @if($employee->taxPreferences)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="ri-percent-line"></i> Tax Preferences</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <strong>Tax Slab:</strong><br>
                                                    @if($employee->taxPreferences->taxSlab)
                                                        {{ $employee->taxPreferences->taxSlab->min_salary }} - {{ $employee->taxPreferences->taxSlab->max_salary }} ({{ $employee->taxPreferences->taxSlab->tax_percent }}%)
                                                    @else
                                                        <span class="text-muted">Auto</span>
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <strong>Exemption:</strong><br>
                                                    {{ number_format($employee->taxPreferences->tax_exemption_amount, 2) }}
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <strong>Apply Tax:</strong>
                                                    @if($employee->taxPreferences->apply_tax)
                                                        <span class="badge bg-success">Yes</span>
                                                    @else
                                                        <span class="badge bg-danger">No</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Deduction Preferences -->
                            @if($employee->deductionPreferences->count() > 0)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="ri-subtract-line"></i> Deduction Preferences</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($employee->deductionPreferences as $deduction)
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <strong>{{ $deduction->deductionType->name }}:</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        {{ number_format($deduction->amount, 2) }}
                                                        @if($deduction->type === 'percentage')
                                                            %
                                                        @endif
                                                        @if($deduction->is_active)
                                                            <span class="badge bg-success ms-1">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary ms-1">Inactive</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

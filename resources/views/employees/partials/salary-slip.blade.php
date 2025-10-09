<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ $employee->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 12px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo {
            height: 40px;
            margin-bottom: 5px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .slip-title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .employee-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 8px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table th {
            background-color: #f0f0f0;
            padding: 4px 6px;
            text-align: left;
            border: 1px solid #000;
            font-weight: bold;
        }
        .table td {
            padding: 3px 6px;
            border: 1px solid #000;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
        }
        .net-salary {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
            padding: 8px;
            margin-top: 10px;
            border: 2px solid #000;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 10px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('ucs-logo.png') }}" alt="SuperNova School" class="logo">
        <div class="company-name">Super Nova School</div>
        <div>{{ $employee->branch->br_name ?? 'Head Office' }}</div>
        <div class="slip-title">Salary Slip For The Month of {{ $period }}</div>
    </div>

    <!-- Employee Information -->
    <div class="employee-info">
        <div><strong>Employee ID:</strong> {{ $employee->employee_id ?? 'N/A' }}</div>
        <div><strong>Name:</strong> {{ $employee->full_name ?? 'N/A' }}</div>
        <div><strong>Designation:</strong> {{ $employee->designation->designation_name ?? 'N/A' }}</div>
        <div><strong>Department:</strong> {{ $employee->department->department_name ?? 'N/A' }}</div>
    </div>

    @if($payroll)
        <div class="two-column">
            <!-- Earnings Section -->
            <div class="section">
                <div class="section-title">Earnings</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="amount">Amount (PKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Basic Salary</td>
                            <td class="amount">{{ number_format($payroll->basic_salary ?? 0, 2) }}</td>
                        </tr>
                        @if($payroll->details)
                            @foreach($payroll->details->where('type', 'allowance') as $allowance)
                                <tr>
                                    <td>{{ $allowance->label ?? 'Allowance' }}</td>
                                    <td class="amount">{{ number_format($allowance->amount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="total-row">
                            <td><strong>Total Earnings</strong></td>
                            <td class="amount"><strong>{{ number_format($payroll->gross_salary ?? 0, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Deductions Section -->
            <div class="section">
                <div class="section-title">Deductions</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="amount">Amount (PKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($payroll->absent_deduction > 0)
                            <tr>
                                <td>Absent Days</td>
                                <td class="amount">{{ number_format($payroll->absent_deduction, 2) }}</td>
                            </tr>
                        @endif
                        @if($payroll->late_deduction > 0)
                            <tr>
                                <td>Late Minutes</td>
                                <td class="amount">{{ number_format($payroll->late_deduction, 2) }}</td>
                            </tr>
                        @endif
                        @if($payroll->details)
                            @foreach($payroll->details->where('type', 'deduction') as $deduction)
                                @if(!in_array($deduction->label, ['Income Tax', 'Provident Fund']))
                                    <tr>
                                        <td>{{ $deduction->label ?? 'Deduction' }}</td>
                                        <td class="amount">{{ number_format($deduction->amount ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        @if($payroll->provident_fund_employee > 0)
                            <tr>
                                <td>Provident Fund</td>
                                <td class="amount">{{ number_format($payroll->provident_fund_employee, 2) }}</td>
                            </tr>
                        @endif
                        @if($payroll->income_tax > 0)
                            <tr>
                                <td>Income Tax</td>
                                <td class="amount">{{ number_format($payroll->income_tax, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td><strong>Total Deductions</strong></td>
                            <td class="amount"><strong>{{ number_format($payroll->total_deductions ?? 0, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Net Salary -->
        <div class="net-salary">
            <div>NET SALARY: PKR {{ number_format($payroll->net_salary ?? 0, 2) }}</div>
        </div>

        <!-- Tax Information (if applicable) -->
        @if($payroll->income_tax > 0)
            <div class="section">
                <div class="section-title">Tax Information</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong>Taxable Amount:</strong></td>
                            <td class="amount">{{ number_format($payroll->taxable_gross_salary ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tax Exemption:</strong></td>
                            <td class="amount">30,000.00</td>
                        </tr>
                        <tr>
                            <td><strong>Net Taxable Income:</strong></td>
                            <td class="amount">{{ number_format(($payroll->taxable_gross_salary ?? 0) - 30000, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Income Tax:</strong></td>
                            <td class="amount">{{ number_format($payroll->income_tax, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

    @else
        <!-- No Payroll Data -->
        <div class="no-data">
            <h3>No Payroll Data Found</h3>
            <p>No processed payroll found for {{ $period }}.</p>
            <p>Please contact HR department for assistance.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Super Nova School</strong> - {{ $employee->branch->br_name ?? 'Head Office' }}</p>
        <p>This is a computer generated salary slip and does not require a signature.</p>
        <p>Generated on: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
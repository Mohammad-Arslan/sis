@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daily Operational Report</h4>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('daily-operational-report.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="report_date" class="form-label">Report Date</label>
                        <input type="date" class="form-control" id="report_date" name="report_date" 
                               value="{{ $selectedDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="branch_id" class="form-label">Campus(es)</label>
                        <select class="form-select" id="branch_id" name="branch_id[]" multiple="multiple">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (is_array($branchId) && in_array($branch->id, $branchId)) || (!is_array($branchId) && $branchId == $branch->id) ? 'selected' : '' }}>
                                    {{ $branch->br_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line align-bottom me-1"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Report Content -->
                <div class="table-responsive" id="report-content">
                    <table class="table table-bordered table-striped">
                        <thead class="table-default">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Campus</th>
                                <th colspan="5" class="text-center">Employee Attendance</th>
                                <th colspan="3" class="text-center">Student Attendance</th>
                                <th colspan="2" class="text-center">Admissions & Withdrawal</th>
                                <th colspan="2" class="text-center">Meter Reading</th>
                            </tr>
                            <tr>
                                <!-- Employee Attendance Headers -->
                                <th class="text-center">Total</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late Arrival</th>
                                <th class="text-center">Early Pack up</th>
                                
                                <!-- Student Attendance Headers -->
                                <th class="text-center">Total</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                
                                <!-- Admissions & Withdrawal Headers -->
                                <th class="text-center">New Admis</th>
                                <th class="text-center">Withdrawals</th>
                                
                                <!-- Meter Reading Headers -->
                                <th class="text-center">EL-unit Consumed</th>
                                <th class="text-center">Generator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['campusData'] as $campus)
                            <tr>
                                <td class="fw-bold">{{ $campus['campus_name'] }}</td>
                                
                                <!-- Employee Attendance Data -->
                                <td class="text-center">{{ $campus['employee_attendance']['total'] }}</td>
                                <td class="text-center">{{ $campus['employee_attendance']['present'] }}</td>
                                <td class="text-center">{{ $campus['employee_attendance']['absent'] }}</td>
                                <td class="text-center">{{ $campus['employee_attendance']['late_arrival'] }}</td>
                                <td class="text-center">{{ $campus['employee_attendance']['early_pack_up'] }}</td>
                                
                                <!-- Student Attendance Data -->
                                <td class="text-center">{{ $campus['student_attendance']['total'] }}</td>
                                <td class="text-center">{{ $campus['student_attendance']['present'] }}</td>
                                <td class="text-center">{{ $campus['student_attendance']['absent'] }}</td>
                                
                                <!-- Admissions & Withdrawal Data -->
                                <td class="text-center">{{ $campus['admissions_withdrawal']['new_admissions'] }}</td>
                                <td class="text-center">{{ $campus['admissions_withdrawal']['withdrawals'] }}</td>
                                
                                <!-- Meter Reading Data -->
                                <td class="text-center">{{ $campus['meter_reading']['el_unit_consumed'] ?? 0 }}</td>
                                <td class="text-center">{{ $campus['meter_reading']['generator'] ?? 0 }}</td>
                            </tr>
                            @endforeach
                            
                            <!-- Grand Total Row -->
                            <tr class="table-warning fw-bold">
                                <td>Grand Total</td>
                                
                                <!-- Employee Attendance Grand Total -->
                                <td class="text-center">{{ $reportData['grandTotal']['employee_attendance']['total'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['employee_attendance']['present'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['employee_attendance']['absent'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['employee_attendance']['late_arrival'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['employee_attendance']['early_pack_up'] }}</td>
                                
                                <!-- Student Attendance Grand Total -->
                                <td class="text-center">{{ $reportData['grandTotal']['student_attendance']['total'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['student_attendance']['present'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['student_attendance']['absent'] }}</td>
                                
                                <!-- Admissions & Withdrawal Grand Total -->
                                <td class="text-center">{{ $reportData['grandTotal']['admissions_withdrawal']['new_admissions'] }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['admissions_withdrawal']['withdrawals'] }}</td>
                                
                                <!-- Meter Reading Grand Total -->
                                <td class="text-center">{{ $reportData['grandTotal']['meter_reading']['el_unit_consumed'] ?? 0 }}</td>
                                <td class="text-center">{{ $reportData['grandTotal']['meter_reading']['generator'] ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for branch multi-select
        $('#branch_id').select2({
            placeholder: "Select Campus(es)",
            allowClear: true,
            width: '100%',
            tags: false,
            closeOnSelect: false
        });
    });
</script>
@endpush

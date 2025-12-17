@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Comprehensive Attendance Report</h4>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('attendance-report.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="report_month" class="form-label">Report Month</label>
                                <input type="month" class="form-control" id="report_month" name="report_month" 
                                       value="{{ $selectedMonth }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="branch_id" class="form-label">Branch(es)</label>
                                <select class="form-select" id="branch_id" name="branch_id[]" multiple="multiple">
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ (is_array($branchId) && in_array($branch->id, $branchId)) || (!is_array($branchId) && $branchId == $branch->id) ? 'selected' : '' }}>
                                            {{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                                <select class="form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                            {{ $year->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                    <a href="{{ route('attendance-report.pdf', request()->query()) }}" 
                                       class="btn btn-success" target="_blank">Export PDF</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Date Display -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="text-center text-primary">Report for: {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }} ({{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }})</h5>
                    </div>
                </div>

                <!-- (A) Staff Attendance Summary -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(A) Staff Attendance Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Employee Type</th>
                                                <th>Total</th>
                                                <th>Present</th>
                                                <th>Leave</th>
                                                <th>Late</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reportData['staffSummary'] as $summary)
                                                <tr>
                                                    <td>{{ $summary['employee_type'] }}</td>
                                                    <td>{{ $summary['total'] }}</td>
                                                    <td>{{ $summary['present'] }}</td>
                                                    <td>{{ $summary['leave'] }}</td>
                                                    <td>{{ $summary['late'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (B) Late Arrivals -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(B) Late Arrivals</h6>
                            </div>
                            <div class="card-body">
                                <div id="late-arrivals-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Time In</th>
                                                    <th>Late Minutes</th>
                                                    <th># Current Month Late</th>
                                                </tr>
                                            </thead>
                                            <tbody id="late-arrivals-tbody">
                                                @forelse($reportData['lateArrivals'] as $late)
                                                    <tr>
                                                        <td>{{ $late['date'] }}</td>
                                                        <td>{{ $late['id'] }}</td>
                                                        <td>{{ $late['name'] }}</td>
                                                        <td>{{ $late['time_in'] }}</td>
                                                        <td>{{ $late['late_minutes'] }}</td>
                                                        <td>{{ $late['current_month_late'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">No late arrivals found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="late-arrivals-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (C) Early Departures -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(C) List of Early Pack up (All Staff)</h6>
                            </div>
                            <div class="card-body">
                                <div id="early-departures-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Time Out</th>
                                                    <th>Early Minutes</th>
                                                    <th># Current Month Early</th>
                                                </tr>
                                            </thead>
                                            <tbody id="early-departures-tbody">
                                                @forelse($reportData['earlyDepartures'] as $early)
                                                    <tr>
                                                        <td>{{ $early['id'] }}</td>
                                                        <td>{{ $early['name'] }}</td>
                                                        <td>{{ $early['designation'] }}</td>
                                                        <td>{{ $early['time_out'] }}</td>
                                                        <td>{{ $early['early_minutes'] }}</td>
                                                        <td>{{ $early['current_month_early'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">No early departures found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="early-departures-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (D) Student Strength & Attendance -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(D) Total Student Strength & Attendance</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Grade</th>
                                                <th>Total Strength</th>
                                                <th>Present</th>
                                                <th>Absent</th>
                                                <th>Present %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reportData['studentStrength'] as $strength)
                                                <tr>
                                                    <td>{{ $strength['grade'] }}</td>
                                                    <td>{{ $strength['total_strength'] }}</td>
                                                    <td>{{ $strength['present'] }}</td>
                                                    <td>{{ $strength['absent'] }}</td>
                                                    <td>{{ $strength['present_percentage'] }}%</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (E) Absent Students -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(E) List Of Absent Students (3 Or More Days)</h6>
                            </div>
                            <div class="card-body">
                                <div id="absent-students-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Name</th>
                                                    <th>Contact #</th>
                                                    <th>Follow Up Feed Back</th>
                                                </tr>
                                            </thead>
                                            <tbody id="absent-students-tbody">
                                                @forelse($reportData['absentStudents'] as $absent)
                                                    <tr>
                                                        <td>{{ $absent['grade'] }}</td>
                                                        <td>{{ $absent['name'] }}</td>
                                                        <td>{{ $absent['contact'] }}</td>
                                                        <td>{{ $absent['follow_up_feedback'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No absent students found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="absent-students-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (F) First 3 Learners -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(F) List of First 3 Learners (Arrival)</h6>
                            </div>
                            <div class="card-body">
                                <div id="first-learners-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student ID</th>
                                                    <th>Student Names</th>
                                                    <th>Grade</th>
                                                    <th>Time In</th>
                                                    <th>Staff on Duty</th>
                                                </tr>
                                            </thead>
                                            <tbody id="first-learners-tbody">
                                                @forelse($reportData['firstLearners'] as $learner)
                                                    <tr>
                                                        <td>{{ $learner['student_id'] }}</td>
                                                        <td>{{ $learner['student_name'] }}</td>
                                                        <td>{{ $learner['grade'] }}</td>
                                                        <td>{{ $learner['time_in'] }}</td>
                                                        <td>{{ $learner['staff_on_duty'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="first-learners-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (G) Last 3 Learners -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(G) List of Last 3 Learners (Departure)</h6>
                            </div>
                            <div class="card-body">
                                <div id="last-learners-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student ID</th>
                                                    <th>Student Names</th>
                                                    <th>Grade</th>
                                                    <th>Time Out</th>
                                                    <th>Staff on Duty</th>
                                                </tr>
                                            </thead>
                                            <tbody id="last-learners-tbody">
                                                @forelse($reportData['lastLearners'] as $learner)
                                                    <tr>
                                                        <td>{{ $learner['student_id'] }}</td>
                                                        <td>{{ $learner['student_name'] }}</td>
                                                        <td>{{ $learner['grade'] }}</td>
                                                        <td>{{ $learner['time_out'] }}</td>
                                                        <td>{{ $learner['staff_on_duty'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="last-learners-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (H) New Admissions -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(H) New Admissions</h6>
                            </div>
                            <div class="card-body">
                                <div id="new-admissions-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Total # of Admission</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody id="new-admissions-tbody">
                                                @forelse($reportData['newAdmissions'] as $admission)
                                                    <tr>
                                                        <td>{{ $admission['grade'] }}</td>
                                                        <td>{{ $admission['total_admissions'] }}</td>
                                                        <td>{{ $admission['remarks'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No new admissions found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="new-admissions-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (I) Student Withdrawals -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border border-pink">
                            <div class="card-header bg-pink text-white">
                                <h6 class="mb-0">(I) Student Withdrawals</h6>
                            </div>
                            <div class="card-body">
                                <div id="student-withdrawals-container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student Names</th>
                                                    <th>Grades</th>
                                                    <th>Reason Of Withdrawal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="student-withdrawals-tbody">
                                                @forelse($reportData['studentWithdrawals'] as $withdrawal)
                                                    <tr>
                                                        <td>{{ $withdrawal['student_name'] }}</td>
                                                        <td>{{ $withdrawal['grade'] }}</td>
                                                        <td>{{ $withdrawal['reason'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No withdrawals found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="student-withdrawals-pagination" class="d-flex justify-content-center mt-3">
                                        <!-- Pagination will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('header_scripts')
<style>
.bg-pink {
    background-color: #f8d7da !important;
    color: #721c24 !important;
}
.border-pink {
    border-color: #f8d7da !important;
}
</style>
@endpush
@push('footer_scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for branch multi-select
    $('#branch_id').select2({
        placeholder: "Select Branch(es)",
        allowClear: true,
        width: '100%',
        tags: false,
        closeOnSelect: false
    });

    // Initialize pagination for all reports
    initializePagination('late-arrivals', 'late_arrivals');
    initializePagination('early-departures', 'early_departures');
    initializePagination('absent-students', 'absent_students');
    initializePagination('first-learners', 'first_learners');
    initializePagination('last-learners', 'last_learners');
    initializePagination('new-admissions', 'new_admissions');
    initializePagination('student-withdrawals', 'student_withdrawals');
});

function initializePagination(containerId, reportType) {
    loadReportData(containerId, reportType, 1);
}

function loadReportData(containerId, reportType, page = 1) {
    const tbodyId = containerId + '-tbody';
    const paginationId = containerId + '-pagination';
    
    // Show loading
    $('#' + tbodyId).html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
    
    $.ajax({
        url: '{{ route("attendance-report.data") }}',
        method: 'GET',
        data: {
            report_type: reportType,
            report_month: '{{ $selectedMonth }}',
            branch_id: @json($branchId),
            academic_year_id: '{{ $academicYearId }}',
            page: page,
            per_page: 10
        },
        success: function(response) {
            // Update table body
            updateTableBody(tbodyId, response.data, reportType);
            
            // Update pagination
            updatePagination(paginationId, response, containerId, reportType);
        },
        error: function(xhr, status, error) {
            $('#' + tbodyId).html('<tr><td colspan="10" class="text-center text-danger">Error loading data</td></tr>');
            console.error('Error:', error);
        }
    });
}

function updateTableBody(tbodyId, data, reportType) {
    let html = '';
    
    if (data.length === 0) {
        // Show specific message based on report type
        let message = 'No data available';
        if (reportType === 'early_departures') {
            message = 'No early departures found';
        } else if (reportType === 'late_arrivals') {
            message = 'No late arrivals found';
        } else if (reportType === 'absent_students') {
            message = 'No absent students found';
        }
        html = `<tr><td colspan="10" class="text-center">${message}</td></tr>`;
    } else {
        data.forEach(function(item) {
            html += '<tr>';
            
            // Generate table rows based on report type
            switch(reportType) {
                case 'late_arrivals':
                    html += `<td>${item.date}</td>`;
                    html += `<td>${item.id}</td>`;
                    html += `<td>${item.name}</td>`;
                    html += `<td>${item.time_in}</td>`;
                    html += `<td>${item.late_minutes}</td>`;
                    html += `<td>${item.current_month_late}</td>`;
                    break;
                    
                case 'early_departures':
                    html += `<td>${item.id}</td>`;
                    html += `<td>${item.name}</td>`;
                    html += `<td>${item.designation}</td>`;
                    html += `<td>${item.time_out}</td>`;
                    html += `<td>${item.early_minutes}</td>`;
                    html += `<td>${item.current_month_early}</td>`;
                    break;
                    
                case 'absent_students':
                    html += `<td>${item.grade}</td>`;
                    html += `<td>${item.name}</td>`;
                    html += `<td>${item.contact}</td>`;
                    html += `<td>${item.follow_up_feedback}</td>`;
                    break;
                    
                case 'first_learners':
                    html += `<td>${item.student_id}</td>`;
                    html += `<td>${item.student_name}</td>`;
                    html += `<td>${item.grade}</td>`;
                    html += `<td>${item.time_in}</td>`;
                    html += `<td>${item.staff_on_duty}</td>`;
                    break;
                    
                case 'last_learners':
                    html += `<td>${item.student_id}</td>`;
                    html += `<td>${item.student_name}</td>`;
                    html += `<td>${item.grade}</td>`;
                    html += `<td>${item.time_out}</td>`;
                    html += `<td>${item.staff_on_duty}</td>`;
                    break;
                    
                case 'new_admissions':
                    html += `<td>${item.grade}</td>`;
                    html += `<td>${item.total_admissions}</td>`;
                    html += `<td>${item.remarks}</td>`;
                    break;
                    
                case 'student_withdrawals':
                    html += `<td>${item.student_name}</td>`;
                    html += `<td>${item.grade}</td>`;
                    html += `<td>${item.reason}</td>`;
                    break;
            }
            
            html += '</tr>';
        });
    }
    
    $('#' + tbodyId).html(html);
}

function updatePagination(paginationId, response, containerId, reportType) {
    let paginationHtml = '';
    
    if (response.last_page > 1) {
        paginationHtml = '<nav><ul class="pagination">';
        
        // Previous button
        if (response.current_page > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" onclick="loadReportData('${containerId}', '${reportType}', ${response.current_page - 1}); return false;">Previous</a>
            </li>`;
        }
        
        // Page numbers
        for (let i = 1; i <= response.last_page; i++) {
            const activeClass = i === response.current_page ? 'active' : '';
            paginationHtml += `<li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="loadReportData('${containerId}', '${reportType}', ${i}); return false;">${i}</a>
            </li>`;
        }
        
        // Next button
        if (response.current_page < response.last_page) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" onclick="loadReportData('${containerId}', '${reportType}', ${response.current_page + 1}); return false;">Next</a>
            </li>`;
        }
        
        paginationHtml += '</ul></nav>';
        
        // Show info
        paginationHtml += `<div class="text-center mt-2">
            <small class="text-muted">
                Showing ${response.from} to ${response.to} of ${response.total} entries
            </small>
        </div>`;
    }
    
    $('#' + paginationId).html(paginationHtml);
}
</script>
@endpush
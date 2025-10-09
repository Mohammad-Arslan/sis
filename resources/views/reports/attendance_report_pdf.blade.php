<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprehensive Attendance Report - {{ $selectedMonth }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-date {
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .section-title {
            background-color: #f8d7da;
            color: #721c24;
            padding: 8px;
            margin: -10px -10px 10px -10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">Comprehensive Attendance Report</div>
        <div class="report-date">Report for: {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }} ({{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }})</div>
    </div>

    <!-- (A) Staff Attendance Summary -->
    <div class="section">
        <div class="section-title">(A) Staff Attendance Summary</div>
        <table>
            <thead>
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
                        <td class="text-center">{{ $summary['total'] }}</td>
                        <td class="text-center">{{ $summary['present'] }}</td>
                        <td class="text-center">{{ $summary['leave'] }}</td>
                        <td class="text-center">{{ $summary['late'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (B) Late Arrivals -->
    <div class="section">
        <div class="section-title">(B) Late Arrivals</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Late Minutes</th>
                    <th># Current Month Late</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['lateArrivals'] as $late)
                    <tr>
                        <td>{{ $late['date'] }}</td>
                        <td>{{ $late['id'] }}</td>
                        <td>{{ $late['name'] }}</td>
                        <td>{{ $late['time_in'] }}</td>
                        <td class="text-center">{{ $late['late_minutes'] }}</td>
                        <td class="text-center">{{ $late['current_month_late'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">No late arrivals found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (C) Early Departures -->
    <div class="section">
        <div class="section-title">(C) List of Early Pack up (All Staff)</div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Time Out</th>
                    <th>Early Minutes</th>
                    <th># Current Month Early</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['earlyDepartures'] as $early)
                    <tr>
                        <td>{{ $early['id'] }}</td>
                        <td>{{ $early['name'] }}</td>
                        <td>{{ $early['designation'] }}</td>
                        <td>{{ $early['time_out'] }}</td>
                        <td class="text-center">{{ $early['early_minutes'] }}</td>
                        <td class="text-center">{{ $early['current_month_early'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">No early departures found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (D) Student Strength & Attendance -->
    <div class="section">
        <div class="section-title">(D) Total Student Strength & Attendance</div>
        <table>
            <thead>
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
                        <td class="text-center">{{ $strength['total_strength'] }}</td>
                        <td class="text-center">{{ $strength['present'] }}</td>
                        <td class="text-center">{{ $strength['absent'] }}</td>
                        <td class="text-center">{{ $strength['present_percentage'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (E) Absent Students -->
    <div class="section">
        <div class="section-title">(E) List Of Absent Students (3 Or More Days)</div>
        <table>
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Name</th>
                    <th>Contact #</th>
                    <th>Follow Up Feed Back</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['absentStudents'] as $absent)
                    <tr>
                        <td>{{ $absent['grade'] }}</td>
                        <td>{{ $absent['name'] }}</td>
                        <td>{{ $absent['contact'] }}</td>
                        <td>{{ $absent['follow_up_feedback'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="no-data">No absent students found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (F) First 3 Learners -->
    <div class="section">
        <div class="section-title">(F) List of First 3 Learners (Arrival)</div>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Names</th>
                    <th>Grade</th>
                    <th>Time In</th>
                    <th>Staff on Duty</th>
                </tr>
            </thead>
            <tbody>
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
                        <td colspan="5" class="no-data">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (G) Last 3 Learners -->
    <div class="section">
        <div class="section-title">(G) List of Last 3 Learners (Departure)</div>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Names</th>
                    <th>Grade</th>
                    <th>Time Out</th>
                    <th>Staff on Duty</th>
                </tr>
            </thead>
            <tbody>
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
                        <td colspan="5" class="no-data">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (H) New Admissions -->
    <div class="section">
        <div class="section-title">(H) New Admissions</div>
        <table>
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Total # of Admission</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['newAdmissions'] as $admission)
                    <tr>
                        <td>{{ $admission['grade'] }}</td>
                        <td class="text-center">{{ $admission['total_admissions'] }}</td>
                        <td>{{ $admission['remarks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="no-data">No new admissions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- (I) Student Withdrawals -->
    <div class="section">
        <div class="section-title">(I) Student Withdrawals</div>
        <table>
            <thead>
                <tr>
                    <th>Student Names</th>
                    <th>Grades</th>
                    <th>Reason Of Withdrawal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['studentWithdrawals'] as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal['student_name'] }}</td>
                        <td>{{ $withdrawal['grade'] }}</td>
                        <td>{{ $withdrawal['reason'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="no-data">No withdrawals found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
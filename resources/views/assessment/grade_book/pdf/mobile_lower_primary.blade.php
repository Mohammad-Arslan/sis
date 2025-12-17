<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report - Mobile</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }
        
        /* Mobile-Optimized Layout */
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .header h1 {
            color: #1e398d;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            color: #1e398d;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .header h3 {
            color: #1e398d;
            font-size: 14px;
            font-weight: normal;
        }
        
        /* Student Info Section */
        .student-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }
        
        .info-label {
            color: #1e398d;
            font-weight: bold;
            min-width: 120px;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        /* Progress Report Info */
        .progress-info {
            margin-bottom: 20px;
        }
        
        .progress-info table {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-info td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        
        .progress-info tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        
        /* Grading Key */
        .grading-key {
            background-color: #dcdddf;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .grading-key h4 {
            color: #1e398d;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .grading-item {
            margin-bottom: 5px;
            display: inline-block;
            margin-right: 15px;
        }
        
        .grading-item strong {
            color: #1e398d;
        }
        
        /* Assessment Table */
        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            overflow-x: auto;
            display: block;
        }
        
        .assessment-table table {
            width: 100%;
            min-width: 600px;
        }
        
        .assessment-table th,
        .assessment-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        
        .assessment-table th {
            background-color: #1e398d;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        
        .assessment-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Behaviour Table */
        .behaviour-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .behaviour-table th,
        .behaviour-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .behaviour-table th {
            background-color: #1e398d;
            color: white;
        }
        
        .behaviour-category {
            background-color: #dcdddf;
            font-weight: bold;
        }
        
        .behaviour-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Comments Section */
        .comments-section {
            background-color: #cdede8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .comment-block {
            margin-bottom: 15px;
        }
        
        .comment-label {
            font-weight: bold;
            color: #1e398d;
            margin-bottom: 5px;
        }
        
        .comment-text {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 4px;
            min-height: 40px;
        }
        
        /* Signatures */
        .signatures {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .signature-row {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .signature-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        /* Promoted/Not Promoted */
        .promotion-status {
            font-weight: bold;
            color: #00A78D;
            margin: 10px 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                padding: 5px;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                margin-bottom: 3px;
            }
            
            .assessment-table {
                font-size: 9px;
            }
            
            .assessment-table th,
            .assessment-table td {
                padding: 3px;
            }
        }
        
        /* Page Break for Print */
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PROGRESS REPORT</h1>
            <h2>{{ $student_behaviour_skill['term']['name'] ?? 'N/A' }}</h2>
            <h2>
                @if (str_contains($student_behaviour_skill['com_class']['class_name'] ?? '', '1') ||
                     str_contains($student_behaviour_skill['com_class']['class_name'] ?? '', 'one') ||
                     str_contains($student_behaviour_skill['com_class']['class_name'] ?? '', '2') ||
                     str_contains($student_behaviour_skill['com_class']['class_name'] ?? '', 'two'))
                    Lower Primary
                @else
                    Upper Primary
                @endif
            </h2>
            <h3>{{ $student_behaviour_skill['com_class']['class_name'] ?? 'N/A' }}</h3>
            <p>Academic Year {{ substr($student_behaviour_skill['academic_year']['title'] ?? '', 0, 4) ?? 'N/A' }}-{{ substr($student_behaviour_skill['academic_year']['title'] ?? '', -2) ?? 'N/A' }}</p>
        </div>
        
        <!-- Student Information -->
        <div class="student-info">
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ ucwords(($student['first_name'] ?? '') . ' ' . ($student['middle_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Computer ID:</span>
                <span class="info-value">{{ $student['roll_no'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Class:</span>
                <span class="info-value">{{ $student_behaviour_skill['com_class']['class_name'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Class Teacher:</span>
                <span class="info-value">{{ $class_teacher_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Campus:</span>
                <span class="info-value">{{ $student_behaviour_skill['branch']['br_name'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <!-- Progress Report Info -->
        <div class="progress-info">
            <h4 style="color: #1e398d; margin-bottom: 10px;">Progress Report - {{ $student_behaviour_skill['term']['name'] ?? 'N/A' }}</h4>
            <table>
                <tr>
                    <td><strong>Student's Age:</strong> {{ calculate_age($student['date_of_birth'] ?? null) }} years</td>
                    <td><strong>Class Average Age:</strong> {{ $class_average_age ?? 'N/A' }}</td>
                    <td><strong>1st Parent-Teacher Meeting:</strong><br>{{ ($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ?? false) ? 'Attended' : 'Not Attended' }}</td>
                </tr>
            </table>
            
            <table style="margin-top: 10px; background-color: #dcdddf;">
                <tr>
                    <td><strong>No. of Working Days:</strong> {{ $total_no_of_working_days ?? 'N/A' }}</td>
                    <td rowspan="2"><strong>Days Present:</strong> {{ $present_attendances ?? 'N/A' }}</td>
                    <td rowspan="2"><strong>Days Absent:</strong> {{ $absent_attendances ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Percentage of Attendance:</strong> {{ $attendance_percentage ?? 'N/A' }}%</td>
                </tr>
            </table>
        </div>
        
        <!-- Grading Key -->
        <div class="grading-key">
            <h4>Grading Key</h4>
            @if(isset($grading_keys) && count($grading_keys) > 0)
                @foreach ($grading_keys as $key)
                    <div class="grading-item">
                        <strong>{{ $key['grading_key'] ?? '' }}:</strong> {{ $key['title'] ?? '' }}
                    </div>
                @endforeach
            @else
                <p>No grading criteria available</p>
            @endif
        </div>
        
        <!-- Assessment Table -->
        <div class="assessment-table">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">Subjects</th>
                        @if (isset($assessment_weightage))
                            @foreach ($assessment_weightage as $assessment_name => $weightage)
                                <th>{{ $assessment_name }}</th>
                            @endforeach
                        @endif
                        <th>Total Marks</th>
                        <th rowspan="2">Overall Grade</th>
                        <th rowspan="2">Teacher's Comment</th>
                    </tr>
                    <tr>
                        @if (isset($assessment_weightage))
                            @foreach ($assessment_weightage as $assessment_name => $weightage)
                                <th>{{ $weightage }} Marks</th>
                            @endforeach
                            <th>{{ array_sum($assessment_weightage) }} Marks</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (isset($all_assessment_data))
                        @foreach ($all_assessment_data as $assessment => $single_subject_mark)
                            <tr>
                                <td>{{ $single_subject_mark['subject_name'] ?? '' }}</td>
                                @if (isset($assessment_weightage))
                                    @foreach ($assessment_weightage as $assessment_name => $weightage)
                                        <td>{{ $single_subject_mark[$assessment_name] ?? '' }}</td>
                                    @endforeach
                                @endif
                                <td>
                                    @if ($single_subject_mark)
                                        {{ array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort', 'subject_name']))) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($single_subject_mark && isset($assessment_weightage) && array_sum($assessment_weightage) > 0)
                                        {{ get_student_grade($student_behaviour_skill['class_id'] ?? null, round((array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort', 'subject_name']))) / array_sum($assessment_weightage)) * 100)) }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($subject_assessment_remarks[$single_subject_mark['subject_name']]))
                                        {{ ucfirst($subject_assessment_remarks[$single_subject_mark['subject_name']]) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    
                    @if (isset($all_minor_subject_grade))
                        @foreach ($all_minor_subject_grade as $minor_subject_grade)
                            <tr>
                                <td>{{ $minor_subject_grade['subject_name'] ?? '' }}</td>
                                @if (isset($assessment_weightage))
                                    @foreach ($assessment_weightage as $assessment_name => $weightage)
                                        <td style="background-color: #dcdddf;"></td>
                                    @endforeach
                                @endif
                                <td style="background-color: #dcdddf;"></td>
                                <td>{{ $minor_subject_grade['grade'] ?? '' }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Behaviour Assessment -->
        <table class="behaviour-table">
            <thead>
                <tr>
                    <th>Behaviour Assessment</th>
                    <th style="text-align: center;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($general_behaviours))
                    @foreach ($general_behaviours as $behaviour)
                        <tr class="behaviour-category">
                            <td colspan="2">{{ $behaviour['title'] ?? '' }}</td>
                        </tr>
                        @if (isset($behaviour['children']))
                            @foreach ($behaviour['children'] as $child)
                                <tr>
                                    <td>{{ $child['title'] ?? '' }}</td>
                                    <td style="text-align: center;">
                                        {{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) 
                                            ? $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] 
                                            : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
        
        <!-- Comments Section -->
        <div class="comments-section">
            <div class="comment-block">
                <div class="comment-label">Class Teacher's Comments:</div>
                <div class="comment-text">
                    {{ $student_behaviour_skill['student_behaviour_skill_remark']['teacher_comments'] ?? 'N/A' }}
                </div>
            </div>
            
            <div class="comment-block">
                <div class="comment-label">School Head's Comments:</div>
                <div class="comment-text">
                    {{ $student_behaviour_skill['student_behaviour_skill_remark']['schoolhead_comments'] ?? 'N/A' }}
                </div>
            </div>
            
            @if (str_contains($student_behaviour_skill['term']['name'] ?? '', '2'))
                <div class="promotion-status">
                    {{ ($student_behaviour_skill['student_behaviour_skill_remark']['is_promoted'] ?? false) ? 'Promoted' : 'Not Promoted' }}
                </div>
            @endif
        </div>
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-row">
                <strong>Class Teacher's Signature:</strong> {{ $class_teacher_name ?? 'N/A' }}
            </div>
            <div class="signature-row">
                <strong>School Head's Signature:</strong> _______________________
            </div>
            <div class="signature-row">
                <strong>Parent's/Guardian's Signature:</strong> _______________________
            </div>
            <div class="signature-row">
                <strong>Date:</strong> {{ Carbon\Carbon::now()->format('d-m-Y') }}
            </div>
        </div>
    </div>
</body>
</html>

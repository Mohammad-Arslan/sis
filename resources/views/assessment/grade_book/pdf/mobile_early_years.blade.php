<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KG Progress Report - Mobile</title>
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
            color: #00A88E;
            font-size: 18px;
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
            min-height: 50px;
        }
        
        /* Student Information Table */
        .info-table {
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-table tr:last-child td {
            border-bottom: none;
        }
        
        .info-table tr:nth-child(even) {
            background-color: #cdede8;
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
            margin-bottom: 8px;
        }
        
        .grading-item strong {
            color: #1e398d;
        }
        
        /* Skills Assessment */
        .skills-section {
            margin-bottom: 20px;
        }
        
        .skill-category {
            background-color: #00A78D;
            color: white;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .skill-item {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 3px;
            border-left: 3px solid #00A88E;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .skill-name {
            flex: 1;
        }
        
        .skill-grade {
            background-color: #1e398d;
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            min-width: 40px;
            text-align: center;
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
        
        /* Page Break for Print */
        @media print {
            .page-break {
                page-break-after: always;
            }
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
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Progress Report: {{ $student_behaviour_skill['term']['name'] ?? 'N/A' }}</h1>
            <h2>Early Years: {{ $student_behaviour_skill['com_class']['class_name'] ?? 'N/A' }}</h2>
            <h3>Academic Year {{ substr($student_behaviour_skill['academic_year']['title'] ?? '', 0, 4) ?? 'N/A' }}-{{ substr($student_behaviour_skill['academic_year']['title'] ?? '', -2) ?? 'N/A' }}</h3>
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
        
        <!-- Student's Information Table -->
        <table class="info-table">
            <tr>
                <td><strong>Student's Age</strong></td>
                <td>{{ calculate_age($student['date_of_birth'] ?? null) }} years</td>
            </tr>
            <tr>
                <td><strong>Class Average Age</strong></td>
                <td>{{ $class_average_age ?? 'N/A' }} years</td>
            </tr>
            <tr>
                <td><strong>Term 1: Total No. of Working Days</strong></td>
                <td>{{ $total_no_of_working_days ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Term 1: Student's Attendance</strong></td>
                <td>{{ $attendance_percentage ?? 'N/A' }}%</td>
            </tr>
            <tr>
                <td><strong>1st Parent-Teacher Meeting</strong></td>
                <td>{{ ($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ?? false) ? 'Attended' : 'Not Attended' }}</td>
            </tr>
        </table>
        
        <!-- Grading Key -->
        <div class="grading-key">
            <h4>Grading Key:</h4>
            @if(isset($grading_keys) && count($grading_keys) > 0)
                @foreach ($grading_keys as $grading_key)
                    <div class="grading-item">
                        <strong>{{ $grading_key['grading_key'] ?? '' }}:</strong> 
                        {{ $grading_key['title'] ?? '' }}
                        @if (isset($grading_key['description']) && $grading_key['description'])
                            <br><small>{{ $grading_key['description'] }}</small>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No grading criteria available</p>
            @endif
        </div>
        
        <!-- Skills Assessment -->
        <div class="skills-section">
            <h4 style="color: #1e398d; margin-bottom: 10px;">Skills Assessment</h4>
            
            @if (isset($outer_skills) && count($outer_skills) > 0)
                @foreach ($outer_skills as $skill)
                    <div class="skill-category">
                        {{ $skill->title ?? 'N/A' }}
                    </div>
                    @if (isset($skill->children) && count($skill->children) > 0)
                        @foreach ($skill->children as $child)
                            <div class="skill-item">
                                <span class="skill-name">{{ $child->title ?? 'N/A' }}</span>
                                <span class="skill-grade">
                                    {{ isset($student_skill['student_behaviour_skill_marks'][$child->id]) 
                                        ? $student_skill['student_behaviour_skill_marks'][$child->id]['grade'] 
                                        : '-' }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
            
            @if (isset($inner_skills) && count($inner_skills) > 0)
                @foreach ($inner_skills as $sort_no => $skills)
                    @foreach ($skills as $skill)
                        <div class="skill-category" style="background-color: {{ $sort_no % 2 == 0 ? '#00A78D' : '#1e398d' }};">
                            {{ $skill->title ?? 'N/A' }}
                        </div>
                        @if (isset($skill->children) && count($skill->children) > 0)
                            @foreach ($skill->children as $child)
                                <div class="skill-item">
                                    <span class="skill-name">{{ $child->title ?? 'N/A' }}</span>
                                    <span class="skill-grade">
                                        {{ isset($student_skill['student_behaviour_skill_marks'][$child->id]) 
                                            ? $student_skill['student_behaviour_skill_marks'][$child->id]['grade'] 
                                            : '-' }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            @endif
        </div>
        
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
                <strong>Date:</strong> {{ Carbon\Carbon::now()->format('d-m-Y') }}
            </div>
        </div>
    </div>
</body>
</html>

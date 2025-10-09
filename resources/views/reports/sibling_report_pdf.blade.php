<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sibling Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .report-info {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .report-info p {
            margin: 5px 0;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e6f3ff;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sibling Report</h1>
        <p>SuperNovaSchool Management System</p>
        <p>Generated on: {{ $generated_at }}</p>
    </div>
    
    <div class="report-info">
        <p>Branch: {{ $branch_name }}</p>
        <p>Total Records: {{ count($data) }}</p>
    </div>
    
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>Family ID</th>
                    <th>Parent Name</th>
                    <th>Parent CNIC</th>
                    <th>Parent Mobile</th>
                    <th>Relation</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Branch</th>
                    <th>Concession</th>
                    <th>Concession %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row['family_id'] }}</td>
                        <td>{{ $row['parent_name'] }}</td>
                        <td>{{ $row['parent_cnic'] }}</td>
                        <td>{{ $row['parent_mobile'] }}</td>
                        <td>{{ $row['relation'] }}</td>
                        <td>{{ $row['student_id'] }}</td>
                        <td>{{ $row['student_name'] }}</td>
                        <td>{{ $row['class_name'] }}</td>
                        <td>{{ $row['section_name'] }}</td>
                        <td>{{ $row['branch_name'] }}</td>
                        <td>
                            @if($row['concession'] === 'NO')
                                <span class="badge badge-danger">No</span>
                            @else
                                <span class="badge badge-success">{{ $row['concession'] }}</span>
                            @endif
                        </td>
                        <td>
                            @if($row['concession_percentage'] === '0')
                                <span class="badge badge-warning">0%</span>
                            @else
                                <span class="badge badge-success">{{ $row['concession_percentage'] }}%</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>No Data Found</h3>
            <p>No sibling records found for the selected criteria.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>This report was generated automatically by SuperNovaSchool Management System</p>
        <p>© {{ date('Y') }} SuperNovaSchool. All rights reserved.</p>
    </div>
</body>
</html>

@forelse($students as $student)
    <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{ $student['roll_no'] }}</td>
        <td>{{ view('students.student_image_tr', ['row' => $student]) }}</td>
        <td>
            <button  class="btn btn-sm btn-primary show-progress-report-modal" data-target="#progressReportModal" data-url="{{route ('grade-book.get-report',[$student->id,$student_behaiour_skill_id])}}">Get Report</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">No Record Found</td>
    </tr>
@endforelse


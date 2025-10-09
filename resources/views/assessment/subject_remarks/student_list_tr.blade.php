@foreach ($students as $student)
    <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{ $student['roll_no'] }}</td>
        <td>{{ view('students.student_image_tr', ['row' => $student]) }}</td>
        <td><input type="text" name="student_remarks[{{ $student['id'] }}]" class="form-control form-control-sm"
                placeholder="Enter Remarks Here"></td>
    </tr>
@endforeach

<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Student Marks List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <!-- Small Tables -->
            <table class="table table-sm table-nowrap studentListTable">
                <thead>
                    <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">Student ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Marks/Grade</th>
                        <th scope="col">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($assessmentEntry))
                        @foreach ($assessmentEntry['student_assessment_marks'] as $marks)
                            @if ($marks['overall_grade'])
                                <input type="hidden" name="overall_grade" value="1">
                            @endif
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $marks['student']['roll_no'] }}</td>
                                <td>{{ view('students.student_image_tr', ['row' => $marks['student']]) }}</td>
                                <td><input type="text" name="student_marks[{{ $marks['student_id'] }}]"
                                        data-previous_marks="{{ isset($marks) ? $marks['obtained_marks_grades'] : '0' }}"
                                        value="{{ isset($marks['obtained_marks_grades']) ? $marks['obtained_marks_grades'] : $marks['overall_grade'] }}"
                                        class="form-control form-control-sm student-obtained-marks"
                                        placeholder="Enter Marks Here"></td>
                                <td><input type="text" name="student_remarks[{{ $marks['student_id'] }}]"
                                        value="{{ $marks['remarks'] }}" class="form-control form-control-sm"
                                        placeholder="Enter Remarks Here"></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

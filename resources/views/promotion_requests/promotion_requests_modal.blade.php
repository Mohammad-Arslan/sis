<div id="studentPromotionModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">{{ $student_promotion_requests->first()->promotion_request->is_promotion ? 'Student Promotion List': "Student Pass-out List" }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id=""
                       class="table table-bordered table-striped align-middle table-responsive mb-0">
                    <thead>
                    <tr>
                        <th>Sr#</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Admission WEF</th>
                        <th>DOB</th>
                        <th>Status</th>
                        {{--<th>From Academic Year</th>
                        <th>From Branch</th>
                        <th>From Class</th>
                        <th>From Section</th>
                        <th>To Academic Year</th>
                        <th>To Branch</th>
                        <th>To Class</th>
                        <th>To Section</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($student_promotion_requests as $student_promotion_request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student_promotion_request->student->roll_no }}</td>
                            {{--<td>{{ $student_promotion_request->student->first_name . ' ' . $student_promotion_request->student->middle_name ?? '' . $student_promotion_request->student->last_name }}</td>--}}
                            <td> @include('students.student_image_tr', ['row'=> $student_promotion_request->student])</td>
                            <td>{{ $student_promotion_request->student->gender }}</td>
                            <td>{{ date('Y-m-d', strtotime($student_promotion_request->student->admission_wef)) }}</td>
                            <td>{{ date('Y-m-d', strtotime($student_promotion_request->student->date_of_birth)) }}</td>
                            <td>
                                @php
                                if ($student_promotion_request->student->status == 'on_roll')
                                    $status = '<span class="badge bg-success">On Roll</span>';
                                elseif ($student_promotion_request->student->status == 'registered')
                                    $status = '<span class="badge bg-primary">Registered</span>';
                                elseif ($student_promotion_request->student->status == 'left')
                                    $status = '<span class="badge bg-danger">Left</span>';
								elseif ($student_promotion_request->student->status == 'pass-out')
                                    $status = '<span class="badge bg-info">Pass Out</span>';
                                else
                                    $status = '<span class="badge bg-danger">Processing</span>';

                                if ($student_promotion_request->student->from_branch != null) {
                                    $status = $status . '<br><span class="orange-bg">Transfered</span>';
                                }
                                echo $status;
                                @endphp
                            </td>
                            {{--<td>{{ $student_promotion_request->promotion_request->prev_academic_year->title }}</td>
                            <td>{{ $student_promotion_request->promotion_request->prev_branch->br_name }}</td>
                            <td>{{ $student_promotion_request->promotion_request->prev_class->class_name }}</td>
                            <td>{{ $student_promotion_request->promotion_request->prev_section->section_name }}</td>
                            <td>{{ $student_promotion_request->promotion_request->cur_academic_year->title }}</td>
                            <td>{{ $student_promotion_request->promotion_request->cur_branch->br_name }}</td>
                            <td>{{ $student_promotion_request->promotion_request->cur_class->class_name }}</td>
                            <td>{{ $student_promotion_request->promotion_request->cur_section->section_name }}</td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Admission WEF</th>
                        <th>DOB</th>
                        <th>Status</th>
                        {{--<th>From Academic Year</th>
                        <th>From Branch</th>
                        <th>From Class</th>
                        <th>From Section</th>
                        <th>To Academic Year</th>
                        <th>To Branch</th>
                        <th>To Class</th>
                        <th>To Section</th>--}}
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

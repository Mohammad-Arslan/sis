<table class="table table-borderless table-hover table-nowrap align-middle mb-0">
    <thead class="table-light">
    <tr class="text-muted">
        <th scope="col">Name</th>
        <th scope="col" style="width: 16%;" class="text-end">Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($class_students as $class_student)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="{{ default_image() }}" alt="" class="avatar-xs rounded-circle me-2">
                    <div class="ms-2">
                        <h5 class="fs-14 my-1"><a href="{{route('students.edit',$class_student['students']['id']).'?tab=personal'}}" class="text-reset">{{$class_student['students']['first_name'] . ' '.$class_student['students']['middle_name'] .' '.$class_student['students']['last_name'] }}</a></h5>
                        <span class="text-muted">Reg. no. {{ $class_student['students']['registration_number'] }}</span>
                        <input type="hidden" id="branchClassSectionId" name="{{ "branch_class_section_id" }}" value="{{ $class_student['branch_class_section_id'] }}" />
                        <input type="hidden" id="academicYearId" name="{{ "academic_year_id" }}" value="{{ $class_student['academic_year_id'] }}" />
                        <input type="hidden" id="studentId" name="{{ "attendance[" . $loop->iteration . "][student_id]" }}" value="{{ $class_student['students']['id'] }}" />
                    </div>
                </div>
            </td>
            @if(isset($from_branch_setup))
                <td class="text-end">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="student_id[]" value="{{ $class_student['students']['id'] }}">
                    </div>
                </td>
            @else
                <td class="text-end">
                    <input type="hidden" class="{{ 'attendance_input input_attendance_'.$loop->iteration }}" name="{{ "attendance[" . $loop->iteration . "][attendance_status_id]" }}" value="{{1}}" />
                    <button type="button" data-target="{{ 'input_attendance_'.$loop->iteration }}" class="{{ 'btn btn-primary waves-effect waves-light attendance_btn btn_attendance_'.$loop->iteration }}">Present</button>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="2" class="text-center">No Record Found</td>
        </tr>
    @endforelse
    </tbody><!-- end tbody -->
</table><!-- end table -->

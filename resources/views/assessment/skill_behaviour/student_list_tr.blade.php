@forelse($students as $student)
    <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{ $student['roll_no'] }}</td>
        <td>{{ view('students.student_image_tr', ['row' => $student]) }}</td>
        <td>
            @if($show_skill_btn === 'true')
                <a href="javascript:void(0)" class="btn btn-sm badge bg-primary open-skill-modal"
                   data-route="{{route('student-behaviour-skill.skill-modal', ['student_id' => $student['id']] )}}">
                    Skill
                </a>
            @else
                <a href="javascript:void(0)" class="btn btn-sm badge bg-primary open-behaviour-modal"
                   data-route="{{route('student-behaviour-skill.behaviour-modal', ['student_id' => $student['id']] )}}">
                    Behaviour
                </a>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">No Record Found</td>
    </tr>
@endforelse

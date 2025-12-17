@if (count($level->children) > 0 && $level->type == 'title')
    <tr>
        <th style="background-color:{{$sort_no%2 == 0 ? '#1e398d' : '#00a88e'}}; color:#ffffff; padding-left:5px; height: {{$inner_loop_index == 0 ? '60px' : '30px'}} ">
            {{$level->title}}
        </th>
    </tr>
    <tr>
        <td>
            <table style="height: -webkit-fill-available;">
                @foreach ($level->children as $sub_level)
                    @include('assessment.grade_book.api_reports.api_partials.api_col_two_inner_skill',['level' => $sub_level])
                @endforeach
            </table>
        </td>
    </tr>
@else
    <tr>
        @if($level->type == 'checkbox')
            <td style="vertical-align: baseline;margin-left:40px;">
                {{-- <input type="checkbox" disabled {{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : ''}}> --}}

                <div class="custom-check-box">
                    <input type="checkbox" disabled {{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : ''}}>
                   <label class="custom-check-label">
                   </label>
                </div>
            </td>
        @else
            <td style="vertical-align: baseline;">
                <input type="text" style="text-align: center; padding: 0px; font-size: 16px;" readonly class="w-30_h-20" id="checkingssss"
                       value="{{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : ''}}">
            </td>
        @endif
        <td style="vertical-align: baseline;">
            <span class="all-text">
                {{$level->title}}
            </span>
            <table>
                @foreach ($level->children as $sub_level)
                    @include('assessment.grade_book.api_reports.api_partials.api_col_two_inner_skill',['level' => $sub_level])
                @endforeach
            </table>
        </td>
    </tr>
@endif

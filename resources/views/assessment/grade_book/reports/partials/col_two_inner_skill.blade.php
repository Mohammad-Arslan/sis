{{-- @if (count($level->children) > 0 && $level->type == 'title')
    <tr>
        <th style="background-color:{{$sort_no%2 == 0 ? '#1e398d' : '#00a88e'}}; color:#ffffff; padding-left:5px; height: {{$inner_loop_index == 0 ? '60px' : '30px'}} ">
            {{$level->title}}
        </th>
    </tr>
    <tr>
        <td>
            <table style="height: -webkit-fill-available;">
                @foreach ($level->children as $sub_level)
                    @include('assessment.grade_book.reports.partials.col_two_inner_skill',['level' => $sub_level])
                @endforeach
            </table>
        </td>
    </tr>
@else
    <tr>
        @if ($level->type == 'checkbox')
            <td style="vertical-align: baseline;margin-left:40px;">

                <div class="custom-check-box">
                    <input type="checkbox" disabled {{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : ''}}>
                   <label class="custom-check-label">
                   </label>
                </div>
            </td>
        @else
            <td style="vertical-align: baseline;">
                <input type="text" style="text-align: center;" readonly class="w-30_h-20"
                       value="{{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : ''}}">
            </td>
        @endif
        <td style="vertical-align: baseline;">
            <span class="all-text">
                {{$level->title}}
            </span>
            <table>
                @foreach ($level->children as $sub_level)
                    @include('assessment.grade_book.reports.partials.col_two_inner_skill',['level' => $sub_level])
                @endforeach
            </table>
        </td>
    </tr>
@endif --}}



@if (count($level->children) > 0 && $level->type == 'title')
    <div
        style="background-color:{{ $sort_no % 2 == 0 ? '#1e398d' : '#00a88e' }}; color:#ffffff; padding-left:5px; max-height: {{ $inner_loop_index == 0 ? '60px' : '30px' }} ">
        {{ $level->title }}
    </div>
    <table style="height: -webkit-fill-available;">
        @foreach ($level->children as $sub_level)
            @include('assessment.grade_book.reports.partials.col_two_inner_skill', [
                'level' => $sub_level,
            ])
        @endforeach
    </table>
@else
    <div class="d-flex">
        @if ($level->type == 'checkbox')
            {{-- <div style="vertical-align: baseline;margin-left:30px;"> --}}
            <div class="custom-check-box">
                <input type="checkbox" disabled
                    {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
                <label class="custom-check-label">
                </label>
            </div>
            {{-- </div> --}}
        @else
            <div style="vertical-align: baseline;">
                <input type="text" style="text-align: center;" readonly class="w-30_h-20"
                    value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}">
                {{-- <span class="all-text" style="margin-left:5px">{{ $level->title }}</span> --}}
            </div>
        @endif
        <div style="vertical-align: baseline;">
            <span class="all-text">
                {{ $level->title }}
            </span>
            @foreach ($level->children as $sub_level)
                <div class="d-flex">
                    @include('assessment.grade_book.reports.partials.col_two_inner_skill', [
                        'level' => $sub_level,
                    ])
                </div>
            @endforeach
        </div>
    </div>
@endif





{{-- <input type="checkbox" disabled {{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : ''}}> --}}

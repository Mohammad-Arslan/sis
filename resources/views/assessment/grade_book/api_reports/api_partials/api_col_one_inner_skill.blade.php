@php
    if ($level->parent_id == 0 && $level->sort_no == 1) {
        $color = '#007a66';
        $bg_color = '#80d3c6';
    } else {
        $color = '#ffffff';
        $bg_color = '#00a88e';
    }
@endphp
@if ($level->parent_id == 0)
    <td>
        <table style="height:100%; background-color:#cdede8;">
            <tr>
                <th style="background-color:{{ $bg_color }}; padding-left:5px;  color:{{ $color }}">
                    {{ $level->title }}
                </th>
            </tr>
            @foreach ($level->children as $sub_level)
                @include('assessment.grade_book.api_reports.api_partials.api_col_one_inner_skill', [
                    'level' => $sub_level,
                ])
            @endforeach
        </table>
    </td>
@else
    @if ($level->type == 'title')
        <tr>
            <th style="color:{{ $color }}; padding-left:5px;  background-color:{{ $bg_color }}">
                {{ $level->title }}
            </th>
        </tr>
        <tr>
            <td>
                <table class="check-box-padding">
                    @foreach ($level->children as $sub_level)
                        @include('assessment.grade_book.api_reports.api_partials.api_col_one_inner_skill', [
                            'level' => $sub_level,
                        ])
                    @endforeach
                </table>
            </td>
        </tr>
    @elseif($level->type == 'checkbox')
        <tr>
            <td style="vertical-align: baseline; margin-left:40px;">
                <div class="custom-check-box">
                    <input type="checkbox" disabled
                        {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
                    <label class="custom-check-label">
                    </label>
                </div>
                {{-- <input type="checkbox" disabled class="w-30_h-20" {{isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : ''}}> --}}
            </td>
            <td>
                <span class="all-text">
                    {{ $level->title }}
                </span>
            </td>
        </tr>
    @else
        <tr>
            <td style="vertical-align: baseline;">
                <input type="text" style="text-align:center; padding: 0px; font-size: 16px;" readonly class="w-30_h-20"
                    value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}">
            </td>
            <td>
                <span class="all-text">
                    {{ $level->title }}
                </span>
                <table>
                    @foreach ($level->children as $sub_level)
                        @include('assessment.grade_book.api_reports.api_partials.api_col_one_inner_skill', [
                            'level' => $sub_level,
                        ])
                    @endforeach
                </table>
            </td>
        </tr>
    @endif
@endif

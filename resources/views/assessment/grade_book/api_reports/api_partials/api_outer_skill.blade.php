@if (count($level->children) > 0)
    <tr>
        <td style="text-align: center">
            <p class="info-headings">{{ $level->title }}</p>
        </td>
    </tr>
    <tr>
        <td style="position: relative">
            <table style="height: -webkit-fill-available;"
                class="{{ $level->language == 'english' ? 'tbl-lang custom-lang-table' : 'tbl-urdu text_dir_rtl fade-bg' }}">
                @foreach ($level->children as $sub_level)
                    @include('assessment.grade_book.api_reports.api_partials.api_outer_skill', ['level' => $sub_level])
                @endforeach
                @if ($level->language == 'urdu')
                    <img src="{{ asset('assets/img/1x/new-kids4.png') }}" style="position:absolute; z-index:0;"
                        alt="" class="set-to-bottom2">
                @endif
            </table>
        </td>
    </tr>
@else
    <tr>
        <td style="vertical-align: baseline;">
            @if ($level->type == 'checkbox')
                <input type="checkbox" disabled class="w-30_h-20"
                    {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
            @else
                <input type="text" style="text-align:center; padding: 0px; font-size: 16px;" id="checkingssss" readonly class="w-30_h-20"
                    value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}">
            @endif
        </td>
        <td style="word-break: break-all">
            <p class="all-text">{{ $level->title }}</p>
        </td>
    </tr>
@endif

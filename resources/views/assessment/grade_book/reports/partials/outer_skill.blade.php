@if (count($level->children) > 0)

    <p class="info-headings">{{ $level->title }}</p>

    <table style="position: relative"
        class="{{ $level->language == 'english' ? 'tbl-lang custom-lang-table' : 'tbl-urdu text_dir_rtl fade-bg' }}">
        @foreach ($level->children as $sub_level)
            @include('assessment.grade_book.reports.partials.outer_skill', ['level' => $sub_level])
        @endforeach
        {{-- @if ($level->language == 'urdu')
            <img src="{{ asset('assets/img/1x/new-kids4.png') }}" style="position:absolute; z-index:0;" alt=""
                class="set-to-bottom2">
        @endif --}}
    </table>
@else
    <tr>
        <td style="vertical-align: baseline;height:0%">
            <div style="display: flex;">
                @if ($level->type == 'checkbox')
                    <input type="checkbox" disabled class="w-30_h-20"
                        {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
                @else
                    <input type="text" style="text-align:center;" readonly class="w-30_h-20"
                        value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}">
                @endif

                <p class="all-text" style="word-break: break-all;margin-left:10px">{{ $level->title }}</p>
            </div>
        </td>
    </tr>
@endif

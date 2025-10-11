<div class="list-group col nested-list">
    <div class="list-group-item">
        @if (count($level->children) > 0)
            {{-- <i class="mdi mdi-folder fs-16 align-middle text-warning me-2"></i> {{ $level->title }}
            {{ dd($level) }} --}}
            @if ($level->type == 'title')
                <i class="mdi mdi-folder fs-16 align-middle text-warning me-2"></i>{{ $level->title }}
            @elseif($level->type == 'checkbox')
                <div class="row">
                    <div class="col-sm-1">
                        <input type="checkbox" data-skill_id="{{ $level['id'] }}" class="pull-right skills"
                            {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
                    </div>
                    <div class="col-sm-11">
                        {{ $level->title }}
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-10">
                        {{ $level->title }}
                    </div>
                    <div class="col-sm-2">
                        {{-- <input type="text" data-skill_id="{{ $level['id'] }}"
                            class="pull-right form-control form-control-sm skills"
                            value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}"
                            placeholder="Enter Grade"> --}}
                            <select class="pull-right form-control form-select form-control-sm skills"
                            data-skill_id="{{ $level['id'] }}">
                            <option value="" selected>Enter Grade</option>
                            @foreach ($grading_keys as $grading_key)
                                <option
                                data-skill_id="{{ $level['id'] }}"
                                {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) && $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] == $grading_key->grading_key ? 'selected' : '' }}
                                value="{{ $grading_key->grading_key }}">{{ $grading_key->grading_key }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            @endif
            <div class="list-group nested-list">
                @foreach ($level->children as $sub_level)
                    @include('assessment.skill_behaviour.skill_levels', ['level' => $sub_level])
                @endforeach
            </div>
        @else
            @if ($level->type == 'title')
                <i class="mdi mdi-language-html5 fs-16 align-middle text-danger me-2"></i>{{ $level->title }}
            @elseif($level->type == 'checkbox')
                <div class="row">
                    <div class="col-sm-1">
                        <input type="checkbox" data-skill_id="{{ $level['id'] }}" class="pull-right skills"
                            {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? 'checked' : '' }}>
                    </div>
                    <div class="col-sm-11">
                        {{ $level->title }}
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-10">
                        {{ $level->title }}
                    </div>
                    <div class="col-sm-2">
                        {{-- <input type="text" data-skill_id="{{ $level['id'] }}"
                            class="pull-right form-control form-control-sm skills"
                            value="{{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) ? $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] : '' }}"
                            placeholder="Enter Grade"> --}}

                            <select class="pull-right form-control form-select form-control-sm skills"
                            data-skill_id="{{ $level['id'] }}">
                            <option value="" selected>Enter Grade</option>
                            @foreach ($grading_keys as $grading_key)
                                <option
                                data-skill_id="{{ $level['id'] }}"
                                {{ isset($student_skill['student_behaviour_skill_marks'][$level['id']]) && $student_skill['student_behaviour_skill_marks'][$level['id']]['grade'] == $grading_key->grading_key ? 'selected' : '' }}
                                value="{{ $grading_key->grading_key }}">{{ $grading_key->grading_key }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

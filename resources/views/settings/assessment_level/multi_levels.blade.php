<div class="list-group col nested-list">
    <div class="list-group-item">
        @if (count($level->children) > 0)
            <i class="mdi mdi-folder fs-16 align-middle text-warning me-2"></i> {{ $level->name}}
            <div class="list-group nested-list">
                @foreach ($level->children as $sub_level)
                    @include('settings.assessment_level.multi_levels',['level' => $sub_level])
                @endforeach
            </div>
        @else
            <i class="mdi mdi-language-html5 fs-16 align-middle text-danger me-2"></i>{{ $level->name}}
        @endif
    </div>
</div>

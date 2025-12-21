@props([
    'href',
    'isActive' => false,
    'dataKey' => null,
    'icon' => null,
])

<li class="nav-item">
    <a href="{{ $href }}"
        class="nav-link menu-link {{ $isActive ? 'active' : 'collapsed' }}"
        {{ $dataKey ? "data-key=\"{$dataKey}\"" : '' }}>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span {{ $dataKey ? "data-key=\"{$dataKey}\"" : '' }}>{{ $slot }}</span>
    </a>
</li>


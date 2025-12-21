@props([
    'href',
    'isActive' => false,
    'dataKey' => null,
])

<li class="nav-item">
    <a href="{{ $href }}"
        class="nav-link {{ $isActive ? 'active' : '' }}"
        {{ $dataKey ? "data-key=\"{$dataKey}\"" : '' }}>
        {{ $slot }}
    </a>
</li>


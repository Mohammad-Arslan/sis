@props([
    'id',
    'routes' => [],
    'icon' => null,
    'dataKey' => null,
    'label',
    'linkClass' => '',
])

@php
    $isActive = !empty($routes) && Request::is($routes);
    $isExpanded = $isActive ? 'true' : 'false';
    $activeClass = $isActive ? 'active' : 'collapsed';
@endphp

<li class="nav-item">
    <a class="nav-link menu-link {{ $activeClass }} {{ $linkClass }}"
        href="#{{ $id }}"
        data-bs-toggle="collapse"
        role="button"
        aria-expanded="{{ $isExpanded }}"
        aria-controls="{{ $id }}">
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        @if($dataKey)
            <span data-key="{{ $dataKey }}">{{ $label }}</span>
        @else
            {{ $label }}
        @endif
    </a>
    <div class="collapse menu-dropdown {{ $isActive ? 'show' : '' }}" id="{{ $id }}">
        <ul class="nav nav-sm flex-column">
            {{ $slot }}
        </ul>
    </div>
</li>


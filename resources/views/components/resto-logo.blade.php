@props([
    'size' => 'md',
    'variant' => 'dark',
])

@php
    $sizeClasses = match ($size) {
        'xs' => 'h-7 w-auto sm:h-8',
        'nav' => 'h-9 w-auto sm:h-10',
        'menu-header' => 'h-10 w-auto sm:h-11',
        'sm' => 'h-9 w-auto md:h-10',
        'md' => 'h-12 w-auto md:h-14',
        'lg' => 'h-14 w-auto md:h-16',
        'xl' => 'h-16 w-auto md:h-20',
        'hero' => 'h-24 w-auto md:h-28 lg:h-32',
        'receipt' => 'h-12 w-auto',
        'sidebar' => 'h-14 w-auto',
        default => 'h-12 w-auto md:h-14',
    };

    $source = $variant === 'light'
        ? 'storage/logo-white.png'
        : 'storage/logo.png';
@endphp

<img
    src="{{ asset($source) }}"
    alt="Sari Rasa"
    {{ $attributes->merge(['class' => "object-contain {$sizeClasses}"]) }}
    loading="eager"
    decoding="async"
/>

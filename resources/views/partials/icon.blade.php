@props(['name'])
<svg {{ $attributes->class(['icon']) }} aria-hidden="true" focusable="false"><use href="/icons/sprite.svg#i-{{ $name }}"></use></svg>
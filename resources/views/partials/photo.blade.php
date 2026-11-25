<picture {{ $attributes }}>
    @foreach ($sources() as $type => $srcset)
        <source type="{{ $type }}" sizes="{{ $sizes }}" srcset="{{ $srcset }}">
    @endforeach
    <img class="{{ $imageClass }}" src="{{ $fallback() }}" alt="{{ $alt }}" width="800" height="600" loading="lazy" decoding="async">
</picture>

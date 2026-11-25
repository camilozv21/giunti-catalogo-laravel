@props(['id', 'title', 'lead' => null, 'tone' => 'dark'])
<div {{ $attributes->class(['section-heading', 'section-heading--light' => $tone === 'light']) }}>
    <h2 id="{{ $id }}" class="section-heading__title reveal-rise">{{ $title }}</h2>
    @if ($lead)
        <p class="section-heading__lead reveal-rise">{{ $lead }}</p>
    @endif
</div>

<section id="top" class="hero on-dark" aria-labelledby="hero-title">
    <picture class="hero__picture">
        {{-- The photo covers the section: on screens narrower than the image, the viewport height sets its width. --}}
        <source media="(max-aspect-ratio: 3/4)" type="image/webp" sizes="(max-aspect-ratio: 9/16) 56.25vh, 100vw" srcset="{{ $hero->portraitSrcset() }}">
        <source type="image/webp" sizes="(max-aspect-ratio: 16/9) 177.7vh, 100vw" srcset="{{ $hero->landscapeSrcset() }}">
        <img class="hero__photo hero-photo" src="/images/hero/hero-1600.jpg" alt="{{ __('ui.hero.image.alt') }}" width="1600" height="900" fetchpriority="high" decoding="async">
    </picture>
    <div class="hero-shade" aria-hidden="true"></div>
    <div class="container hero__inner">
        <h1 id="hero-title" class="hero__title">
            <span class="hero-title__line">@foreach ($hero->firstLine() as $i => $word)@if ($i > 0) @endif<span class="hero-title__word" style="--i: {{ $i }}">{{ $word }}</span>@endforeach</span>
            <span class="hero-title__line"><span class="hero-mark">@foreach ($hero->secondLine() as $i => $word)@if ($i > 0) @endif<span class="hero-title__word" style="--i: {{ count($hero->firstLine()) + $i }}">{{ $word }}</span>@endforeach</span></span>
        </h1>
        <p class="hero__lead rise-in" style="--d: 380ms">{{ __('ui.hero.lead') }}</p>
        <div class="hero__actions rise-in" style="--d: 500ms">
            <a href="#catalogo" class="btn btn--accent btn--lg cta-flood">
                {{ __('ui.hero.ctaPrimary') }}
                <x-icon name="arrow-down" class="icon--large" />
            </a>
        </div>
    </div>
</section>

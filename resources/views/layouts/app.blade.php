<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-scroll-behavior="smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1B4F8A">
    <meta name="color-scheme" content="light">
    <title>{{ __('ui.meta.title') }}</title>
    <meta name="description" content="{{ __('ui.meta.description') }}">
    <meta name="application-name" content="{{ __('ui.meta.siteName') }}">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="robots" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="{{ $seo->siteUrl }}">
    <link rel="alternate" hreflang="it-IT" href="{{ $seo->siteUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seo->siteUrl }}">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:title" content="{{ __('ui.meta.title') }}">
    <meta property="og:description" content="{{ __('ui.meta.description') }}">
    <meta property="og:url" content="{{ $seo->siteUrl }}">
    <meta property="og:site_name" content="{{ __('ui.meta.siteName') }}">
    <meta property="og:locale" content="it_IT">
    <meta property="og:image" content="{{ $seo->socialImageUrl }}">
    <meta property="og:image:width" content="{{ $seo->socialImageWidth() }}">
    <meta property="og:image:height" content="{{ $seo->socialImageHeight() }}">
    <meta property="og:image:alt" content="{{ __('ui.meta.ogImageAlt') }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('ui.meta.title') }}">
    <meta name="twitter:description" content="{{ __('ui.meta.description') }}">
    <meta name="twitter:image" content="{{ $seo->socialImageUrl }}">
    <meta name="twitter:image:alt" content="{{ __('ui.meta.ogImageAlt') }}">
    <meta name="twitter:image:width" content="{{ $seo->socialImageWidth() }}">
    <meta name="twitter:image:height" content="{{ $seo->socialImageHeight() }}">
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script type="application/ld+json">{!! $seo->jsonLd() !!}</script>
    <link rel="preload" href="/fonts/inter-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/fraunces-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>
    {{-- One stylesheet for one page: inlining it removes the render-blocking request. --}}
    <style>{!! file_get_contents(public_path('css/app.css')) !!}</style>
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('head')
</head>
<body>
    @include('partials.skip-link')
    @include('sections.header')

    <main id="contenuto" tabindex="-1" class="main">
        @yield('content')
    </main>

    @include('sections.footer')
    @include('partials.cart-drawer')

    <div class="sr-only" aria-live="polite" aria-atomic="true" data-announcer></div>
    @stack('scripts')
</body>
</html>

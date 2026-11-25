<header class="site-header">
    <div class="site-header__inner">
        <a href="#top" class="site-header__brand" aria-label="{{ __('ui.brand.homeLabel') }}">
            @include('partials.logo')
        </a>

        <nav class="site-header__nav" aria-label="{{ __('ui.nav.label') }}">
            @include('partials.nav-links', ['variant' => 'header'])
        </nav>

        <div class="site-header__search">
            @include('partials.search')
        </div>

        <div class="site-header__actions">
            @include('partials.login')
            @include('partials.cart-button')
            <div class="site-header__menu">
                @include('partials.mobile-menu')
            </div>
        </div>
    </div>
</header>

<footer class="site-footer on-dark">
    <div class="container site-footer__intro">
        <p class="mega-tagline site-footer__tagline" aria-hidden="true">{{ __('ui.hero.titleStart') }} {{ __('ui.hero.titleMark') }}</p>
        @include('partials.trust-strip')
    </div>
    <div class="container site-footer__main">
        <div>
            @include('partials.logo', ['tone' => 'light'])
            <p class="site-footer__description">{{ __('ui.footer.description') }}</p>
        </div>
        <nav aria-label="{{ __('ui.footer.navLabel') }}">
            @include('partials.nav-links', ['variant' => 'footer'])
        </nav>
        <div>
            <p class="site-footer__social-title">{{ __('ui.footer.socialLabel') }}</p>
            <ul class="site-footer__social">
                @foreach (config('site.social') as $network)
                    <li>
                        <a href="#" class="site-footer__social-link"
                           aria-label="{{ __("ui.footer.social.{$network}") }} {{ __('ui.footer.socialPlaceholder') }}">
                            <x-icon :name="$network" class="icon--large" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container site-footer__legal">
            <p>{{ __('ui.footer.copyright', ['year' => config('site.year')]) }}</p>
            <p class="site-footer__demo">{{ __('ui.footer.demo') }}</p>
        </div>
    </div>
</footer>

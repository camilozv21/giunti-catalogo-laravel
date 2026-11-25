<button type="button" class="btn btn--ghost btn--icon" aria-label="{{ __('ui.nav.menuOpen') }}"
        aria-haspopup="dialog" aria-expanded="false" aria-controls="mobile-menu" data-menu-open>
    <x-icon name="menu" class="icon--large" />
</button>
<dialog id="mobile-menu" class="mobile-menu" aria-labelledby="mobile-menu-title" data-menu>
    <div class="mobile-menu__inner">
        <div class="mobile-menu__head">
            <h2 id="mobile-menu-title" class="mobile-menu__title">{{ __('ui.nav.menuTitle') }}</h2>
            <button type="button" class="btn btn--ghost btn--icon" aria-label="{{ __('ui.nav.menuClose') }}" data-menu-close>
                <x-icon name="x" class="icon--large" />
            </button>
        </div>
        <nav aria-label="{{ __('ui.nav.label') }}">
            @include('partials.nav-links', ['variant' => 'menu'])
        </nav>
    </div>
</dialog>

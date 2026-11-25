<span class="logo{{ ($tone ?? 'dark') === 'light' ? ' logo--light' : '' }}">
    <img class="logo__mark" src="/images/brand/mark.svg" alt="" width="36" height="36">
    <span class="logo__text" translate="no"><span class="logo__first">{{ Str::before(__('ui.brand.name'), ' ') }}</span> <span class="logo__rest">{{ Str::after(__('ui.brand.name'), ' ') }}</span></span>
</span>

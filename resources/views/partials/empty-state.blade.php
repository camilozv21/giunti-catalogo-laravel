<div class="empty" hidden data-catalog-empty>
    <span class="empty__icon"><x-icon name="search" class="icon--large" /></span>
    <h3 class="empty__title" data-catalog-empty-title>{{ __('ui.catalog.empty.titleNoQuery') }}</h3>
    <p class="empty__text">{{ __('ui.catalog.empty.text') }}</p>
    <div class="empty__suggestions">
        <span class="empty__suggestions-label">{{ __('ui.catalog.empty.suggestions') }}</span>
        @foreach (__('ui.catalog.empty.examples') as $example)
            <button type="button" class="empty__chip" data-suggestion="{{ $example }}">{{ $example }}</button>
        @endforeach
    </div>
    <button type="button" class="btn btn--secondary btn--md empty__reset" data-catalog-reset>{{ __('ui.catalog.reset') }}</button>
</div>

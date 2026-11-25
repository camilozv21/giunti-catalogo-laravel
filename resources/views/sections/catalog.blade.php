<section id="catalogo" class="catalog" aria-labelledby="catalog-title">
    <div class="container">
        <x-section-heading id="catalog-title" :title="__('ui.catalog.title')" :lead="__('ui.catalog.lead')" />

        <div class="catalog__browser" data-catalog>
            <div id="catalogo-risultati" class="catalog__toolbar">
                <div class="catalog__controls">
                    @include('partials.filter')

                    <form class="age-field" data-age-field>
                        <label for="catalog-age" class="age-field__label">{{ __('ui.catalog.age.label') }}</label>
                        {{-- type="text" with a numeric keypad: type="number" would hide input that is not a number. --}}
                        <input id="catalog-age" class="age-field__input" name="eta" type="text" inputmode="numeric" enterkeyhint="done" autocomplete="off" maxlength="3" aria-describedby="catalog-age-unit" data-age-input>
                        <span id="catalog-age-unit" class="age-field__unit">{{ __('ui.catalog.age.unit') }}</span>
                    </form>
                </div>

                <div class="catalog__summary">
                    <p id="catalog-status" role="status" class="catalog__status" data-catalog-status>{{ trans_choice('ui.catalog.count', $catalog->count()) }}</p>
                    <p class="catalog__vat">{{ __('ui.catalog.vat') }}</p>
                    <button type="button" class="btn btn--link btn--md catalog__reset" hidden data-catalog-reset>
                        <x-icon name="x" />
                        {{ __('ui.catalog.reset') }}
                    </button>
                </div>
            </div>

            <ul role="list" class="catalog__grid" data-grid>
                @foreach ($catalog->products() as $product)
                    <li class="catalog__item"
                        data-product-id="{{ $product->id }}"
                        data-category="{{ $product->category->value }}"
                        data-kind="{{ $product->kind()->value }}"
                        @if ($product->isTest()) data-age-min="{{ $product->ageMin }}" data-age-max="{{ $product->ageMax }}" @endif
                        data-search="{{ $product->searchText() }}"
                        style="view-transition-name: card-{{ $product->id }}">
                        <x-product-card :product="$product" />
                    </li>
                @endforeach
            </ul>

            @include('partials.empty-state')
        </div>
    </div>
</section>

<form role="search" class="search" data-search-form>
    <label for="site-search" class="sr-only">{{ __('ui.search.label') }}</label>
    <x-icon name="search" class="search__icon" />
    <input id="site-search" class="search__input" type="search" name="q"
           role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="search-results"
           placeholder="{{ __('ui.search.placeholder') }}" autocomplete="off" enterkeyhint="search" spellcheck="false"
           data-search-input>
    <button type="button" class="search__clear" aria-label="{{ __('ui.search.clear') }}" hidden data-search-clear>
        <x-icon name="x" />
    </button>

    <div class="search-popup" hidden data-search-popup>
        <ul id="search-results" role="listbox" aria-label="{{ __('ui.search.results') }}" class="search__results">
            @foreach ($catalog->products() as $product)
                <li id="search-option-{{ $product->id }}" role="option" aria-selected="false" hidden class="search__option"
                    data-search-option="{{ $product->id }}" data-search-text="{{ $product->searchText() }}">
                    <span class="search__thumb">
                        <picture>
                            <source type="image/avif" srcset="/images/photos/{{ $product->image->stem() }}-96.avif">
                            <source type="image/webp" srcset="/images/photos/{{ $product->image->stem() }}-96.webp">
                            <img src="/images/photos/{{ $product->image->stem() }}-96.jpg" alt="" width="48" height="36" loading="lazy" decoding="async">
                        </picture>
                    </span>
                    <span class="search__text">
                        <span class="search__name">{{ $product->name }}</span>
                        <span class="search__meta"><span translate="no">{{ $product->code }}</span> · {{ $catalog->categoryLabel($product->category) }}</span>
                    </span>
                    <span class="search__price">{{ $product->salePrice->format() }}</span>
                </li>
            @endforeach
        </ul>
        <p class="search__none" hidden data-search-none></p>
    </div>
</form>

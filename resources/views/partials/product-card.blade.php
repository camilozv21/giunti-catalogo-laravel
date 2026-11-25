@props(['product'])
<article class="product-card" aria-labelledby="product-{{ $product->id }}-title" tabindex="-1">
    <div class="product-card__media">
        <x-photo class="product-card__picture" :stem="$product->image->stem()" :alt="$product->image->alt" image-class="product-card__image card-photo"
                 sizes="(min-width: 80rem) 400px, (min-width: 64rem) calc((100vw - 7rem) / 3), (min-width: 48rem) calc((100vw - 4.5rem) / 2), calc(100vw - 2rem)" />
        <span class="product-card__discount">{{ __('ui.card.discount', ['percent' => $product->discountPercent()]) }}</span>
        <x-format-badge :format="$product->format" class="product-card__badge" />
    </div>

    <div class="product-card__head">
        <h3 id="product-{{ $product->id }}-title" class="product-card__title">
            <span class="product-card__code" translate="no">{{ $product->code }}</span><span class="sr-only"> – </span><span class="product-card__name">{{ $product->name }}</span>
        </h3>
        <p class="product-card__author" title="{{ $product->byline() }}">{{ $product->byline() }}</p>
    </div>

    <div class="product-card__details">
        <x-product-meta :product="$product" />
    </div>

    <div class="product-card__purchase">
        <x-price :product="$product" />
        <button type="button" class="btn btn--secondary btn--md product-card__add" data-add-to-cart="{{ $product->id }}" data-name="{{ $product->code }}" data-state="idle">
            <x-icon name="shopping-bag" data-add-icon />
            <span data-add-label>{{ __('ui.card.addShort') }}</span>
            {{-- The visible text must start the accessible name (WCAG 2.5.3). --}}
            <span class="sr-only">{{ __('ui.card.addSuffix', ['name' => $product->code]) }}</span>
        </button>
    </div>
</article>

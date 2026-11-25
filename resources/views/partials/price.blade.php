@props(['product'])
<p class="price">
    <span class="sr-only">{{ __('ui.card.priceSale') }}</span>
    <span class="price__sale" data-price="sale">{{ $product->salePrice->format() }}</span>
    <span class="sr-only">{{ __('ui.card.priceOriginal') }}</span>
    <del class="price__original">{{ $product->price->format() }}</del>
</p>

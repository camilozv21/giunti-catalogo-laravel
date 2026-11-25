<dialog id="cart-drawer" class="drawer" aria-labelledby="cart-title" data-cart-drawer>
    {{-- The page behind a modal dialog is inert, so the drawer needs its own live region. --}}
    <div class="sr-only" aria-live="polite" aria-atomic="true" data-announcer></div>
    <div class="drawer__inner">
        <div class="drawer__head">
            <div>
                <h2 id="cart-title" class="drawer__title">{{ __('ui.cart.title') }}</h2>
                <p class="drawer__count" role="status" data-cart-items>{{ trans_choice('ui.cart.items', 0) }}</p>
            </div>
            <button type="button" class="btn btn--ghost btn--icon" aria-label="{{ __('ui.cart.close') }}" data-cart-close>
                <x-icon name="x" class="icon--large" />
            </button>
        </div>

        <div class="drawer__empty" data-cart-empty>
            <span class="drawer__empty-icon"><x-icon name="shopping-bag" class="icon--large" /></span>
            <p class="drawer__empty-title">{{ __('ui.cart.emptyTitle') }}</p>
            <p class="drawer__empty-text">{{ __('ui.cart.emptyText') }}</p>
            <button type="button" class="btn btn--secondary btn--md drawer__empty-cta" data-cart-goto="catalogo" data-cart-empty-cta>{{ __('ui.cart.emptyCta') }}</button>
        </div>

        <ul class="drawer__lines" hidden data-cart-lines></ul>

        <div class="drawer__footer" hidden data-cart-footer>
            <dl class="drawer__totals">
                <div class="drawer__row">
                    <dt class="drawer__label">{{ __('ui.cart.subtotal') }}</dt>
                    <dd class="drawer__amount" data-cart-subtotal></dd>
                </div>
                <div class="drawer__row">
                    <dt class="drawer__label">{{ __('ui.cart.saving') }}</dt>
                    <dd class="drawer__amount drawer__amount--saving" data-cart-saving></dd>
                </div>
                <div class="drawer__row drawer__row--total">
                    <dt class="drawer__total-label">{{ __('ui.cart.total') }}</dt>
                    <dd class="drawer__amount drawer__amount--total" data-cart-total></dd>
                </div>
            </dl>
            <p class="drawer__note" data-cart-note></p>
            <p class="drawer__note" hidden data-cart-qualification>{{ __('ui.cart.qualificationNote') }}</p>
            <button type="button" class="btn btn--primary btn--md btn--block" data-cart-quote>
                <x-icon name="mail" />
                {{ __('ui.cart.quote') }}
            </button>
            <button type="button" class="btn btn--secondary btn--md btn--block" data-cart-close>{{ __('ui.cart.continue') }}</button>
        </div>
    </div>

    <template data-cart-line-template>
        <li class="cart-line">
            <div>
                <p class="cart-line__code" translate="no" data-line-code></p>
                <p class="cart-line__name" data-line-name></p>
            </div>
            <div class="cart-line__row">
                <div class="stepper" role="group" aria-label="{{ __('ui.cart.quantity') }}">
                    <button type="button" class="stepper__button" data-line-decrease><x-icon name="minus" /></button>
                    <span class="stepper__value" data-line-quantity></span>
                    <button type="button" class="stepper__button" data-line-increase><x-icon name="plus" /></button>
                </div>
                <p class="cart-line__total" data-line-total></p>
            </div>
            <button type="button" class="cart-line__remove" data-line-remove>
                <x-icon name="trash" />
                {{ __('ui.cart.remove') }}
            </button>
        </li>
    </template>

    <script type="application/json" data-cart-products>@json($catalog->cartItems())</script>
</dialog>

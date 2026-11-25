import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { fill, plural } from '../utils/copy.mjs';
import { formatPrice } from '../utils/format.mjs';
import { scrollToElement } from '../utils/motion.mjs';
import { announce } from './announcer.mjs';
import { closeOnBackdropClick } from './dialog.mjs';

const ADDED_FEEDBACK_MS = 1800;
const FOCUS_DELAY_MS = 50;

function showAddedState($button, isAdded) {
    $button.attr('data-state', isAdded ? 'added' : 'idle');
    $button.find('[data-add-label]').text(isAdded ? copy.card.added : copy.card.addShort);
    $button.find('[data-add-icon] use').attr('href', `/icons/sprite.svg#i-${isAdded ? 'check' : 'shopping-bag'}`);
}

function restartPulse($count) {
    $count.removeClass('is-pulsing');
    void $count[0].offsetWidth; // forces a reflow so the animation starts again
    $count.addClass('is-pulsing');
}

function lineIdOf(element) {
    return $(element).closest('[data-line-id]').attr('data-line-id');
}

export function initCart() {
    const $cartButton = $('[data-cart-button]');
    const $count = $cartButton.find('[data-cart-count]');
    const $drawer = $('[data-cart-drawer]');
    if (!$drawer.length) return;
    const drawer = $drawer[0];
    const products = JSON.parse($drawer.find('[data-cart-products]').text());
    const template = $drawer.find('[data-cart-line-template]')[0];
    const $lines = $drawer.find('[data-cart-lines]');
    const quantities = new Map();
    const feedbackTimers = new Map();

    function computeTotals() {
        const totals = { count: 0, subtotal: 0, saving: 0, total: 0, ecm: 0, hasTests: false };
        quantities.forEach((quantity, id) => {
            const product = products[id];
            totals.count += quantity;
            totals.subtotal += product.priceCents * quantity;
            totals.total += product.salePriceCents * quantity;
            totals.saving += (product.priceCents - product.salePriceCents) * quantity;
            totals.ecm += (product.ecm || 0) * quantity;
            totals.hasTests = totals.hasTests || product.kind === 'test';
        });
        return totals;
    }

    function createLine(id) {
        const product = products[id];
        const $line = $(template.content.firstElementChild.cloneNode(true)).attr('data-line-id', id);
        $line.find('[data-line-code]').text(product.code);
        $line.find('[data-line-name]').text(product.name);
        $line.find('[data-line-decrease]').attr('aria-label', fill(copy.cart.decrease, { name: product.code }));
        $line.find('[data-line-increase]').attr('aria-label', fill(copy.cart.increase, { name: product.code }));
        $line.find('[data-line-remove]').attr('aria-label', fill(copy.cart.removeLabel, { name: product.code }));
        return $line;
    }

    function renderCount(count) {
        $cartButton.attr('aria-label', plural(copy.cart.count, count));
        $count.text(String(count)).prop('hidden', count === 0);
        $drawer.find('[data-cart-items]').text(plural(copy.cart.items, count));
        $drawer.find('[data-cart-empty]').prop('hidden', count > 0);
        $lines.prop('hidden', count === 0);
        $drawer.find('[data-cart-footer]').prop('hidden', count === 0);
    }

    // Keyed, in-place update: keyboard focus stays on the stepper button that was pressed.
    function renderLines() {
        $lines.children('[data-line-id]').each((_, line) => {
            if (!quantities.has($(line).attr('data-line-id'))) $(line).remove();
        });
        quantities.forEach((quantity, id) => {
            let $line = $lines.children(`[data-line-id="${id}"]`);
            if (!$line.length) $line = createLine(id).appendTo($lines);
            $line.find('[data-line-quantity]').text(String(quantity));
            $line.find('[data-line-total]').text(formatPrice(products[id].salePriceCents * quantity));
        });
    }

    function renderTotals(totals) {
        const note = totals.ecm > 0 ? `${copy.cart.vat} · ${fill(copy.cart.ecmTotal, { count: totals.ecm })}` : copy.cart.vat;
        $drawer.find('[data-cart-subtotal]').text(formatPrice(totals.subtotal));
        $drawer.find('[data-cart-saving]').text(`−${formatPrice(totals.saving)}`);
        $drawer.find('[data-cart-total]').text(formatPrice(totals.total));
        $drawer.find('[data-cart-note]').text(note);
        $drawer.find('[data-cart-qualification]').prop('hidden', !totals.hasTests);
    }

    function render() {
        const totals = computeTotals();
        renderCount(totals.count);
        renderLines();
        renderTotals(totals);
    }

    // The pressed button is gone: focus the next line, the previous one, or the empty state.
    function focusAfterRemoval(removedIndex) {
        const $removeButtons = $lines.find('[data-line-remove]');
        if (!$removeButtons.length) {
            $drawer.find('[data-cart-empty-cta]').trigger('focus');
            return;
        }
        $removeButtons.eq(Math.min(removedIndex, $removeButtons.length - 1)).trigger('focus');
    }

    function removeLine(id) {
        const index = Array.from(quantities.keys()).indexOf(id);
        quantities.delete(id);
        render();
        announce(fill(copy.cart.removed, { name: products[id].code }));
        focusAfterRemoval(index);
    }

    function setQuantity(id, quantity) {
        if (quantity <= 0) {
            removeLine(id);
            return;
        }
        quantities.set(id, quantity);
        render();
    }

    function addToCart($button) {
        const id = $button.attr('data-add-to-cart');
        quantities.set(id, (quantities.get(id) || 0) + 1);
        render();
        restartPulse($count);
        showAddedState($button, true);
        window.clearTimeout(feedbackTimers.get(id));
        feedbackTimers.set(id, window.setTimeout(() => showAddedState($button, false), ADDED_FEEDBACK_MS));
        announce(fill(copy.cart.added, { name: $button.attr('data-name') }));
    }

    function quoteMessage() {
        const rows = Array.from(quantities, ([id, quantity]) => {
            const product = products[id];
            return `- ${quantity} × ${product.code} – ${product.name} (${formatPrice(product.salePriceCents * quantity)})`;
        });
        const total = `${copy.cart.total}: ${formatPrice(computeTotals().total)} (${copy.cart.vat})`;
        return [copy.cart.quoteMessage, ...rows, total].join('\n');
    }

    function requestQuote() {
        $(document).trigger('contact:prefill', [quoteMessage()]);
        drawer.close();
        scrollToElement(document.getElementById('contatti'));
        window.setTimeout(() => document.getElementById('contact-name')?.focus({ preventScroll: true }), FOCUS_DELAY_MS);
    }

    const stepQuantity = (step) => (event) => {
        const id = lineIdOf(event.currentTarget);
        setQuantity(id, quantities.get(id) + step);
    };

    $(document).on('click', '[data-add-to-cart]', (event) => addToCart($(event.currentTarget)));

    $cartButton.on('click', () => drawer.showModal());
    $drawer.on('click', '[data-cart-close]', () => drawer.close());
    closeOnBackdropClick($drawer);
    $drawer.on('click', '[data-cart-goto]', (event) => {
        drawer.close();
        scrollToElement(document.getElementById($(event.currentTarget).attr('data-cart-goto')));
    });
    $drawer.on('click', '[data-cart-quote]', requestQuote);

    $lines.on('click', '[data-line-decrease]', stepQuantity(-1));
    $lines.on('click', '[data-line-increase]', stepQuantity(1));
    $lines.on('click', '[data-line-remove]', (event) => setQuantity(lineIdOf(event.currentTarget), 0));

    render();
}

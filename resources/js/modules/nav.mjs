import $ from 'jquery/slim';
import { ALL_CATEGORIES } from '../utils/filters.mjs';
import { closeOnBackdropClick } from './dialog.mjs';
import { getFilters, setFilters } from './filter-state.mjs';

// The menu button is hidden from this width: an open menu must not keep the widened page inert.
const MENU_HIDDEN_QUERY = '(min-width: 64rem)';

function applyNavCategory(category) {
    if (category === 'formazione') setFilters({ category: 'formazione' });
    else if (category === 'test' && getFilters().category === 'formazione') setFilters({ category: ALL_CATEGORIES });
}

function initMobileMenu() {
    const $dialog = $('[data-menu]');
    if (!$dialog.length) return;
    const dialog = $dialog[0];
    const $open = $('[data-menu-open]');

    $open.on('click', () => {
        dialog.showModal();
        $open.attr('aria-expanded', 'true');
    });
    $dialog.on('close', () => $open.attr('aria-expanded', 'false'));
    window.matchMedia(MENU_HIDDEN_QUERY).addEventListener('change', (event) => {
        if (event.matches) dialog.close();
    });
    $dialog.on('click', '[data-menu-close], .nav-links__link', () => dialog.close());
    closeOnBackdropClick($dialog);
}

export function initNav() {
    $(document).on('click', '[data-nav-category]', (event) => applyNavCategory($(event.currentTarget).attr('data-nav-category')));
    initMobileMenu();
}

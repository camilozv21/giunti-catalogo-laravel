import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { fill } from '../utils/copy.mjs';
import { ALL_CATEGORIES } from '../utils/filters.mjs';
import { scrollToElement } from '../utils/motion.mjs';
import { normalize } from '../utils/normalize.mjs';
import { scrollToResults } from './catalog/index.mjs';
import { onFiltersChange, setFilters } from './filter-state.mjs';

const HIGHLIGHT_MS = 1600;

function revealCard(id) {
    const card = document.querySelector(`[data-product-id="${id}"] .product-card`);
    if (!card) return;
    scrollToElement(card, 'center');
    card.focus({ preventScroll: true });
    card.setAttribute('data-highlight', '');
    window.setTimeout(() => card.removeAttribute('data-highlight'), HIGHLIGHT_MS);
}

function readOptions($form) {
    return $form.find('[data-search-option]').map((_, element) => ({
        element,
        id: $(element).attr('data-search-option'),
        text: normalize($(element).attr('data-search-text') || ''),
    })).get();
}

export function initSearch() {
    const $form = $('[data-search-form]');
    if (!$form.length) return;
    const $input = $form.find('[data-search-input]');
    const $clear = $form.find('[data-search-clear]');
    const $popup = $form.find('[data-search-popup]');
    const $none = $form.find('[data-search-none]');
    const options = readOptions($form);

    let isOpen = false;
    let activeIndex = -1;
    let matches = [];

    const currentQuery = () => String($input.val());

    function render() {
        const query = currentQuery();
        const needle = normalize(query);
        matches = needle ? options.filter((option) => option.text.includes(needle)) : [];
        const isExpanded = isOpen && matches.length > 0;
        options.forEach((option) => {
            const index = matches.indexOf(option);
            $(option.element).prop('hidden', index < 0).attr('aria-selected', String(index === activeIndex));
        });
        $none.prop('hidden', matches.length > 0).text(fill(copy.search.none, { query: query.trim() }));
        $popup.prop('hidden', !isOpen || !needle);
        $input.attr('aria-expanded', String(isExpanded));
        if (isExpanded && activeIndex >= 0) $input.attr('aria-activedescendant', matches[activeIndex].element.id);
        else $input.removeAttr('aria-activedescendant');
    }

    function setOpen(value) {
        isOpen = value;
        render();
    }

    function closeList() {
        activeIndex = -1;
        setOpen(false);
    }

    function selectProduct(id) {
        closeList();
        // A category or age filter may hide the card: keep the query, clear the rest.
        if ($(`[data-product-id="${id}"]`).prop('hidden')) setFilters({ category: ALL_CATEGORIES, age: null });
        window.requestAnimationFrame(() => revealCard(id));
    }

    function moveActive(event, step) {
        if (!matches.length) return;
        event.preventDefault();
        activeIndex = (activeIndex + step + matches.length) % matches.length;
        setOpen(true);
    }

    function selectActive(event) {
        if (!isOpen || activeIndex < 0) return;
        event.preventDefault();
        selectProduct(matches[activeIndex].id);
    }

    // The first Escape closes the list, the next one clears the query.
    function handleEscape(event) {
        if (isOpen && normalize(currentQuery())) {
            event.preventDefault();
            closeList();
            return;
        }
        if (!$input.val()) return;
        event.preventDefault();
        setFilters({ query: '' });
    }

    function syncWithFilters(filters) {
        if ($input.val() !== filters.query) $input.val(filters.query);
        $clear.prop('hidden', filters.query === '');
        render();
    }

    $input.on('input', () => {
        setFilters({ query: currentQuery() });
        activeIndex = -1;
        setOpen(true);
    });
    $input.on('focus', () => setOpen(true));
    $input.on('blur', () => setOpen(false));
    $input.on('keydown', (event) => {
        if (event.key === 'ArrowDown') moveActive(event, 1);
        else if (event.key === 'ArrowUp') moveActive(event, -1);
        else if (event.key === 'Enter') selectActive(event);
        else if (event.key === 'Escape') handleEscape(event);
    });

    // Focus stays in the input, which drives the list.
    $popup.on('mousedown', '[data-search-option]', (event) => event.preventDefault());
    $popup.on('mousemove', '[data-search-option]', (event) => {
        const index = matches.findIndex((option) => option.element === event.currentTarget);
        if (index === activeIndex) return;
        activeIndex = index;
        render();
    });
    $popup.on('click', '[data-search-option]', (event) => selectProduct($(event.currentTarget).attr('data-search-option')));

    $clear.on('click', () => {
        setFilters({ query: '' });
        $input.trigger('focus');
    });

    $form.on('submit', (event) => {
        event.preventDefault();
        setOpen(false);
        scrollToResults();
    });

    onFiltersChange(syncWithFilters);
}

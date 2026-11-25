import $ from 'jquery/slim';
import { ALL_CATEGORIES } from '../../utils/filters.mjs';
import { scrollToElement, withViewTransition } from '../../utils/motion.mjs';
import { onFiltersChange, resetFilters, setFilters } from '../filter-state.mjs';
import { createAgeField } from './age-field.mjs';
import { createCategoryFilter } from './category-filter.mjs';
import { createEmptyState, createGrid, createSummary } from './results.mjs';

export function scrollToResults() {
    scrollToElement(document.getElementById('catalogo-risultati'));
}

export function initCatalog() {
    const $root = $('[data-catalog]');
    if (!$root.length) return;

    const categoryFilter = createCategoryFilter($root);
    const ageField = createAgeField($root, render);
    const grid = createGrid($root);
    const summary = createSummary($root, categoryFilter.labelOf);
    const emptyState = createEmptyState($root);

    function render(filters) {
        ageField.render(filters);
        const visibleCount = grid.render(filters);
        categoryFilter.render(filters);
        summary.render(filters, visibleCount, ageField.isInvalid());
        emptyState.render(filters, visibleCount);
    }

    $root.on('click', '[data-catalog-reset]', () => {
        withViewTransition(resetFilters);
        categoryFilter.focusChecked();
    });
    $root.on('click', '[data-suggestion]', (event) => {
        const query = $(event.currentTarget).attr('data-suggestion');
        withViewTransition(() => setFilters({ query, category: ALL_CATEGORIES, age: null }));
        categoryFilter.focusChecked();
    });

    onFiltersChange(render);
}

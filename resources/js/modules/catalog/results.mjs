import $ from 'jquery/slim';
import copy from '../../../data/copy.it.json';
import { fill, plural } from '../../utils/copy.mjs';
import { ALL_CATEGORIES, isFiltered, matchesFilters } from '../../utils/filters.mjs';
import { readCatalogItems } from './items.mjs';

function countText(filters, count) {
    const query = filters.query.trim();
    if (query) return plural(copy.catalog.results, count, { query });
    if (filters.age !== null) return plural(copy.catalog.testsForAge, count, { age: filters.age });
    return plural(copy.catalog.count, count);
}

function statusText(filters, count, categoryLabel) {
    const text = countText(filters, count);
    if (filters.category === ALL_CATEGORIES) return text;
    return `${text} ${fill(copy.catalog.inCategory, { category: categoryLabel })}`;
}

export function createGrid($root) {
    const $items = $root.find('[data-product-id]');
    const items = readCatalogItems($items);

    return {
        render(filters) {
            let visibleCount = 0;
            $items.each((index, element) => {
                const isMatch = matchesFilters(items[index], filters);
                $(element).prop('hidden', !isMatch);
                if (isMatch) visibleCount += 1;
            });
            return visibleCount;
        },
    };
}

export function createSummary($root, labelOf) {
    const $status = $root.find('[data-catalog-status]');
    const $reset = $root.find('.catalog__reset');

    return {
        render(filters, visibleCount, isAgeInvalid) {
            $status.text(isAgeInvalid ? copy.catalog.age.invalid : statusText(filters, visibleCount, labelOf(filters.category)));
            $status.toggleClass('catalog__status--invalid', isAgeInvalid);
            $reset.prop('hidden', !isFiltered(filters));
        },
    };
}

export function createEmptyState($root) {
    const $empty = $root.find('[data-catalog-empty]');
    const $title = $root.find('[data-catalog-empty-title]');

    return {
        render(filters, visibleCount) {
            const query = filters.query.trim();
            $empty.prop('hidden', visibleCount > 0);
            $title.text(query ? fill(copy.catalog.empty.title, { query }) : copy.catalog.empty.titleNoQuery);
        },
    };
}

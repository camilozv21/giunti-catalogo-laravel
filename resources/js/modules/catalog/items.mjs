import $ from 'jquery/slim';
import { normalize } from '../../utils/normalize.mjs';

function parseAgeAttribute(value) {
    return value === undefined ? null : Number(value);
}

// .attr rather than .data: no automatic type casting of the values.
export function readCatalogItems($elements) {
    return $elements.map((_, element) => {
        const $item = $(element);
        return {
            id: $item.attr('data-product-id'),
            category: $item.attr('data-category'),
            kind: $item.attr('data-kind'),
            ageMin: parseAgeAttribute($item.attr('data-age-min')),
            ageMax: parseAgeAttribute($item.attr('data-age-max')),
            searchText: normalize($item.attr('data-search') || ''),
        };
    }).get();
}

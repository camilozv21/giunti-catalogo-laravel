import $ from 'jquery/slim';
import { withViewTransition } from '../../utils/motion.mjs';
import { setFilters } from '../filter-state.mjs';

function readLabels($radios) {
    const labels = {};
    $radios.each((_, radio) => {
        labels[radio.value] = $(radio).closest('label').children('span').first().text().trim();
    });
    return labels;
}

export function createCategoryFilter($root) {
    const $radios = $root.find('[data-category-radio]');
    const labels = readLabels($radios);

    $radios.on('change', (event) => {
        const category = event.currentTarget.value;
        withViewTransition(() => setFilters({ category }));
    });

    return {
        render(filters) {
            $radios.each((_, radio) => {
                $(radio).prop('checked', radio.value === filters.category);
            });
        },
        labelOf(category) {
            return labels[category];
        },
        // For controls that remove themselves (reset, suggestion): focus must not fall back to <body>.
        focusChecked() {
            window.requestAnimationFrame(() => $radios.filter(':checked').trigger('focus'));
        },
    };
}

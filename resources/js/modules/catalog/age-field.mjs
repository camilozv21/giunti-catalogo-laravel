import { parseAge } from '../../utils/filters.mjs';
import { getFilters, setFilters } from '../filter-state.mjs';

const DESCRIBED_BY = 'catalog-age-unit';
const DESCRIBED_BY_INVALID = 'catalog-age-unit catalog-status';

// Text that is not an age stays in the field as a draft until it is fixed; the filters only get valid
// ages. When the age changes elsewhere (assistant, reset, Formazione) the draft is dropped.
export function createAgeField($root, onDraftChange) {
    const $input = $root.find('[data-age-input]');
    let draft = null;
    let appliedAge = null;

    function isInvalid() {
        return draft !== null;
    }

    function showAge(age) {
        const text = age === null ? '' : String(age);
        if ($input.val() !== text) $input.val(text);
    }

    function render(filters) {
        if (filters.age !== appliedAge) {
            appliedAge = filters.age;
            draft = null;
        }
        if (!isInvalid()) showAge(filters.age);
        $input.attr('aria-invalid', isInvalid() ? 'true' : null);
        $input.attr('aria-describedby', isInvalid() ? DESCRIBED_BY_INVALID : DESCRIBED_BY);
    }

    // Enter ("Fine" on a phone keypad) only closes the keyboard: the grid already filtered while typing.
    $root.find('[data-age-field]').on('submit', (event) => {
        event.preventDefault();
        $input.trigger('blur');
    });

    // No View Transition while typing: it would freeze the field for a beat on every keystroke.
    $input.on('input', () => {
        const raw = String($input.val());
        const age = parseAge(raw);
        const filters = getFilters();
        draft = age === undefined ? raw : null;
        if (age !== undefined && age !== filters.age) setFilters({ age });
        else onDraftChange(filters);
    });

    return { render, isInvalid };
}

import { normalize } from './normalize.mjs';

export const ALL_CATEGORIES = 'tutti';
export const DEFAULT_FILTERS = Object.freeze({ category: ALL_CATEGORIES, query: '', age: null });

const AGE_MIN = 0;
const AGE_MAX = 120;

function coversAge(item, age) {
    if (item.kind !== 'test' || item.ageMin === null || item.ageMax === null) return false;
    return age >= item.ageMin && age <= item.ageMax;
}

export function matchesFilters(item, filters) {
    if (filters.category !== ALL_CATEGORIES && item.category !== filters.category) return false;
    if (filters.age !== null && !coversAge(item, filters.age)) return false;
    const query = normalize(filters.query);
    return query === '' || item.searchText.includes(query);
}

// null: no age filter. undefined: not an age, so the field keeps it as a draft and the grid stays as it is.
export function parseAge(raw) {
    const text = raw.trim();
    if (text === '') return null;
    const age = Number(text);
    return Number.isInteger(age) && age >= AGE_MIN && age <= AGE_MAX ? age : undefined;
}

export function isFiltered(filters) {
    return filters.category !== ALL_CATEGORIES || filters.query.trim() !== '' || filters.age !== null;
}

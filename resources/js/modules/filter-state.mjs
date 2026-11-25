import $ from 'jquery/slim';
import { ALL_CATEGORIES, DEFAULT_FILTERS, parseAge } from '../utils/filters.mjs';

const CATEGORIES = ['clinica', 'scuola', 'lavoro', 'formazione'];
const CHANGE_EVENT = 'catalog:change';
const URL_WRITE_DELAY_MS = 250;

const $document = $(document);
let state = DEFAULT_FILTERS;
let urlTimer;

// Ages only apply to tests: choosing Formazione clears the age, and setting an age leaves Formazione.
function applyCategoryRules(next, patch) {
    if (patch.category === 'formazione') return { ...next, age: null };
    if (patch.age != null && next.category === 'formazione') return { ...next, category: ALL_CATEGORIES };
    return next;
}

function setParam(params, key, value) {
    if (value) params.set(key, value);
    else params.delete(key);
}

function writeUrl() {
    const params = new URLSearchParams(window.location.search);
    setParam(params, 'categoria', state.category === ALL_CATEGORIES ? null : state.category);
    setParam(params, 'q', state.query.trim() || null);
    setParam(params, 'eta', state.age === null ? null : String(state.age));
    const search = params.toString();
    const { pathname, hash } = window.location;
    window.history.replaceState(window.history.state, '', `${pathname}${search ? `?${search}` : ''}${hash}`);
}

function scheduleUrlWrite() {
    window.clearTimeout(urlTimer);
    urlTimer = window.setTimeout(writeUrl, URL_WRITE_DELAY_MS);
}

function publish() {
    $document.trigger(CHANGE_EVENT, [state]);
    scheduleUrlWrite();
}

export function getFilters() {
    return state;
}

export function setFilters(patch) {
    state = Object.freeze(applyCategoryRules({ ...state, ...patch }, patch));
    publish();
}

export function resetFilters() {
    state = DEFAULT_FILTERS;
    publish();
}

export function onFiltersChange(handler) {
    $document.on(CHANGE_EVENT, (event, filters) => handler(filters));
}

export function initFiltersFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const category = params.get('categoria');
    const filters = {
        category: CATEGORIES.includes(category) ? category : ALL_CATEGORIES,
        query: params.get('q') || '',
        age: parseAge(params.get('eta') ?? '') ?? null,
    };
    if (filters.category !== ALL_CATEGORIES || filters.query !== '' || filters.age !== null) setFilters(filters);
}

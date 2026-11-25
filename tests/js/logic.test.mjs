// Unit tests for the framework-free logic used by the jQuery modules, run with `node --test`.
// Same fixtures as the Next.js version, so both follow the same rules.
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { describe, it } from 'node:test';
import { fill, plural } from '../../resources/js/utils/copy.mjs';
import { matchesFilters, parseAge } from '../../resources/js/utils/filters.mjs';
import { formatPrice } from '../../resources/js/utils/format.mjs';
import { normalize } from '../../resources/js/utils/normalize.mjs';
import { validateContact } from '../../resources/js/utils/validators.mjs';

const json = (path) => JSON.parse(readFileSync(new URL(path, import.meta.url), 'utf8'));
const catalog = json('../../resources/data/catalog.json');
const search = json('../fixtures/search.json');
const filters = json('../fixtures/filters.json');
const validation = json('../fixtures/validation.json');
const format = json('../fixtures/format.json');

const items = catalog.products.map((product) => ({
    id: product.id,
    category: product.category,
    kind: product.kind,
    ageMin: product.kind === 'test' ? product.ageMin : null,
    ageMax: product.kind === 'test' ? product.ageMax : null,
    searchText: normalize(`${product.code} ${product.name}`),
}));
const allIds = items.map((item) => item.id);
const run = (f) => items.filter((item) => matchesFilters(item, f)).map((item) => item.id);

describe('normalize', () => {
    for (const { input, expected } of search.normalize) {
        it(`'${input}' → '${expected}'`, () => assert.equal(normalize(input), expected));
    }
});

describe('search by code and name', () => {
    for (const { q, ids } of search.queries) {
        it(`query '${q}'`, () => assert.deepEqual(run({ category: 'tutti', query: q, age: null }), ids === 'all' ? allIds : ids));
    }
});

describe('age field', () => {
    for (const { input, expected } of filters.ageInput) {
        it(`'${input}' → ${expected}`, () => assert.equal(parseAge(input), expected === 'invalid' ? undefined : expected));
    }
});

describe('combined filters', () => {
    for (const { filters: f, ids } of filters.cases) {
        it(JSON.stringify(f), () => assert.deepEqual(run(f), ids === 'all' ? allIds : ids));
    }
});

describe('validateContact', () => {
    for (const { input, errors } of validation.cases) {
        it(JSON.stringify(input), () => {
            const expected = Object.fromEntries(
                Object.entries(errors).map(([field, value]) => [field, typeof value === 'string' ? { key: value } : value]),
            );
            assert.deepEqual(validateContact(input), expected);
        });
    }
});

describe('formatPrice', () => {
    for (const { cents, expected } of format.price) {
        it(`${cents} → ${expected}`, () => assert.equal(formatPrice(cents), expected));
    }
});

describe('copy helpers', () => {
    it('fills placeholders and keeps unknown ones', () => {
        assert.equal(fill('Grazie, {name}. {x}', { name: 'Giulia' }), 'Grazie, Giulia. {x}');
    });
    it('picks Italian plural forms', () => {
        const forms = { zero: 'Nessun prodotto', one: '1 prodotto', other: '{count} prodotti' };
        assert.equal(plural(forms, 0), 'Nessun prodotto');
        assert.equal(plural(forms, 1), '1 prodotto');
        assert.equal(plural(forms, 12), '12 prodotti');
    });
});

describe('score interpreter (shared fixtures)', async () => {
    const { formatZ, scoresFor } = await import('../../resources/js/utils/scores.mjs');
    const scores = json('../fixtures/scores.json');
    for (const { z, t, standard, percentile, band } of scores.cases) {
        it(`z = ${z}`, () => {
            const result = scoresFor(z);
            assert.deepEqual({ t: result.t, standard: result.standard, percentile: result.percentile, band: result.band }, { t, standard, percentile, band });
        });
    }
    it('formats z the Italian way', () => {
        assert.equal(formatZ(1), '+1,0');
        assert.equal(formatZ(-2.5), '−2,5');
        assert.equal(formatZ(0), '0,0');
    });
});

describe('assistant scenarios match the catalogue (shared fixtures)', () => {
    const copy = json('../../resources/data/copy.it.json');
    const expected = json('../fixtures/assistant.json');
    for (const { scenario, ids } of expected.cases) {
        it(scenario, () => {
            const { filters: scenarioFilters } = copy.assistant.scenarios.find((s) => s.id === scenario);
            assert.deepEqual(items.filter((item) => matchesFilters(item, scenarioFilters)).map((item) => item.id), ids);
        });
    }
});

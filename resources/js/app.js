import $ from 'jquery/slim';
import { initAssistant } from './modules/assistant.mjs';
import { initCart } from './modules/cart.mjs';
import { initCatalog } from './modules/catalog/index.mjs';
import { initContactForm } from './modules/contact-form.mjs';
import { initDemoFab } from './modules/demo-fab.mjs';
import { initFiltersFromUrl } from './modules/filter-state.mjs';
import { initInterpreter } from './modules/interpreter.mjs';
import { initNav } from './modules/nav.mjs';
import { initSearch } from './modules/search.mjs';

$(() => {
    initCatalog();
    initAssistant();
    initInterpreter();
    initSearch();
    initNav();
    initCart();
    initContactForm();
    initDemoFab();
    // Last: every module must be listening before the filters from the URL are applied.
    initFiltersFromUrl();

    // Browser tests can wait for this before interacting.
    $('html').attr('data-ready', '');
});

import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { plural } from '../utils/copy.mjs';
import { matchesFilters } from '../utils/filters.mjs';
import { prefersReducedMotion } from '../utils/motion.mjs';
import { scrollToResults } from './catalog/index.mjs';
import { readCatalogItems } from './catalog/items.mjs';
import { setFilters } from './filter-state.mjs';

const TYPING_DELAY_MS = 450;
const ANSWER_DELAY_MS = 1300;
const AUTOPLAY_VISIBILITY = 0.45;

const STEP_QUESTION = 1;
const STEP_TYPING = 2;
const STEP_ANSWER = 3;

function onFirstView(element, callback) {
    const observer = new IntersectionObserver((entries) => {
        if (!entries.some((entry) => entry.isIntersecting)) return;
        observer.disconnect();
        callback();
    }, { threshold: AUTOPLAY_VISIBILITY });
    observer.observe(element);
    return observer;
}

export function initAssistant() {
    const $root = $('[data-assistant]');
    if (!$root.length) return;

    const $question = $root.find('[data-chat-question]');
    const $typing = $root.find('[data-chat-typing]');
    const $answer = $root.find('[data-chat-answer]');
    const $found = $root.find('[data-chat-found]');
    const $rows = $root.find('[data-chat-row]');
    const $chips = $root.find('[data-scenario]');
    const $applied = $root.find('[data-chat-applied]');
    const { scenarios } = copy.assistant;
    const itemsById = new Map(readCatalogItems($('[data-product-id]')).map((item) => [item.id, item]));

    let active = scenarios[0];
    let timers = [];
    let autoplay = null;

    function showStep(step) {
        $question.prop('hidden', step < STEP_QUESTION);
        $typing.prop('hidden', step !== STEP_TYPING);
        $answer.prop('hidden', step < STEP_ANSWER);
    }

    function renderAnswer() {
        let matchCount = 0;
        $rows.each((_, row) => {
            const isMatch = matchesFilters(itemsById.get($(row).attr('data-chat-row')), active.filters);
            $(row).prop('hidden', !isMatch);
            row.style.setProperty('--i', String(isMatch ? matchCount : 0));
            if (isMatch) matchCount += 1;
        });
        $found.text(plural(copy.assistant.found, matchCount));
    }

    function play(scenario) {
        timers.forEach((timer) => window.clearTimeout(timer));
        active = scenario;
        $chips.each((_, chip) => $(chip).attr('aria-pressed', String($(chip).attr('data-scenario') === scenario.id)));
        $question.text(scenario.message);
        $applied.text($chips.filter(`[data-scenario="${scenario.id}"]`).attr('data-applied') || '');
        renderAnswer();
        if (prefersReducedMotion()) {
            showStep(STEP_ANSWER);
            return;
        }
        showStep(STEP_QUESTION);
        timers = [
            window.setTimeout(() => showStep(STEP_TYPING), TYPING_DELAY_MS),
            window.setTimeout(() => showStep(STEP_ANSWER), ANSWER_DELAY_MS),
        ];
    }

    $chips.on('click', (event) => {
        autoplay?.disconnect();
        const id = $(event.currentTarget).attr('data-scenario');
        play(scenarios.find((scenario) => scenario.id === id));
    });

    $root.on('click', '[data-chat-cta]', () => {
        setFilters(active.filters);
        scrollToResults();
    });

    const chat = $root.find('[data-chat]')[0];
    if (chat && !prefersReducedMotion()) autoplay = onFirstView(chat, () => play(scenarios[0]));
}

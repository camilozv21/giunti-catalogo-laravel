import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { scrollToElement } from '../utils/motion.mjs';

const CONTACT_VISIBLE_RATIO = 0.2;
const FOCUS_DELAY_MS = 50;

function setHidden($fab, isHidden) {
    $fab.attr({
        'data-hidden': isHidden ? 'true' : null,
        'aria-hidden': isHidden ? 'true' : null,
        tabindex: isHidden ? '-1' : null,
    });
}

export function initDemoFab() {
    const $fab = $('[data-fab]');
    const contact = document.getElementById('contatti');
    if (!$fab.length || !contact) return;

    // Hidden while the form is on screen: it would point where the visitor already is and could cover the submit button.
    const observer = new IntersectionObserver(([entry]) => setHidden($fab, entry?.isIntersecting ?? false), {
        threshold: CONTACT_VISIBLE_RATIO,
    });
    observer.observe(contact);

    $fab.on('click', (event) => {
        event.preventDefault();
        if (String($('#contact-message').val()).trim() === '') $(document).trigger('contact:prefill', [copy.fab.message]);
        scrollToElement(contact);
        window.setTimeout(() => document.getElementById('contact-name')?.focus({ preventScroll: true }), FOCUS_DELAY_MS);
    });
}

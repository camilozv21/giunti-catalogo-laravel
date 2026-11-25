import $ from 'jquery/slim';

const REWRITE_DELAY_MS = 30;

// Writes to both live regions: a closed <dialog> and the inert page behind an open one stay silent.
export function announce(text) {
    const $regions = $('[data-announcer]');
    // Clearing first makes a repeated message audible again.
    $regions.text('');
    window.setTimeout(() => $regions.text(text), REWRITE_DELAY_MS);
}

import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { fill } from '../utils/copy.mjs';
import { formatZ, scoresFor } from '../utils/scores.mjs';

const Z_MIN = -3;
const Z_MAX = 3;
// Geometry of resources/svg/interpreter.svg, in its units; the server renders the first frame with the same values.
const CHART_SIGMA = 58;
const CHART_AMPLITUDE = 168;
const CHART_CENTER = 232;
const CHART_WIDTH = 464;

function chartVars(z, percentileValue) {
    return {
        '--z': String(z),
        '--lift': `${(CHART_AMPLITUDE * (1 - Math.exp(-(z * z) / 2))).toFixed(2)}px`,
        '--cut': `${(((CHART_CENTER + z * CHART_SIGMA) / CHART_WIDTH) * 100).toFixed(2)}%`,
        '--fill': `${(((z - Z_MIN) / (Z_MAX - Z_MIN)) * 100).toFixed(2)}%`,
        '--p': String(Math.round(percentileValue)),
    };
}

function percentileLabel(percentile) {
    return /^\d+$/.test(percentile) ? `${percentile}°` : percentile;
}

function sentenceFor(percentile) {
    if (percentile === '<1') return copy.interpreter.sentenceLow;
    if (percentile === '>99') return copy.interpreter.sentenceHigh;
    return fill(copy.interpreter.sentence, { count: percentile });
}

export function initInterpreter() {
    const $root = $('[data-interpreter]');
    if (!$root.length) return;
    const $input = $root.find('[data-interpreter-input]');
    const $card = $root.find('[data-interpreter-card]');

    function render(z) {
        const scores = scoresFor(z);
        const percentile = percentileLabel(scores.percentile);
        const band = copy.interpreter.bands[scores.band];

        $root.find('[data-interpreter-z]').text(formatZ(z));
        $root.find('[data-interpreter-t]').text(String(scores.t));
        $root.find('[data-interpreter-standard]').text(String(scores.standard));
        $root.find('[data-interpreter-percentile], [data-interpreter-card-percentile]').text(percentile);
        $root.find('[data-interpreter-band]').text(band);
        $root.find('[data-interpreter-sentence]').text(sentenceFor(scores.percentile));
        $input.attr('aria-valuetext', fill(copy.interpreter.valueText, { z: formatZ(z), percentile, band }));

        for (const [name, value] of Object.entries(chartVars(z, scores.percentileValue))) {
            $input[0].style.setProperty(name, value);
            $card[0].style.setProperty(name, value);
        }
    }

    $input.on('input', (event) => render(Number(event.currentTarget.value)));
}

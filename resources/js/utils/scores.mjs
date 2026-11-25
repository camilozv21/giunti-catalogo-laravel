const STANDARD_MEAN = 100;
const STANDARD_SD = 15;
const T_MEAN = 50;
const T_SD = 10;
const TYPOGRAPHIC_MINUS = '\u2212';

// Abramowitz–Stegun 7.1.26 (maximum error 1.5e-7).
function erf(x) {
    const sign = Math.sign(x);
    const a = Math.abs(x);
    const t = 1 / (1 + 0.3275911 * a);
    const poly = ((((1.061405429 * t - 1.453152027) * t + 1.421413741) * t - 0.284496736) * t + 0.254829592) * t;
    return sign * (1 - poly * Math.exp(-a * a));
}

function normalCdf(z) {
    return 0.5 * (1 + erf(z / Math.SQRT2));
}

// Conventional clinical cut-offs on the standard score.
function bandFor(standard) {
    if (standard < 70) return 'molto-inferiore';
    if (standard < 85) return 'inferiore';
    if (standard <= 115) return 'media';
    if (standard <= 130) return 'superiore';
    return 'molto-superiore';
}

function percentileText(value) {
    if (value < 0.5) return '<1';
    if (value >= 99.5) return '>99';
    return String(Math.round(value));
}

export function scoresFor(z) {
    const standard = Math.round(STANDARD_MEAN + STANDARD_SD * z);
    const percentileValue = normalCdf(z) * 100;
    return {
        z,
        t: Math.round(T_MEAN + T_SD * z),
        standard,
        percentile: percentileText(percentileValue),
        percentileValue,
        band: bandFor(standard),
    };
}

export function formatZ(z) {
    // Anything that rounds to 0,0 gets no sign.
    if (Math.abs(z) < 0.05) return '0,0';
    const value = Math.abs(z).toFixed(1).replace('.', ',');
    return `${z > 0 ? '+' : TYPOGRAPHIC_MINUS}${value}`;
}

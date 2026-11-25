const pluralRules = new Intl.PluralRules('it-IT');

export function fill(template, params = {}) {
    return template.replace(/\{(\w+)\}/g, (match, key) => (key in params ? String(params[key]) : match));
}

function pluralForm(forms, count) {
    if (count === 0 && forms.zero) return forms.zero;
    if (pluralRules.select(count) === 'one' && forms.one) return forms.one;
    return forms.other;
}

export function plural(forms, count, params = {}) {
    return fill(pluralForm(forms, count), { count, ...params });
}

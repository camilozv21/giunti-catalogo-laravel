// useGrouping 'always': since CLDR 46 Italian no longer groups four-digit amounts ("1250,00 €").
const priceFormatter = new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR', useGrouping: 'always' });

export function formatPrice(cents) {
    return priceFormatter.format(cents / 100);
}

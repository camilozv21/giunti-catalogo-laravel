export function normalize(value) {
    return String(value)
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[‘’ʼ`´]/g, "'")
        .replace(/[–—]/g, '-')
        .toLowerCase()
        .replace(/\s+/g, ' ')
        .trim();
}

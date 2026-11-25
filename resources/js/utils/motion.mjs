export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function scrollToElement(element, block = 'start') {
    element?.scrollIntoView({ behavior: prefersReducedMotion() ? 'auto' : 'smooth', block });
}

// The update must stay synchronous: the "after" snapshot is taken as soon as it returns.
export function withViewTransition(update) {
    if (typeof document.startViewTransition !== 'function' || prefersReducedMotion()) {
        update();
        return;
    }
    document.startViewTransition(update);
}

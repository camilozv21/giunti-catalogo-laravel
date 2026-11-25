// A click on the ::backdrop targets the <dialog> element itself.
export function closeOnBackdropClick($dialog) {
    $dialog.on('click', (event) => {
        if (event.target === $dialog[0]) $dialog[0].close();
    });
}

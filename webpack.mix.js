const path = require('path');
const mix = require('laravel-mix');

mix.setPublicPath('public')
    .js('resources/js/app.js', 'js')
    .stylus('resources/stylus/app.styl', 'css', {
        stylusOptions: {
            include: [path.resolve(__dirname, 'resources/stylus')],
        },
    })
    .options({
        processCssUrls: false,
        // cssnano's declaration sorter moves the `animation` shorthand after
        // `animation-timeline`, which silently resets scroll-driven animations.
        cssNano: { cssDeclarationSorter: false },
    })
    .disableNotifications();

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps(false, 'source-map');
}

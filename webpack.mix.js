const mix = require('laravel-mix');
require('laravel-mix-polyfill');

mix
    .sass('client/src/styles/grid-field-styling.scss', 'client/dist/styles')
    .sourceMaps()
    .polyfill({
        enabled: mix.inProduction(),
        useBuiltIns: "usage",
        targets: {"ie": 11}
    });
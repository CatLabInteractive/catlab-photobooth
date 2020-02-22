const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/app.scss', 'public/css');

mix.js('resources/swagger/swagger-ui.js', 'public/swaggerui')
    .sass('resources/swagger/swagger.scss', 'public/swaggerui');

// Photobooth
mix.ts('resources/photobooth/js/app.js', 'public/res/photobooth/js')
    .sass('resources/photobooth/sass/app.scss', 'public/res/photobooth/css');

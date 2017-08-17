let mix = require('laravel-mix');

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

mix.scripts([
    'resources/assets/js/jquery-3.2.1.js',
    'resources/assets/js/jquery.datetimepicker.full.js',
    'resources/assets/js/app.js'
], 'public/js/app.js')
.version();

mix.sass(
    'resources/assets/sass/app.scss',
    'public/css/app.css'
)
.styles([
    'public/css/app.css',
    'resources/assets/css/jquery.datetimepicker.css'
], 'public/css/app.css')
.version();

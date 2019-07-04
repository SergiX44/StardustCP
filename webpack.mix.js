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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.css',
    'node_modules/stisla/assets/css/style.css',
    'node_modules/stisla/assets/css/components.css'
], 'public/css/vendors.css');

mix.scripts([
    'node_modules/jquery/dist/jquery.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
    'node_modules/jquery.nicescroll/dist/jquery.nicescroll.js',
    'node_modules/moment/moment.js',
    'node_modules/stisla/assets/js/stisla.js',
    'node_modules/stisla/assets/js/scripts.js'
], 'public/js/vendors.js');

mix.copyDirectory('node_modules/stisla/assets/fonts', 'public/fonts/');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts/');

if (mix.inProduction()) {
    mix.version();
}
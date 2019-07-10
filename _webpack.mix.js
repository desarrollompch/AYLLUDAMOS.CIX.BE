
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

mix.js('resources/assets/js/app.js', 'public/js')
    .scripts([
        'resources/assets/vendor/jquery.cookie/jquery.cookie.js',
        'resources/assets/js/front.js'
    ], 'public/js/all.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .styles([
       'resources/assets/css/style.default.css',
       'node_modules/vue-multiselect/dist/vue-multiselect.min.css'
   ], 'public/css/all.css')
    .browserSync('incidencias.test')
    .version();

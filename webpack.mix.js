const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({
    hmrOptions: {
        host: 'localhost',
        port: process.env.MIX_HMR_PORT || 8080
    }
});
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).minify('public/assets/js/soft-ui-dashboard.js');
mix.sass('public/assets/scss/soft-ui-dashboard.scss', 'public/assets/css');

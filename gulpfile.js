var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var paths = {
        resources: {
            js: './resources/assets/js',
            css: './resources/assets/css'
        },

        public: {
            js: './public/js',
            css: './public/css'
        }
    };

	mix.sass("app.scss");

    mix.scripts([
        paths.resources.js + '/jquery-2.2.4.min.js',
        paths.resources.js + '/bootstrap-3.3.6.min.js',
        paths.resources.js + '/chart-2.1.6.min.js',
        paths.resources.js + '/application.js'
    ], paths.public.js + "/application.js");

	mix.version([
        'css/app.css',
        'js/application.js'
    ]);
});

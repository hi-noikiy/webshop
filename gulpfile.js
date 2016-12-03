const elixir = require('laravel-elixir');

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

    /**
     * Admin shit
     */
    mix.sass('admin/app.scss', 'public/css/admin/app.css')
        .webpack('admin/app.js', 'public/js/admin/app.js');

    mix.version([
        'css/app.css',
        'js/application.js',
        'js/admin/app.js',
        'css/admin/app.css'
    ]);
});

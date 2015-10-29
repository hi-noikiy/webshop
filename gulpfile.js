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

var paths = {
        'bowerAsset': './resources/assets/bower',
        'jsAsset': './public/js',
        'cssAsset': './public/css',
        'fontAsset': './public/build/fonts'
}

elixir(function(mix) {
        mix.copy(paths.bowerAsset + '/bootstrap/fonts', paths.fontAsset);
        mix.copy(paths.bowerAsset + '/bootstrap/dist/js/bootstrap.min.js', paths.jsAsset + '/bootstrap.min.js');
        mix.copy(paths.bowerAsset + '/jquery/dist/jquery.min.js', paths.jsAsset + '/jquery.min.js');

	mix.less([
                "app.less"
        ]);

	mix.version([
                'css/app.css',
                'js/jquery.min.js',
                'js/bootstrap.min.js'
        ]);
});

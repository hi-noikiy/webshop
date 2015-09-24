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
        'bower': './resources/assets/bower/',
}

elixir(function(mix) {
        mix.copy(paths.bower + 'bootstrap/fonts', 'public/fonts');
        mix.copy(paths.bower + 'bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.min.js');
        mix.copy(paths.bower + 'jquery/dist/jquery.min.js', 'public/js/jquery.min.js');
        mix.copy(paths.bower + 'jquery/dist/jquery.min.map', 'public/js/jquery.min.map');
});

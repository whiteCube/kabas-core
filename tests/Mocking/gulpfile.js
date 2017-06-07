const elixir = require('laravel-elixir');

require('laravel-elixir-config');
require('laravel-elixir-imagemin');
require('laravel-elixir-webpack-official');
require('laravel-elixir-browsersync-official');

elixir(mix => {
    mix.imagemin()
        .copy(elixir.config.assetsPath + '/fonts/**.*', elixir.config.publicPath + "/fonts")
        .sass( [elixir.config.sass.folder], elixir.config.publicPath + '/css')
        .webpack(elixir.config.js.file)
        .browserSync({ proxy: elixir.config.browserSync.proxy })
});

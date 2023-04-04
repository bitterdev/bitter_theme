let mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
        bootstrap: true,
        vue: 'Vue',
        moment: 'moment'
    }
});

mix.setResourceRoot('../');
mix.setPublicPath('../themes/bitter_theme');

mix
    .sass('assets/themes/bitter_theme/scss/main.scss', '../themes/bitter_theme/css')
    .js('assets/themes/bitter_theme/js/main.js', '../themes/bitter_theme/js').vue()
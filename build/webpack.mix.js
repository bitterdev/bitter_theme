let mix = require('laravel-mix');
const path = require("path");

mix.webpackConfig({
    externals: {
        jquery: "jQuery",
        bootstrap: true,
        vue: "Vue",
        moment: "moment",
    }
});

mix.setResourceRoot('../');
mix.setPublicPath('../themes/bitter_theme');

mix
    .sass('../themes/bitter_theme/css/presets/default/main.scss', '../themes/bitter_theme/css/skins/default.css', {
        sassOptions: {
            includePaths: [
                path.resolve(__dirname, './node_modules/')
            ]
        }
    })
    .js('assets/themes/bitter_theme/js/main.js', '../themes/bitter_theme/js').vue()
    .copy('node_modules/photoswipe/dist/default-skin/default-skin.css', '../css/default-skin.css')
    .copy('node_modules/photoswipe/dist/default-skin/default-skin.png', '../css/default-skin.png')
    .copy('node_modules/photoswipe/dist/default-skin/default-skin.svg', '../css/default-skin.svg')
    .copy('node_modules/photoswipe/dist/default-skin/preloader.gif', '../css/preloader.gif')
    .copy('node_modules/photoswipe/dist/photoswipe-ui-default.js', '../js/photoswipe-ui-default.min.js')
    .copy('node_modules/photoswipe/dist/photoswipe.css', '../css/photoswipe.css')
    .copy('node_modules/photoswipe/dist/photoswipe.min.js', '../js/photoswipe.min.js')
    .copy('node_modules/macy/dist/macy.js', '../js/macy.js')
    .copy('node_modules/slick-carousel/slick/slick.min.js', '../js/slick.min.js')
    .copy('node_modules/slick-carousel/slick/slick-theme.css', '../css/slick-theme.css')
    .copy('node_modules/slick-carousel/slick/slick.css', '../css/slick.css')
    .copy('node_modules/slick-carousel/slick/ajax-loader.gif', '../css/ajax-loader.gif')
    .copy('node_modules/slick-carousel/slick/fonts/slick.eot', '../css/fonts/slick.eot')
    .copy('node_modules/slick-carousel/slick/fonts/slick.svg', '../css/fonts/slick.svg')
    .copy('node_modules/slick-carousel/slick/fonts/slick.ttf', '../css/fonts/slick.ttf')
    .copy('node_modules/slick-carousel/slick/fonts/slick.woff', '../css/fonts/slick.woff')
    .copy('node_modules/mmenu-light/dist/mmenu-light.css', '../css/mmenu-light.css')
    .copy('node_modules/mmenu-light/dist/mmenu-light.js', '../js/mmenu-light.js')
    .copy('node_modules/vanilla-cookieconsent/dist/cookieconsent.css', '../css/cookieconsent.css')
    .copy('node_modules/vanilla-cookieconsent/dist/cookieconsent.js', '../js/cookieconsent.js')
    .copy('node_modules/@orestbida/iframemanager/dist/iframemanager.css', '../css/iframemanager.css')
    .copy('node_modules/@orestbida/iframemanager/dist/iframemanager.js', '../js/iframemanager.js')
    .copy('node_modules/toastify-js/src/toastify.js', '../js/toastify.js')
    .copy('node_modules/toastify-js/src/toastify.css', '../css/toastify.css')
    .copyDirectory('node_modules/@fontsource/open-sans', '../themes/bitter_theme/css/fonts/open-sans')

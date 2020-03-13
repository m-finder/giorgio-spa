const mix = require('laravel-mix');
const config = require('./webpack.config');
require('laravel-mix-svg-vue');
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


mix.webpackConfig(config);

mix.js('resources/js/admin.js', 'public/js')
  .extract([
    'vue',
    'axios',
    'vuex',
    'vue-router',
    'echarts',
    'vue-echarts',
    'vue-izitoast'
  ])
  .sass('resources/sass/admin.scss', 'public/css')
  .svgVue({
    svgPath: 'resources/icons',
    extract: false,
    svgoSettings: [
      {removeTitle: true},
      {removeViewBox: true},
      {removeDimensions: true},
      {removeAttrs: {attrs: '(fill|stroke)'}}
    ]
  })
  .copy('resources/images', 'public/images');

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps()
    .webpackConfig({
      devtool: 'cheap-eval-source-map', // Fastest for development
    });
}

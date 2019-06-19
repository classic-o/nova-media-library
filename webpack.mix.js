let mix = require('laravel-mix');

mix.setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .sass('resources/scss/tool.scss', 'css');

mix.setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  .sass('resources/scss/field.scss', 'css');

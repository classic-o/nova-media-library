let mix = require("laravel-mix");
let path = require('path');
require("./nova.mix");

mix
  .setPublicPath("dist")
  .js("resources/js/tool.js", "js")
  .vue({ version: 3 })
  .nova("classic-o/nova-media-library");

  mix.alias({
    'laravel-nova': path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/packages.js'),
  });

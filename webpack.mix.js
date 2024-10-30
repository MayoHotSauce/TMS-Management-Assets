const mix = require('laravel-mix');

// Compile JavaScript and SCSS files
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')

// Include AdminLTE CSS and JS
   .styles('node_modules/admin-lte/dist/css/adminlte.min.css', 'public/css/adminlte.min.css')
   .scripts('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
   .scripts('node_modules/admin-lte/dist/js/adminlte.min.js', 'public/js/adminlte.min.js');

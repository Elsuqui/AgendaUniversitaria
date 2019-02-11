const mix = require('laravel-mix');

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

//Mix para copiar y compilar paquete para integracion de semantic via node
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/semantic-ui-css/semantic.min.css', 'public/css/semantic.min.css')
    .copy('node_modules/semantic-ui-css/semantic.min.js', 'public/js/semantic.min.js');

//Libreria de OpenCv para javascript navegadores
//mix.copy('resources/js/opencv.js', 'public/js/opencv.js');
//Libreria Utils.js para cargar archivos de cascada
mix.copy('resources/js/utils.js', 'public/js/utils.js');
//Libreria para el manejo de Estados controlar la deteccion
mix.copy('resources/js/stats.min.js', 'public/js/stats.min.js');
//Libreria para el uso del calendario (Full Calendar)
mix.copy('resources/js/plugins/fullcalendar', 'public/plugins/fullcalendar');
//Libreria para le manejo de fechas y horas (Moment.js)
mix.copy('resources/js/plugins/moment.min.js', 'public/plugins/fullcalendar/moment.min.js');
//Libreria que sirve de datepicker en semantic
mix.copy('node_modules/semantic-ui-calendar', 'public/plugins/semantic-ui-calendar');

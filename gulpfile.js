var elixir = require('laravel-elixir'),
    node = 'node_modules',
    adminlte_plugins =  '/node_modules/admin-lte/plugins',
    plugins = '/public/plugins';

var paths = {
    'jquery': node + '/jquery/dist',
    'jqueryui' : node + '/admin-lte/plugins/jQueryUI',
    'bootstrap' : node + '/bootstrap/dist',
    'colorbox': node + '/jquery-colorbox',
    'clipboard' : node + '/clipboard',
    'adminlte': node + '/admin-lte',
    'colorpicker': node + '/bootstrap-colorpicker/dist',
    'datepicker': node + '/bootstrap-datepicker/dist',
    'daterangepicker': node + '/bootstrap-daterangepicker',
    'timepicker': node + '/bootstrap-timepicker',
    'fullcalendar': node + '/fullcalendar/dist',
    'slimscroll': node + '/slimscroll/lib',
    'moment': node + '/bootstrap-daterangepicker',
    'select2': node + '/select2/dist',
    'fontawesome': node + '/font-awesome',
    'tippy' : node + '/tippy.js',
    'fineuploader': plugins + '/fine-uploader',
    'datetimepicker': plugins + '/datetimepicker',
    'contextmenu': plugins + '/jquery-contextMenu',
    'resources': '/resources/assets'
};


elixir(function (mix) {

    mix.copy(paths.fontawesome +'/fonts/*.*', 'public/build/fonts');
    mix.copy(paths.bootstrap +'/fonts/*.*','public/build/fonts');
    mix.copy(paths.colorpicker + '/img/', 'public/build/css/img');
    mix.copy(paths.colorbox + '/example3/images/', 'public/build/css/images');

    mix.sass(['menus.scss', 'qubo-skin.scss'], 'public/css/qubo.css');

    mix.styles([
        paths.bootstrap + '/css/bootstrap.min.css',
        paths.colorpicker + '/css/bootstrap-colorpicker.min.css',
        paths.datepicker + '/css/bootstrap-datepicker3.min.css',
        paths.daterangepicker + '/daterangepicker.css',
        paths.datetimepicker + '/jquery.datetimepicker.css',
        paths.timepicker + '/css/bootstrap-timepicker.min.css',
        paths.fullcalendar + '/fullcalendar.min.css',
        paths.select2 + '/css/select2.min.css',
        paths.fineuploader + '/v5.2.1/fine-uploader-new.min.css',
        paths.colorbox + '/example3/colorbox.css',
        paths.fontawesome + '/css/font-awesome.min.css',
        paths.adminlte + '/dist/css/AdminLTE.min.css',
        paths.contextmenu + '/jquery.contextMenu.min.css',
        'public/css/qubo.css'
    ], 'public/css/admin.css', './'); //dest / src

    mix.scripts([
        paths.jquery + '/jquery.min.js',
         paths.jqueryui + '/jquery-ui.min.js',
        paths.bootstrap + '/js/bootstrap.js',
        paths.adminlte + '/dist/js/adminlte.min.js',
        // paths.slimscroll + '/slimscroll.js',
        paths.moment + '/moment.min.js',
        paths.colorpicker + '/js/bootstrap-colorpicker.min.js',
        paths.datepicker + '/js/bootstrap-datepicker.min.js',
        paths.daterangepicker + '/daterangepicker.js',
        paths.datetimepicker + '/jquery.datetimepicker.js',
        paths.timepicker + '/js/bootstrap-timepicker.min.js',
        paths.fullcalendar + '/fullcalendar.min.js',
        paths.select2 + '/js/select2.min.js',
        paths.fineuploader + '/v5.2.1/fine-uploader.js',
        paths.colorbox + '/jquery.colorbox-min.js',
        paths.contextmenu + '/jquery.contextMenu.min.js',
        paths.tippy + '/dist/tippy.min.js',
        paths.resources + '/js/highlight.js',
        paths.resources + '/js/app_custom.js'
    ], 'public/js/admin.js', './'); //dest / src

    mix.version(['public/js/admin.js', 'public/css/admin.css']);

});
const mix = require('laravel-mix');

// Read .env
require('dotenv').config();

// Admin styles
mix.styles('resources/css/admin/coreui.min.css', 'public/css/admin/coreui.min.css')
    .styles('resources/css/admin/font-awesome.min.css', 'public/css/admin/font-awesome.min.css')
    .styles('resources/css/admin/pace.min.css', 'public/css/admin/pace.min.css')
    .styles('resources/css/admin/alertifyjs.css', 'public/css/admin/alertifyjs.css')
    .styles('resources/css/admin/app.css', 'public/css/admin/app.css')
;

// Admin font awesome fonts
mix.copyDirectory('resources/fonts', 'public/css/fonts');

// Admin JS
mix.scripts('resources/js/admin/jquery.min.js', 'public/js/admin/jquery.min.js')
    .scripts('resources/js/admin/popper.min.js', 'public/js/admin/popper.min.js')
    .scripts('resources/js/admin/bootstrap.min.js', 'public/js/admin/bootstrap.min.js')
    .scripts('resources/js/admin/pace.min.js', 'public/js/admin/pace.min.js')
    .scripts('resources/js/admin/coreui.min.js', 'public/js/admin/coreui.min.js')
    .scripts('resources/js/admin/alertifyjs.js', 'public/js/admin/alertifyjs.js')
    .scripts('resources/js/admin/axios.min.js', 'public/js/admin/axios.min.js')
    .scripts('resources/js/admin/app.js', 'public/js/admin/app.js')
;

// Renewal styles
mix.styles('resources/css/renewals/bootstrap.min.css', 'public/css/renewals/bootstrap.min.css')
    .styles('resources/css/renewals/material-bootstrap-wizard.css', 'public/css/renewals/material-bootstrap-wizard.css')
    .styles('resources/css/renewals/demo.css', 'public/css/renewals/demo.css')
    .styles('resources/css/renewals/individual.css', 'public/css/renewals/individual.css')
;

// Renewal scripts
mix.scripts('resources/js/renewals/jquery-2.2.4.min.js', 'public/js/renewals/jquery-2.2.4.min.js')
    .scripts('resources/js/renewals/bootstrap.min.js', 'public/js/renewals/bootstrap.min.js')
    .scripts('resources/js/renewals/jquery.bootstrap.js', 'public/js/renewals/jquery.bootstrap.js')
    .scripts('resources/js/renewals/material-bootstrap-wizard.js', 'public/js/renewals/material-bootstrap-wizard.js')
    .scripts('resources/js/renewals/jquery.validate.min.js', 'public/js/renewals/jquery.validate.min.js')
;

if (mix.inProduction()) {
    mix.version();
} else {
    mix.browserSync(process.env.APP_URL);
    mix.disableNotifications();
}

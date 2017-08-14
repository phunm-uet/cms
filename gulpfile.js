process.env.DISABLE_NOTIFIER = true;

const elixir = require('laravel-elixir');
const gulp = require('gulp');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;
elixir.inProduction = true;

elixir(function (mix) {

    mix.sass('./core/base/resources/assets/sass/core.scss', 'public/vendor/core/css');

    mix.sass('./core/base/resources/assets/sass/custom/admin-bar.scss', 'public/vendor/core/css');

    mix
        .scripts(
            [
                './core/base/resources/assets/js/script.js',
                './core/base/resources/assets/js/csrf.js'
            ],
            'public/vendor/core/js/core.js',
            './core/base/resources/assets/js');

    mix
        .scripts('./core/menu-left-hand/resources/assets/js/app_modules/admin-menu-left-hand.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/base/resources/assets/js/app_modules/editor.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/dashboard/resources/assets/js/app_modules/dashboard.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/base/resources/assets/js/app_modules/datatables.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/acl/resources/assets/js/app_modules/profile.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/blog/resources/assets/js/app_modules/tags.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/media/resources/assets/js/app_modules/media.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/base/resources/assets/js/app_modules/slug.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/acl/resources/assets/js/app_modules/feature.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/acl/resources/assets/js/app_modules/role.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/menu/resources/assets/js/app_modules/menu.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/widget/resources/assets/js/app_modules/widget.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/base/resources/assets/js/app_modules/plugin.js', 'public/vendor/core/js/app_modules')
        .scripts('./core/acl/resources/assets/js/app_modules/login.js', 'public/vendor/core/js/app_modules');

    // Translation
    mix.scripts('./plugins/translation/resources/assets/js/translation.js', 'public/vendor/core/plugins/translation/js');

    // Backup
    mix.scripts('./plugins/backup/resources/assets/js/backup.js', 'public/vendor/core/plugins/backup/js');

    // Language plugin
    mix
        .scripts('./plugins/language/resources/assets/js/language.js', 'public/vendor/core/plugins/language/js')
        .scripts('./plugins/language/resources/assets/js/language-global.js', 'public/vendor/core/plugins/language/js');

    // Custom fields plugin
    mix
        .sass('./plugins/custom-field/resources/assets/sass/edit-field-group.scss', 'public/vendor/core/plugins/custom-field/css')
        .sass('./plugins/custom-field/resources/assets/sass/custom-field.scss', 'public/vendor/core/plugins/custom-field/css')
        .scripts('./plugins/custom-field/resources/assets/js/edit-field-group.js', 'public/vendor/core/plugins/custom-field/js')
        .scripts('./plugins/custom-field/resources/assets/js/use-custom-fields.js', 'public/vendor/core/plugins/custom-field/js');


    /* =============== CUSTOMIZE FOR EACH PROJECT ==================== */

    mix
        .sass('./core/base/resources/assets/sass/base/themes/black.scss', 'public/vendor/core/css/themes')
        .sass('./core/base/resources/assets/sass/base/themes/default.scss', 'public/vendor/core/css/themes');
});

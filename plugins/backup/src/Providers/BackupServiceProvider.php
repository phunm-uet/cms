<?php
namespace Botble\Backup\Providers;

use Botble\Backup\Supports\Filter;
use Botble\Base\Supports\Helper;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/backup.php', 'backup');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'backup');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'backup');

        if (app()->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/backup')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/backup')], 'lang');
            $this->publishes([__DIR__ . '/../../config/backup.php' => config_path('backup.php')], 'config');

            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/backup')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/backup')], 'assets');
        }

        Filter::initialize();
    }
}

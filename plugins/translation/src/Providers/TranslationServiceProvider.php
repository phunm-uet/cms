<?php
namespace Botble\Translation\Providers;

use Botble\Translation\Console\CleanCommand;
use Botble\Translation\Console\ExportCommand;
use Botble\Translation\Console\FindCommand;
use Botble\Translation\Console\ImportCommand;
use Botble\Translation\Console\ResetCommand;
use Botble\Translation\Manager;
use Botble\Translation\Supports\Filter;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        $this->app->bind('translation-manager', Manager::class);

        if (app()->runningInConsole()) {
            $this->commands([
                ResetCommand::class,
                ImportCommand::class,
                FindCommand::class,
                ExportCommand::class,
                CleanCommand::class
            ]);
        }

    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/translation.php', 'translation');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'translations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'translations');


        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/translations')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/translations')], 'lang');
            $this->publishes([__DIR__ . '/../../config/translation.php' => config_path('translation.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/translation')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/translation')], 'assets');
        }


        Filter::initialize();
    }
}

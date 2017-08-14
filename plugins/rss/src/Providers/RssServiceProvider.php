<?php

namespace Botble\Rss\Providers;

use Botble\Rss\Models\Rss;
use Illuminate\Support\ServiceProvider;
use Botble\Rss\Repositories\Caches\RssCacheDecorator;
use Botble\Rss\Repositories\Eloquent\RssRepository;
use Botble\Rss\Repositories\Interfaces\RssInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Base\Supports\Helper;

class RssServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(RssInterface::class, function () {
                return new RssCacheDecorator(new RssRepository(new Rss()), new Cache($this->app['cache'], RssRepository::class));
            });
        } else {
            $this->app->singleton(RssInterface::class, function () {
                return new RssRepository(new Rss());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'rss');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/rss.php', 'rss');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'rss');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/rss')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/rss')], 'lang');
            $this->publishes([__DIR__ . '/../../config/rss.php' => config_path('rss.php')], 'config');
        }
    }
}

<?php

namespace Botble\Page\Providers;

use Botble\Base\Supports\Helper;
use Botble\Page\Models\Page;
use Botble\Page\Repositories\Caches\PageCacheDecorator;
use Botble\Page\Repositories\Eloquent\PageRepository;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Page\Supports\Action;
use Botble\Page\Supports\Filter;
use Illuminate\Support\ServiceProvider;

/**
 * Class PageServiceProvider
 * @package Botble\Page
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class PageServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(PageInterface::class, function () {
                return new PageCacheDecorator(new PageRepository(new Page()), new Cache($this->app['cache'], PageRepository::class));
            });
        } else {
            $this->app->singleton(PageInterface::class, function () {
                return new PageRepository(new Page());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/page.php', 'pages');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pages');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'pages');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/pages')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/pages')], 'lang');
            $this->publishes([__DIR__ . '/../../config/page.php' => config_path('page.php')], 'config');
        }

        Filter::initialize();
        Action::initialize();
    }
}

<?php

namespace Botble\MenuLeftHand\Providers;

use Botble\Base\Services\Cache\Cache;
use Botble\MenuLeftHand\Facades\MenuLeftHandFacade;
use Botble\MenuLeftHand\Repositories\Interfaces\MenuLeftHandInterface;
use Botble\MenuLeftHand\Models\MenuLeftHand;
use Botble\MenuLeftHand\Repositories\Caches\MenuLeftHandCacheDecorator;
use Botble\MenuLeftHand\Repositories\Eloquent\MenuLeftHandRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Botble\Base\Supports\Helper;

/**
 * Class MenuLeftHandServiceProvider
 * @package Botble\MenuLeftHand
 * @author Sang Nguyen
 * @since 07/02/2016 09:50 AM
 */
class MenuLeftHandServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');

        if (setting('enable_cache', false)) {
            $this->app->singleton(MenuLeftHandInterface::class, function () {
                return new MenuLeftHandCacheDecorator(new MenuLeftHandRepository(new MenuLeftHand()), new Cache($this->app['cache'], MenuLeftHandRepository::class));
            });
        } else {
            $this->app->singleton(MenuLeftHandInterface::class, function () {
                return new MenuLeftHandRepository(new MenuLeftHand());
            });
        }

        AliasLoader::getInstance()->alias('MenuLeftHand', MenuLeftHandFacade::class);
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/menu-left-hand.php', 'menu-left-hand');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'menu-left-hand');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'menu-left-hand');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/menu-left-hand')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/menu-left-hand')], 'lang');
            $this->publishes([__DIR__ . '/../../config/menu-left-hand.php' => config_path('menu-left-hand.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }
    }
}

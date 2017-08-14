<?php

namespace Botble\Block\Providers;

use Botble\Block\Models\Block;
use Botble\Block\Supports\Filter;
use Botble\Block\Supports\Shortcode;
use Illuminate\Support\ServiceProvider;
use Botble\Block\Repositories\Caches\BlockCacheDecorator;
use Botble\Block\Repositories\Eloquent\BlockRepository;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Base\Supports\Helper;

class BlockServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(BlockInterface::class, function () {
                return new BlockCacheDecorator(new BlockRepository(new Block()), new Cache($this->app['cache'], BlockRepository::class));
            });
        } else {
            $this->app->singleton(BlockInterface::class, function () {
                return new BlockRepository(new Block());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'block');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/block.php', 'block');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'block');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/block')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/block')], 'lang');
            $this->publishes([__DIR__ . '/../../config/block.php' => config_path('block.php')], 'config');
        }

        add_admin_menu([
            'route' => 'block.list',
            'sequence' => 2,
            'name' => trans('block::block.menu'),
            'kind' => 'page',
            'icon' => 'fa fa-code',
        ]);

        Shortcode::initialize();
        Filter::initialize();
    }
}

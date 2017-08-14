<?php

namespace Botble\Gallery\Providers;

use Botble\Base\Supports\Helper;
use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Models\GalleryMeta;
use Botble\Gallery\Repositories\Caches\GalleryMetaCacheDecorator;
use Botble\Gallery\Repositories\Eloquent\GalleryMetaRepository;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Illuminate\Support\ServiceProvider;
use Botble\Gallery\Repositories\Caches\GalleryCacheDecorator;
use Botble\Gallery\Repositories\Eloquent\GalleryRepository;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Gallery\Supports\Action;
use Botble\Gallery\Supports\Filter;

class GalleryServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(GalleryInterface::class, function () {
                return new GalleryCacheDecorator(new GalleryRepository(new Gallery()), new Cache($this->app['cache'], GalleryRepository::class));
            });

            $this->app->singleton(GalleryMetaInterface::class, function () {
                return new GalleryMetaCacheDecorator(new GalleryMetaRepository(new GalleryMeta()), new Cache($this->app['cache'], GalleryMetaRepository::class));
            });
        } else {
            $this->app->singleton(GalleryInterface::class, function () {
                return new GalleryRepository(new Gallery());
            });

            $this->app->singleton(GalleryMetaInterface::class, function () {
                return new GalleryMetaRepository(new GalleryMeta());
            });
        }
        
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/gallery.php', 'gallery');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'gallery');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'gallery');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/gallery')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/gallery')], 'lang');
            $this->publishes([__DIR__ . '/../../config/gallery.php' => config_path('gallery.php')], 'config');
        }

        Action::initialize();
        Filter::initialize();

        add_admin_menu([
            'route' => 'galleries.list',
            'sequence' => 4,
            'name' => trans('gallery::gallery.menu'),
            'kind' => 'page',
            'icon' => 'fa fa-camera',
        ]);
    }
}

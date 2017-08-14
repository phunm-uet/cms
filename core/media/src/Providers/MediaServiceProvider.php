<?php

namespace Botble\Media\Providers;

use Botble\Base\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\Media\Facades\MediaLibraryFacade;
use Botble\Media\Models\File;
use Botble\Media\Models\Folder;
use Botble\Media\Models\MediaShare;
use Botble\Media\Repositories\Caches\FileCacheDecorator;
use Botble\Media\Repositories\Caches\FolderCacheDecorator;
use Botble\Media\Repositories\Caches\MediaShareCacheDecorator;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Media\Repositories\Eloquent\MediaShareRepository;
use Botble\Media\Repositories\Eloquent\FileRepository;
use Botble\Media\Repositories\Eloquent\FolderRepository;
use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Botble\Media\Repositories\Interfaces\FolderInterface;

/**
 * Class MediaServiceProvider
 * @package Botble\Media
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class MediaServiceProvider extends ServiceProvider
{

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(FileInterface::class, function () {
                return new FileCacheDecorator(new FileRepository(new File()), new Cache($this->app['cache'], FileRepository::class));
            });

            $this->app->singleton(FolderInterface::class, function () {
                return new FolderCacheDecorator(new FolderRepository(new Folder()), new Cache($this->app['cache'], FolderRepository::class));
            });

            $this->app->singleton(MediaShareInterface::class, function () {
                return new MediaShareCacheDecorator(new MediaShareRepository(new MediaShare()), new Cache($this->app['cache'], MediaShareRepository::class));
            });
        } else {
            $this->app->singleton(FileInterface::class, function () {
                return new FileRepository(new File());
            });

            $this->app->singleton(FolderInterface::class, function () {
                return new FolderRepository(new Folder());
            });

            $this->app->singleton(MediaShareInterface::class, function () {
                return new MediaShareRepository(new MediaShare());
            });
        }
        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('MediaLibrary', MediaLibraryFacade::class);
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/media.php', 'media');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'media');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'media');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/media')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/media')], 'lang');
            $this->publishes([__DIR__ . '/../../config/media.php' => config_path('media.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }
    }
}

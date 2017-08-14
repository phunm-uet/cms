<?php

namespace Botble\Blog\Providers;

use Botble\Base\Supports\Helper;
use Botble\Blog\Models\Post;
use Botble\Blog\Repositories\Caches\PostCacheDecorator;
use Botble\Blog\Repositories\Eloquent\PostRepository;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Blog\Supports\Action;
use Botble\Blog\Supports\Filter;
use Illuminate\Support\ServiceProvider;
use Botble\Blog\Models\Category;
use Botble\Blog\Repositories\Caches\CategoryCacheDecorator;
use Botble\Blog\Repositories\Eloquent\CategoryRepository;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Models\Tag;
use Botble\Blog\Repositories\Caches\TagCacheDecorator;
use Botble\Blog\Repositories\Eloquent\TagRepository;
use Botble\Blog\Repositories\Interfaces\TagInterface;

/**
 * Class PostServiceProvider
 * @package Botble\Blog\Post
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class BlogServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(PostInterface::class, function () {
                return new PostCacheDecorator(new PostRepository(new Post()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(CategoryInterface::class, function () {
                return new CategoryCacheDecorator(new CategoryRepository(new Category()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(TagInterface::class, function () {
                return new TagCacheDecorator(new TagRepository(new Tag()), new Cache($this->app['cache'], __CLASS__));
            });
        } else {
            $this->app->singleton(PostInterface::class, function () {
                return new PostRepository(new Post());
            });

            $this->app->singleton(CategoryInterface::class, function () {
                return new CategoryRepository(new Category());
            });

            $this->app->singleton(TagInterface::class, function () {
                return new TagRepository(new Tag());
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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'blog');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'blog');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/blog')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/blog')], 'lang');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        Filter::initialize();
        Action::initialize();
    }
}

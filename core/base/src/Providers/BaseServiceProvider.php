<?php

namespace Botble\Base\Providers;

use Botble\ACL\Providers\AclServiceProvider;
use Botble\Assets\Providers\AssetsServiceProvider;
use Botble\Base\Exceptions\Handler;
use Botble\Base\Facades\ActionFacade;
use Botble\Base\Facades\AdminBarFacade;
use Botble\Base\Facades\AdminBreadcrumbFacade;
use Botble\Base\Facades\EmailHandlerFacade;
use Botble\Base\Facades\FilterFacade;
use Botble\Base\Facades\MetaBoxFacade;
use Botble\Base\Facades\PageTitleFacade;
use Botble\Base\Http\Middleware\AdminBarMiddleware;
use Botble\Base\Http\Middleware\DisableInDemoMode;
use Botble\Base\Http\Middleware\HttpsProtocol;
use Botble\Base\Http\Middleware\Locale;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Models\Plugin;
use Botble\Base\Repositories\Caches\MetaBoxCacheDecorator;
use Botble\Base\Repositories\Caches\PluginCacheDecorator;
use Botble\Base\Repositories\Eloquent\MetaBoxRepository;
use Botble\Base\Repositories\Eloquent\PluginRepository;
use Botble\Base\Repositories\Interfaces\MetaBoxInterface;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Base\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\Blog\Providers\BlogServiceProvider;
use Botble\Dashboard\Providers\DashboardServiceProvider;
use Botble\Media\Providers\MediaServiceProvider;
use Botble\Menu\Providers\MenuServiceProvider;
use Botble\MenuLeftHand\Providers\MenuLeftHandServiceProvider;
use Botble\Page\Providers\PageServiceProvider;
use Botble\SeoHelper\Providers\SeoHelperServiceProvider;
use Botble\Setting\Providers\SettingServiceProvider;
use Botble\Shortcode\Providers\ShortcodeServiceProvider;
use Botble\Theme\Providers\ThemeServiceProvider;
use Botble\Widget\Providers\WidgetServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MetaBox;
use Schema;

class BaseServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app->register(AssetsServiceProvider::class);
        $this->app->register(SettingServiceProvider::class);
        $this->app->register(ShortcodeServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', Locale::class);
        $router->pushMiddlewareToGroup('web', HttpsProtocol::class);
        $router->pushMiddlewareToGroup('web', AdminBarMiddleware::class);
        $router->aliasMiddleware('preventDemo', DisableInDemoMode::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('MetaBox', MetaBoxFacade::class);
        $loader->alias('Action', ActionFacade::class);
        $loader->alias('Filter', FilterFacade::class);
        $loader->alias('EmailHandler', EmailHandlerFacade::class);
        $loader->alias('AdminBar', AdminBarFacade::class);
        $loader->alias('PageTitle', PageTitleFacade::class);
        $loader->alias('AdminBreadcrumb', AdminBreadcrumbFacade::class);

        if (setting('enable_cache', false)) {
            $this->app->singleton(MetaBoxInterface::class, function () {
                return new MetaBoxCacheDecorator(new MetaBoxRepository(new MetaBoxModel()), new Cache($this->app['cache'], MetaBoxRepository::class));
            });

            $this->app->singleton(PluginInterface::class, function () {
                return new PluginCacheDecorator(new PluginRepository(new Plugin()), new Cache($this->app['cache'], PluginRepository::class));
            });

        } else {
            $this->app->singleton(MetaBoxInterface::class, function () {
                return new MetaBoxRepository(new MetaBoxModel());
            });

            $this->app->singleton(PluginInterface::class, function () {
                return new PluginRepository(new Plugin());
            });
        }

        $this->app->register(PluginServiceProvider::class);
    }

    /**
     * Boot the service provider.
     * @return void
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(MediaServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(MenuLeftHandServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(BlogServiceProvider::class);
        $this->app->register(SeoHelperServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(ThemeManagementServiceProvider::class);
        $this->app->register(BreadcrumbsServiceProvider::class);
        $this->app->register(ComposerServiceProvider::class);

        if ($this->app->environment() == 'local') {
            if (env('ENABLE_DEBUG_BAR') == true) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->mergeConfigFrom(__DIR__ . '/../../config/cms.php', 'cms');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bases');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'bases');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/bases')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/bases')], 'lang');
            $this->publishes([__DIR__ . '/../../config/cms.php' => config_path('cms.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 99, 3);

        Schema::defaultStringLength(191);

        $this->app->register(FormServiceProvider::class);

        do_action('init');
    }
}

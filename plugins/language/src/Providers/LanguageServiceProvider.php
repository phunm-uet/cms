<?php

namespace Botble\Language\Providers;

use Assets;
use Botble\Base\Supports\Helper;
use Botble\Language\Facades\LanguageFacade;
use Botble\Language\Http\Middleware\LocaleSessionRedirect;
use Botble\Language\Http\Middleware\LocalizationRedirectFilter;
use Botble\Language\Http\Middleware\LocalizationRoutes;
use Botble\Language\Models\Language as LanguageModel;
use Botble\Language\Models\LanguageMeta;
use Botble\Language\Repositories\Caches\LanguageMetaCacheDecorator;
use Botble\Language\Repositories\Eloquent\LanguageMetaRepository;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Botble\Language\Supports\Action;
use Botble\Language\Supports\Filter;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Language\Repositories\Caches\LanguageCacheDecorator;
use Botble\Language\Repositories\Eloquent\LanguageRepository;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Base\Services\Cache\Cache;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(LanguageInterface::class, function () {
                return new LanguageCacheDecorator(new LanguageRepository(new LanguageModel()), new Cache($this->app['cache'], LanguageRepository::class));
            });

            $this->app->singleton(LanguageMetaInterface::class, function () {
                return new LanguageMetaCacheDecorator(new LanguageMetaRepository(new LanguageMeta()), new Cache($this->app['cache'], LanguageMetaRepository::class));
            });
        } else {
            $this->app->singleton(LanguageInterface::class, function () {
                return new LanguageRepository(new LanguageModel());
            });

            $this->app->singleton(LanguageMetaInterface::class, function () {
                return new LanguageMetaRepository(new LanguageMeta());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('Language', LanguageFacade::class);


        $this->app['router']->aliasMiddleware('localize', LocalizationRoutes::class);
        $this->app['router']->aliasMiddleware('localizationRedirect', LocalizationRedirectFilter::class);
        $this->app['router']->aliasMiddleware('localeSessionRedirect', LocaleSessionRedirect::class);
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/language.php', 'language');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'language');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'language');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/language')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/language')], 'lang');
            $this->publishes([__DIR__ . '/../../config/language.php' => config_path('language.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/language')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/language')], 'assets');
        }

        Action::initialize();
        Filter::initialize();

        add_admin_menu([
            'route' => 'languages.list',
            'sequence' => 2,
            'name' => trans('language::language.menu'),
            'kind' => 'page',
            'icon' => null,
        ],
        [
            'route' => 'settings.options'
        ]);

        Assets::addJavascriptsDirectly('vendor/core/plugins/language/js/language-global.js');
    }
}

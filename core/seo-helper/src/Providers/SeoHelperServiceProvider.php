<?php

namespace Botble\SeoHelper\Providers;

use Botble\Base\Supports\Helper;
use Botble\SeoHelper\Contracts\SeoHelperContract;
use Botble\SeoHelper\Facades\SeoHelperFacade;
use Botble\SeoHelper\SeoHelper;
use Botble\SeoHelper\SeoMeta;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\SeoHelper\SeoTwitter;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\SeoHelper\Contracts\SeoMetaContract;
use Botble\SeoHelper\Contracts\SeoOpenGraphContract;
use Botble\SeoHelper\Contracts\SeoTwitterContract;

/**
 * Class SEOServiceProvider
 * @package Botble\SEO
 * @author Sang Nguyen
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {

        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);

        AliasLoader::getInstance()->alias('SeoHelper', SeoHelperFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/seo-helper.php', 'seo-helper');

        if (app()->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/seo-helper.php' => config_path('seo-helper.php')], 'config');
        }
    }
}

<?php

namespace Botble\Base\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Botble\Base\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace, 'middleware' => 'web'], function () {
            $this->group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

                $this->get('/', [
                    'as' => 'public.index',
                    'uses' => 'PublicController@getIndex',
                ]);

                $this->get('/{slug}.html', [
                    'as' => 'public.single.detail',
                    'uses' => 'PublicController@getView',
                ]);

                $this->get('/tag/{slug}.html', [
                    'as' => 'public.tag',
                    'uses' => 'PublicController@getByTag',
                ]);

                $this->get('/author/{slug}', [
                    'as' => 'public.author',
                    'uses' => 'PublicController@getAuthor',
                ]);

                $this->get('/api/search', [
                    'as' => 'public.api.search',
                    'uses' => 'PublicController@getApiSearch',
                ]);

                $this->get('/search', [
                    'as' => 'public.search',
                    'uses' => 'PublicController@getSearch',
                ]);

                $this->get('/sitemap.xml', [
                    'as' => 'public.sitemap',
                    'uses' => 'PublicController@getSitemap',
                ]);

            });
        });
    }
}

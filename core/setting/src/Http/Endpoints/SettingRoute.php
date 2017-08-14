<?php
namespace Botble\Setting\Http\Endpoints;

use Botble\Base\Supports\Routes\RouteRegister;
use Illuminate\Contracts\Routing\Registrar as Router;

class SettingRoute extends RouteRegister
{

    /**
     * Map all routes.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar $router
     * @author Sang Nguyen
     */
    public function map(Router $router)
    {
        $this->group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
            $this->group(['prefix' => 'settings'], function () {

                $this->get('/', [
                    'as' => 'settings.options',
                    'uses' => 'SettingController@getOptions',
                ]);

                $this->post('/edit', [
                    'as' => 'settings.edit',
                    'uses' => 'SettingController@postEdit',
                ]);

            });
        });
    }
}

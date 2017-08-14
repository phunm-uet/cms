<?php

Route::group(['namespace' => 'Botble\Base\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth', 'permission' => 'superuser'], function () {

        Route::group(['prefix' => 'system'], function () {

            Route::get('/', [
                'as' => 'system.options',
                'uses' => 'SystemController@getOptions',
            ]);

            Route::get('/info', [
                'as' => 'system.info',
                'uses' => 'SystemController@getInfo',
            ]);

        });

        Route::group(['prefix' => 'plugins'], function () {

            Route::get('/', [
                'as' => 'plugins.list',
                'uses' => 'SystemController@getListPlugins',
            ]);

            Route::get('/change}', [
                'as' => 'plugins.change.status',
                'uses' => 'SystemController@getChangePluginStatus',
                'middleware' => 'preventDemo',
            ]);

        });

    });

});
<?php

Route::group(['namespace' => 'Botble\MenuLeftHand\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'menu-left-hand', 'permission' => 'superuser'], function () {

            Route::get('/edit', [
                'as' => 'system.menu.left-hand',
                'uses' => 'MenuLeftHandController@getEdit',
            ]);

            Route::post('/edit', [
                'as' => 'system.menu.left-hand',
                'uses' => 'MenuLeftHandController@postEdit',
            ]);

            Route::get('/create', [
                'as' => 'system.menu.left-hand.item.create',
                'uses' => 'MenuLeftHandController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'system.menu.left-hand.item.create',
                'uses' => 'MenuLeftHandController@postCreate',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'system.menu.left-hand.item.delete',
                'uses' => 'MenuLeftHandController@getDelete',
            ]);

        });

    });
});
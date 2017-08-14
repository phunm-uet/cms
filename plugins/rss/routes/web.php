<?php

Route::group(['namespace' => 'Botble\Rss\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'rss'], function () {

            Route::get('/', [
                'as' => 'rss.list',
                'uses' => 'RssController@getList',
            ]);

            Route::get('/create', [
                'as' => 'rss.create',
                'uses' => 'RssController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'rss.create',
                'uses' => 'RssController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'rss.edit',
                'uses' => 'RssController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'rss.edit',
                'uses' => 'RssController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'rss.delete',
                'uses' => 'RssController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'rss.delete.many',
                'uses' => 'RssController@postDeleteMany',
                'permission' => 'rss.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'rss.change.status',
                'uses' => 'RssController@postChangeStatus',
                'permission' => 'rss.edit',
            ]);
        });
    });
    
});
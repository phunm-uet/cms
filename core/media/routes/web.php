<?php

Route::group(['namespace' => 'Botble\Media\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth', 'permission' => 'media.index'], function () {

        Route::group(['prefix' => 'media'], function () {

            Route::get('/', [
                'as' => 'media.index',
                'uses' => 'MediaController@getIndex',
            ]);

            Route::get('/quota', [
                'as' => 'files.quota.refresh',
                'uses' => 'MediaController@getQuota',
            ]);

            Route::get('/gallery', [
                'as' => 'files.gallery.show',
                'uses' => 'MediaController@getGallery',
            ]);

            Route::get('/folder', [
                'as' => 'files.gallery.ajax',
                'uses' => 'MediaController@getAjaxMediaFolder',
            ]);

            Route::get('/shared', [
                'as' => 'files.shared.show',
                'uses' => 'MediaController@getShared',
            ]);

        });

        Route::group(['prefix' => 'files'], function () {

            Route::post('/edit', [
                'as' => 'files.store',
                'uses' => 'FileController@postEdit',
            ]);

            Route::delete('/delete', [
                'as' => 'files.destroy',
                'uses' => 'FileController@deleteFile',
            ]);

            Route::post('/rename', [
                'as' => 'files.rename',
                'uses' => 'FileController@renameFile',
            ]);

        });

        Route::group(['prefix' => 'folders'], function () {

            Route::post('/create', [
                'as' => 'folders.create',
                'uses' => 'FolderController@postCreate',
            ]);

            Route::delete('/delete', [
                'as' => 'folders.delete',
                'uses' => 'FolderController@deleteFolder',
            ]);

            Route::post('/rename', [
                'as' => 'folders.rename',
                'uses' => 'FolderController@renameFolder',
            ]);

        });

        Route::group(['prefix' => 'shares'], function () {

            Route::get('/', [
                'as' => 'shares.list',
                'uses' => 'MediaShareController@getList',
            ]);

            Route::post('/remove', [
                'as' => 'share.remove',
                'uses' => 'MediaShareController@postDelete',
            ]);

            Route::post('/share', [
                'as' => 'item.share',
                'uses' => 'MediaShareController@postShare',
            ]);

            Route::get('/shared', [
                'as' => 'files.shared.withme.index',
                'uses' => 'MediaShareController@getSharedIndex',
            ]);

            Route::get('/shared-folder-with-me', [
                'as' => 'files.shared.withme.folder',
                'uses' => 'MediaShareController@getSharedWithMe',
            ]);

        });

    });
    
});
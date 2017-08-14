<?php

Route::group(['namespace' => 'Botble\ACL\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir')], function () {

        Route::group(['middleware' => 'guest'], function () {

            Route::get('/login', [
                'as' => 'access.login',
                'uses' => 'AuthController@getLogin',
            ]);

            Route::post('/login', [
                'as' => 'access.login',
                'uses' => 'AuthController@postLogin',
            ]);

            Route::get('/forgot-password', [
                'as' => 'access.forgot-password',
                'uses' => 'AuthController@getForgotPassword',
            ]);

            Route::post('/forgot-password', [
                'as' => 'access.forgot-password',
                'uses' => 'AuthController@postForgotPassword',
            ]);

            Route::get('/reset-password', [
                'as' => 'access.reset-password',
                'uses' => 'AuthController@getResetPassword',
            ]);

            Route::post('/reset-password', [
                'as' => 'access.reset-password',
                'uses' => 'AuthController@postResetPassword',
            ]);

            Route::get('/invite/accept/{token}', [
                'as' => 'invite.accept',
                'uses' => 'AuthController@getAcceptInvite',
            ]);

            Route::post('/invite/accept/{token}', [
                'as' => 'invite.accept',
                'uses' => 'AuthController@postAcceptInvite',
            ]);

        });

        Route::group(['middleware' => 'auth'], function () {

            Route::get('/logout', [
                'as' => 'access.logout',
                'uses' => 'AuthController@getLogout',
                'permission' => false,
            ]);

        });
    });

    Route::get('auth/{provider}', ['as' => 'auth.social', 'uses' => 'AuthController@redirectToProvider']);
    Route::get('auth/callback/{provider}', ['as' => 'auth.social.callback', 'uses' => 'AuthController@handleProviderCallback']);

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'users'], function () {

            Route::get('/', [
                'as' => 'users.list',
                'uses' => 'UserController@getList',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'users.delete',
                'uses' => 'UserController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'users.delete.many',
                'uses' => 'UserController@postDeleteMany',
                'permission' => 'users.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'users.change.status',
                'uses' => 'UserController@postChangeStatus',
                'permission' => 'users.edit',
            ]);

            Route::post('/update-profile/{id}', [
                'as' => 'users.update-profile',
                'uses' => 'UserController@postUpdateProfile',
                'permission' => false,
                'middleware' => 'preventDemo',
            ]);

            Route::post('/modify-profile-image', [
                'as' => 'users.profile.image',
                'uses' => 'UserController@postModifyProfileImage',
                'permission' => false,
            ]);

            Route::post('/change-password/{id}', [
                'as' => 'users.change-password',
                'uses' => 'UserController@postChangePassword',
                'permission' => false,
                'middleware' => 'preventDemo',
            ]);

            Route::get('/profile/{id}', [
                'as' => 'user.profile.view',
                'uses' => 'UserController@getUserProfile',
                'permission' => false,
            ]);

            Route::post('/invite', [
                'as' => 'invite.user',
                'uses' => 'UserController@postInviteUser',
                'permission' => 'users.create',
            ]);

        });

        Route::group(['prefix' => 'super-users', 'permission' => 'superuser'], function () {

            Route::get('/', [
                'as' => 'users-supers.list',
                'uses' => 'SuperUserController@getList',
            ]);

            Route::get('delete/{id}', [
                'as' => 'users-supers.delete',
                'uses' => 'SuperUserController@getDelete',
                'middleware' => 'preventDemo',
            ]);

            Route::post('delete-many', [
                'as' => 'users-supers.delete.many',
                'uses' => 'SuperUserController@postDeleteMany',
                'middleware' => 'preventDemo',
            ]);

            Route::post('create', [
                'as' => 'users-supers.create',
                'uses' => 'SuperUserController@postCreate',
            ]);

        });

    });

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'features', 'permission' => 'superuser'], function () {

            Route::get('/', [
                'as' => 'system.feature.list',
                'uses' => 'FeatureController@getList',
            ]);

            Route::get('/edit', [
                'as' => 'system.feature.edit',
                'uses' => 'FeatureController@getEdit',
            ]);

            Route::post('/edit', [
                'as' => 'system.feature.edit',
                'uses' => 'FeatureController@postEdit',
            ]);
        });

        Route::group(['prefix' => 'roles'], function () {

            Route::get('/', [
                'as' => 'roles.list',
                'uses' => 'RoleController@getList',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'roles.delete',
                'uses' => 'RoleController@getDelete',
            ]);

            Route::post('/delete/many', [
                'as' => 'roles.delete.many',
                'uses' => 'RoleController@postDeleteMany',
                'permission' => 'roles.delete',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'roles.edit',
                'uses' => 'RoleController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'roles.edit',
                'uses' => 'RoleController@postEdit',
            ]);

            Route::get('/create', [
                'as' => 'roles.create',
                'uses' => 'RoleController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'roles.create',
                'uses' => 'RoleController@postCreate',
            ]);

            Route::get('/duplicate/{id}', [
                'as' => 'roles.duplicate',
                'uses' => 'RoleController@getDuplicate',
                'permission' => 'roles.create',
            ]);

            Route::get('/json', [
                'as' => 'roles.list.json',
                'uses' => 'RoleController@getJson',
                'permission' => 'roles.list',
            ]);

            Route::post('/assign', [
                'as' => 'roles.assign',
                'uses' => 'RoleController@postAssignMember',
                'permission' => 'roles.edit',
            ]);

        });
    });

    Route::get('/admin-language/{alias}', [
        'as' => 'admin.language',
        'uses' => 'UserController@getLanguage'
    ]);

    Route::get('/admin-theme/{theme}', [
        'as' => 'admin.theme',
        'uses' => 'UserController@getTheme'
    ]);
    
});
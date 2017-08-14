<?php

namespace Botble\Base\Providers;

use Assets;
use Botble\ACL\Models\UserMeta;
use MenuLeftHand;
use Botble\MenuLeftHand\Models\MenuLeftHand as MenuLeftHandModel;
use Illuminate\Support\ServiceProvider;
use Sentinel;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        view()->composer(['bases::layouts.partials.top-header', 'bases::layouts.base'], function ($view) {
            $themes = Assets::getThemes();
            $locales = Assets::getAdminLocales();

            if (Sentinel::check()) {
                $active_theme = UserMeta::getMeta('admin-theme', config('cms.default-theme'));
            } elseif (session()->has('admin-theme')) {
                $active_theme = session('admin-theme');
            } else {
                $active_theme = config('cms.default-theme');
            }

            if (!array_key_exists($active_theme, $themes)) {
                $active_theme = config('cms.default-theme');
            }

            $view->with(compact('themes', 'locales', 'active_theme'));
        });

        view()->composer('bases::layouts.partials.sidebar', function ($view) {

            if (!session()->has('menu_left_hand')) {
                MenuLeftHandModel::buildMenu();
            }

            $menuLeftHand = MenuLeftHand::getData();

            $view->with(compact('menuLeftHand'));
        });

        view()->composer(['bases::layouts.base'], function ($view) {

            do_action(BASE_ACTION_ENQUEUE_SCRIPTS);

            $headScripts = Assets::getJavascript('top');
            $bodyScripts = Assets::getJavascript('bottom');
            $appModules = Assets::getAppModules();

            $groupedBodyScripts = array_merge($bodyScripts, $appModules);

            $view->with('headScripts', $headScripts);
            $view->with('bodyScripts', $groupedBodyScripts);
            $view->with('stylesheets', Assets::getStylesheets(['core']));
        });

    }
}

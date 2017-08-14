<?php

namespace Botble\Base\Providers;

use Breadcrumbs;
use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {

        Breadcrumbs::register('', function ($breadcrumbs) {
            $breadcrumbs->push('', '');
        });

        Breadcrumbs::register('dashboard.index', function ($breadcrumbs) {
            $breadcrumbs->push(trans('bases::layouts.dashboard'), route('dashboard.index'));
        });

        /**
         * Register breadcrumbs based on menu stored in session
         * @author Sang Nguyen
         */
        Breadcrumbs::register('pageTitle', function ($breadcrumbs, $defaultTitle = 'pageTitle', $route) {

            $jsonMenu = session()->get('menu_left_hand');
            if (!empty($jsonMenu)) {
                $arMenu = json_decode($jsonMenu);
            }
            $breadcrumbs->parent('dashboard.index');
            $found = false;
            if (isset($arMenu)) {
                foreach ($arMenu as $menuCategory) {
                    if ($route == $menuCategory->route && !empty($menuCategory->name)) {
                        $found = true;
                        $breadcrumbs->push($menuCategory->name, $route);
                        break;
                    }
                }
                if (!$found) {
                    foreach ($arMenu as $menuCategory) {
                        if (isset($menuCategory->items)) {
                            foreach ($menuCategory->items as $menuItem) {
                                if ($route == $menuItem->route && !empty($menuItem->name)) {
                                    $found = true;
                                    $breadcrumbs->push($menuCategory->name, $menuCategory->route);
                                    $breadcrumbs->push($menuItem->name, $route);
                                    break;
                                }
                            }
                        }
                    }
                }

                if (!$found) {
                    $breadcrumbs->push($defaultTitle, $route);
                }
            }
        });

        do_action(BASE_ACTION_REGISTER_BREADCRUMBS);
    }
}

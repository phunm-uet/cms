<?php

if (!function_exists('add_admin_menu')) {
    /**
     * @param array $args
     * @param array $parent
     * @author Sang Nguyen
     */
    function add_admin_menu(array $args, array $parent = [])
    {
        view()->composer('bases::layouts.partials.sidebar', function ($view) use ($args, $parent) {

            MenuLeftHand::addItem($args, $parent);

            $menuLeftHand = MenuLeftHand::getData();

            $view->with(compact('menuLeftHand'));
        });
    }
}


if (!function_exists('delete_admin_menu')) {
    /**
     * @param array $condition
     * @author Sang Nguyen
     */
    function delete_admin_menu(array $condition)
    {
        view()->composer('bases::layouts.partials.sidebar', function ($view) use ($condition) {

            MenuLeftHand::removeItem($condition);

            $menuLeftHand = MenuLeftHand::getData();

            $view->with(compact('menuLeftHand'));
        });
    }
}

if (!function_exists('check_active_menu')) {
    /**
     * @param $menu
     * @author Sang Nguyen
     */
    function check_active_menu($menu) {
        $is_active = false;
        if (isset($menu->items)) {
            foreach ($menu->items as $sub_menu) {
                if ($sub_menu->route == Route::currentRouteName()) {
                    $is_active = true;
                    break;
                }
            }
        }
        return $is_active;
    }
}
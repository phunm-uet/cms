<?php

if (!function_exists('setting')) {
    /**
     * Get the setting instance.
     *
     * @param $key
     * @param $default
     * @return array|\Botble\Setting\Setting
     * @author Sang Nguyen
     */
    function setting($key = null, $default = null)
    {
        if (!empty($key)) {
            return Setting::get($key, $default);
        }
        return app(\Botble\Setting\Setting::class);
    }
}

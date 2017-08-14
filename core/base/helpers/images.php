<?php

if (!function_exists('get_image')) {
    /**
     * @param $image
     * @param null $default
     * @return string
     */
    function get_image($image, $default = null)
    {
        if (!$image) {
            if (!$default) {
                return config('theme-assets.default_image');
            }
            return $default;
        }

        return $image;
    }
}

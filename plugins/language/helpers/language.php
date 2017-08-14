<?php

use Botble\Language\Repositories\Interfaces\LanguageInterface;

if (!function_exists('get_active_languages')) {
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    function get_active_languages()
    {
        return app(LanguageInterface::class)->all();
    }
}

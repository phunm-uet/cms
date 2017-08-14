<?php

namespace Botble\Block\Supports;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {

        if (defined('LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE')) {
            add_filter(LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE, [$this, 'addMultiLanguage'], 70, 1);
        }
    }

    /**
     * Trigger __construct function
     *
     * @return Filter
     */
    public static function initialize()
    {
        return new self();
    }

    /**
     * @param $languages
     * @return array
     * @author Sang Nguyen
     */
    public function addMultiLanguage($languages)
    {
        return array_merge($languages, [BLOCK_MODULE_SCREEN_NAME]);
    }
}
<?php

namespace Botble\Media;

use Assets;

class MediaLibrary
{
    /**
     * Load all assets for media library
     *
     * @author Sang Nguyen
     * @since 19/08/2015 07:00 AM
     * @modified 03/02/2017 11:01 AM
     */
    public function registerMediaLibrary()
    {
        Assets::addJavascript(['fancybox', 'floatThead', 'uploader', 'videojs', 'selectables']);
        Assets::addStylesheets(['floatThead', 'fancybox']);
        Assets::addAppModule(['media']);
    }
}
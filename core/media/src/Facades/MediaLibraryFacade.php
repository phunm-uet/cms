<?php

namespace Botble\Media\Facades;

use Botble\Media\MediaLibrary;
use Illuminate\Support\Facades\Facade;

class MediaLibraryFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author Sang Nguyen
     */
    protected static function getFacadeAccessor()
    {
        return MediaLibrary::class;
    }
}

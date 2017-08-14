<?php

namespace Botble\Widget\Facades;

use Botble\Widget\WidgetGroup;
use Illuminate\Support\Facades\Facade;

class WidgetGroupFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'botble.widget-group-collection';
    }
}

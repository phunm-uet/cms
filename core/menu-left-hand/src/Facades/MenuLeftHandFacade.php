<?php
namespace Botble\MenuLeftHand\Facades;

use Botble\MenuLeftHand\MenuLeftHand;
use Illuminate\Support\Facades\Facade;

/**
 * Class MetaBoxFacade
 * @package Botble\Base
 */
class MenuLeftHandFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     * @since 10-10-2016
     */
    protected static function getFacadeAccessor()
    {
        return MenuLeftHand::class;
    }
}

<?php

namespace Botble\MenuLeftHand\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface MenuLeftHandInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getMenuNotRootArray();
}

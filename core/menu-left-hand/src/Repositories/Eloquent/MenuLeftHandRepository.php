<?php

namespace Botble\MenuLeftHand\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\MenuLeftHand\Repositories\Interfaces\MenuLeftHandInterface;

/**
 * Class MenuLeftHandRepository
 * @package Botble\MenuLeftHand
 */
class MenuLeftHandRepository extends RepositoriesAbstract implements MenuLeftHandInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getMenuNotRootArray()
    {
        return $this->model->where('kind', '!=', 'root')->pluck('name', 'id')->all();
    }
}

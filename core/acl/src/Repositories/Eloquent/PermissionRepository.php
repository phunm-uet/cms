<?php

namespace Botble\ACL\Repositories\Eloquent;

use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;

/**
 * Class PermissionRepository
 * @package Botble\ACL\Repositories
 */
class PermissionRepository extends RepositoriesAbstract implements PermissionInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisibleFeatures()
    {
        return $this->model->orderBy('name')
            ->whereIsFeature(1)
            ->whereFeatureVisible(1)
            ->get();
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisiblePermissions()
    {
        return $this->model->orderBy('name')
            ->whereFeatureVisible(1)
            ->get();
    }
}

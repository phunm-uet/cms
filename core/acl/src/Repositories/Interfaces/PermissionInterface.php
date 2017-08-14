<?php

namespace Botble\ACL\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface PermissionInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisibleFeatures();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisiblePermissions();
}

<?php

namespace Botble\ACL\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * Get unique username from email
     *
     * @param $email
     * @return string
     * @author Sang Nguyen
     */
    public function getUniqueUsernameFromEmail($email);
}

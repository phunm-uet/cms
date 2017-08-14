<?php

namespace Botble\ACL\Repositories\Eloquent;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;

class UserRepository extends RepositoriesAbstract implements UserInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        return $this->model->where('username', '!=', null)
            ->select('username', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unique username from email
     *
     * @param $email
     * @return string
     * @author Sang Nguyen
     */
    public function getUniqueUsernameFromEmail($email)
    {
        $emailPrefix = substr($email, 0, strpos($email, '@'));
        $username = $emailPrefix;
        $offset = 1;
        while ($this->getFirstBy(['username' => $username])) {
            $username = $emailPrefix . $offset;
            $offset++;
        }
        return $username;
    }
}

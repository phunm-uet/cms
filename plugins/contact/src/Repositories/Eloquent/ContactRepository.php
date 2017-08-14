<?php

namespace Botble\Contact\Repositories\Eloquent;

use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;

class ContactRepository extends RepositoriesAbstract implements ContactInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getUnread()
    {
        return $this->model->where('is_read', 0)->get();
    }

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function countUnread()
    {
        return $this->model->where('is_read', 0)->count();
    }
}

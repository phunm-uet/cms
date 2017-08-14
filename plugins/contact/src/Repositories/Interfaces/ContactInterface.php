<?php
namespace Botble\Contact\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface ContactInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getUnread();

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function countUnread();
}

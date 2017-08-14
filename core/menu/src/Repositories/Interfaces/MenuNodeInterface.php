<?php

namespace Botble\Menu\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface MenuNodeInterface extends RepositoryInterface
{
    /**
     * @param $menu_content_id
     * @param $parent_id
     * @param null $selects
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getByMenuContentId($menu_content_id, $parent_id, $selects = null);
}

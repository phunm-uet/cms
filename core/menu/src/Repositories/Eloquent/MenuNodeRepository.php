<?php
namespace Botble\Menu\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;

class MenuNodeRepository extends RepositoriesAbstract implements MenuNodeInterface
{
    /**
     * @param $menu_content_id
     * @param $parent_id
     * @param null $selects
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getByMenuContentId($menu_content_id, $parent_id, $selects = null)
    {
        return $this->model->where(['menu_content_id' => $menu_content_id, 'parent_id' => $parent_id])
            ->select($selects)
            ->orderBy('position', 'asc')->get();
    }
}

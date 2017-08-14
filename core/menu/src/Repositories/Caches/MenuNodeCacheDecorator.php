<?php
namespace Botble\Menu\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Botble\Base\Services\Cache\CacheInterface;

class MenuNodeCacheDecorator extends CacheAbstractDecorator implements MenuNodeInterface
{
    /**
     * @var MenuNodeInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * MenuCacheDecorator constructor.
     * @param MenuNodeInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MenuNodeInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param $menu_content_id
     * @param $parent_id
     * @param null $selects
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByMenuContentId($menu_content_id, $parent_id, $selects = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

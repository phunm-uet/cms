<?php

namespace Botble\MenuLeftHand\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\MenuLeftHand\Repositories\Interfaces\MenuLeftHandInterface;
use Botble\Base\Services\Cache\CacheInterface;

class MenuLeftHandCacheDecorator extends CacheAbstractDecorator implements MenuLeftHandInterface
{
    /**
     * @var MenuLeftHandInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * MenuLeftHandCacheDecorator constructor.
     * @param MenuLeftHandInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MenuLeftHandInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getMenuNotRootArray()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

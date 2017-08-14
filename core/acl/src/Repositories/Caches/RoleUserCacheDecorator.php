<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Base\Services\Cache\CacheInterface;

class RoleUserCacheDecorator extends CacheAbstractDecorator implements RoleUserInterface
{
    /**
     * @var RoleUserInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * UserCacheDecorator constructor.
     * @param RoleUserInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(RoleUserInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}

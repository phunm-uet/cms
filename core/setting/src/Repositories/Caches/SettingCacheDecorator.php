<?php

namespace Botble\Setting\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Base\Services\Cache\CacheInterface;
use Botble\Setting\Repositories\Interfaces\SettingInterface;

class SettingCacheDecorator extends CacheAbstractDecorator implements SettingInterface
{
    /**
     * @var SettingInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * SettingCacheDecorator constructor.
     * @param SettingInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(SettingInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}

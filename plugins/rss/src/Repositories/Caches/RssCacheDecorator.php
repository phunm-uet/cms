<?php

namespace Botble\Rss\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Base\Services\Cache\CacheInterface;
use Botble\Rss\Repositories\Interfaces\RssInterface;

class RssCacheDecorator extends CacheAbstractDecorator implements RssInterface
{
    /**
     * @var RssInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * RssCacheDecorator constructor.
     * @param RssInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(RssInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}

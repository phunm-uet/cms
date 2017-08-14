<?php

namespace Botble\Gallery\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Botble\Base\Services\Cache\CacheInterface;

class GalleryMetaCacheDecorator extends CacheAbstractDecorator implements GalleryMetaInterface
{
    /**
     * @var GalleryMetaInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * GalleryCacheDecorator constructor.
     * @param GalleryMetaInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(GalleryMetaInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}

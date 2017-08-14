<?php

namespace Botble\Gallery\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Base\Services\Cache\CacheInterface;

class GalleryCacheDecorator extends CacheAbstractDecorator implements GalleryInterface
{
    /**
     * @var GalleryInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * GalleryCacheDecorator constructor.
     * @param GalleryInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(GalleryInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * Get all galleries.
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAll()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFeaturedGalleries($limit)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

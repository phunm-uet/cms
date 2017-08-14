<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Botble\Base\Services\Cache\CacheInterface;

class FolderCacheDecorator extends CacheAbstractDecorator implements FolderInterface
{
    /**
     * @var FolderInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * FolderCacheDecorator constructor.
     * @param FolderInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(FolderInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param $folderId
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFolderByParentId($folderId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @author Sang Nguyen
     * @return mixed
     */
    public function createSlug($name)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @param $parent
     * @author Sang Nguyen
     * @return mixed
     */
    public function createName($name, $parent)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}

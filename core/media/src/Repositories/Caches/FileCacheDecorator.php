<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Botble\Base\Services\Cache\CacheInterface;

class FileCacheDecorator extends CacheAbstractDecorator implements FileInterface
{

    /**
     * @var FileInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * FileCacheDecorator constructor.
     * @param FileInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(FileInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceUsed()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceLeft()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getQuota()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPercentageUsed()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createName($name, $folder)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @param $extension
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $extension, $folder)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder_id
     * @param array $type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFilesByFolderId($folder_id, $type = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Base\Services\Cache\CacheInterface;

class MediaShareCacheDecorator extends CacheAbstractDecorator implements MediaShareInterface
{
    /**
     * @var MediaShareInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * MediaShareCacheDecorator constructor.
     * @param MediaShareInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MediaShareInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param $share_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getShareWithUser($share_id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $shareId
     * @param $shareType
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFileShares($shareId, $shareType)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $file
     * @author Sang Nguyen
     * @return mixed
     */
    public function unshareFile($file)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function getMyShareDirectory($folder)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

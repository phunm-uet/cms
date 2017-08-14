<?php

namespace Botble\Contact\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Base\Services\Cache\CacheInterface;

class ContactCacheDecorator extends CacheAbstractDecorator implements ContactInterface
{

    /**
     * @var ContactInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * ContactCacheDecorator constructor.
     * @param ContactInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(ContactInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getUnread()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function countUnread()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

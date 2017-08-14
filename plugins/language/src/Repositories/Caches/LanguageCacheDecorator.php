<?php

namespace Botble\Language\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Base\Services\Cache\CacheInterface;
use Botble\Language\Repositories\Interfaces\LanguageInterface;

class LanguageCacheDecorator extends CacheAbstractDecorator implements LanguageInterface
{
    /**
     * @var LanguageInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * LanguageCacheDecorator constructor.
     * @param LanguageInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(LanguageInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getActiveLanguage()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getDefaultLanguage()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

<?php
namespace Botble\Dashboard\Repositories\Caches;

use Botble\Base\Repositories\Caches\CacheAbstractDecorator;
use Botble\Base\Services\Cache\CacheInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    /**
     * @var DashboardWidgetSettingInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * WidgetCacheDecorator constructor.
     * @param DashboardWidgetSettingInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(DashboardWidgetSettingInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
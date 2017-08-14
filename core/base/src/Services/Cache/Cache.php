<?php

namespace Botble\Base\Services\Cache;

use Illuminate\Cache\CacheManager;

class Cache implements CacheInterface
{
    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var null
     */
    protected $minutes;

    /**
     * @var string
     */
    protected $cacheGroup;

    /**
     * Cache constructor.
     * @param CacheManager $cache
     * @param null $cacheGroup
     * @param boolean $minutes
     * @author Sang Nguyen
     */
    public function __construct(CacheManager $cache, $cacheGroup, $minutes = false)
    {
        $this->cache = $cache;
        $this->cacheGroup = $cacheGroup;
        $this->minutes = $minutes ? $minutes : setting('cache_time', 10);
    }

    public function generateCacheKey($key)
    {
        return md5($this->cacheGroup) . $key;
    }

    /**
     * Retrieve data from cache.
     *
     * @param string $key Cache item key
     * @return mixed
     * @author Sang Nguyen
     */
    public function get($key)
    {
        return $this->cache->get($this->generateCacheKey($key));
    }

    /**
     * Add data to the cache.
     *
     * @param string $key Cache item key
     * @param mixed $value The data to store
     * @param boolean $minutes The number of minutes to store the item
     * @return mixed
     * @author Sang Nguyen
     */
    public function put($key, $value, $minutes = false)
    {
        if (!$minutes) {
            $minutes = $this->minutes;
        }

        $key = $this->generateCacheKey($key);

        $this->storeCacheKey($key);

        return $this->cache->put($key, $value, $minutes);
    }

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired.
     *
     * @param string $key Cache item key
     * @return bool If cache item exists
     * @author Sang Nguyen
     */
    public function has($key)
    {
        $key = $this->generateCacheKey($key);
        return $this->cache->has($key);
    }

    /**
     * Store cache key to file
     *
     * @param $key
     * @return void
     * @author Sang Nguyen, Tedozi Manson
     */
    public function storeCacheKey($key)
    {
        if (file_exists(config('cms.cache_store_keys'))) {
            $cacheKeys = get_file_data(config('cms.cache_store_keys'));
            if (!empty($cacheKeys) && !in_array($key, array_get($cacheKeys, $this->cacheGroup, []))) {
                $cacheKeys[$this->cacheGroup][] = $key;
            }
        } else {
            $cacheKeys = [];
            $cacheKeys[$this->cacheGroup][] = $key;
        }
        save_file_data(config('cms.cache_store_keys'), $cacheKeys);
    }

    /**
     * Clear cache of an object
     *
     * @author Sang Nguyen, Tedozi Manson
     */
    public function flush()
    {
        $cacheKeys = [];
        if (file_exists(config('cms.cache_store_keys'))) {
            $cacheKeys = get_file_data(config('cms.cache_store_keys'));
        }
        if (!empty($cacheKeys)) {
            $caches = array_get($cacheKeys, $this->cacheGroup);
            if ($caches) {
                foreach ($caches as $cache) {
                    $this->cache->forget($cache);
                }
                unset($cacheKeys[$this->cacheGroup]);
            }
        }
        save_file_data(config('cms.cache_store_keys'), $cacheKeys);
    }
}

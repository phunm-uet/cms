<?php

namespace Botble\Base\Repositories\Caches;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;
use EmailHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;

abstract class CacheAbstractDecorator implements RepositoryInterface
{
    /**
     * @var mixed
     */
    protected $repository;

    /**
     * @var mixed
     */
    protected $cache;

    /**
     * @return mixed
     */
    public function getCacheInstance()
    {
        return $this->cache;
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function fill(array $fields)
    {
        return $this->getDataWithoutCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getModel()
    {
        return $this->repository->getModel();
    }

    /**
     * Get table name.
     *
     * @return string
     * @author Sang Nguyen
     */
    public function getTable()
    {
        return $this->repository->getTable();
    }

    /**
     * Make a new instance of the entity to query on.
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function make(array $with = [])
    {
        return $this->repository->make($with);
    }

    /**
     * Retrieve model by id regardless of status.
     *
     * @param int $id model ID
     * @param array $with
     * @return Object object of model information
     * @author Sang Nguyen
     */
    public function findById($id, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $function
     * @param $args
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataIfExistCache($function, array $args)
    {

        try {
            $cacheKey = md5(get_class($this) . $function . serialize(request()->all()) . serialize(func_get_args()));

            if ($this->cache->has($cacheKey)) {
                return $this->cache->get($cacheKey);
            }

            $cacheData = call_user_func_array([$this->repository, $function], $args);

            // Store in cache for next request
            $this->cache->put($cacheKey, $cacheData);

            return $cacheData;
        } catch (Exception $ex) {
            info($ex->getMessage());
            EmailHandler::sendErrorException($ex);
            return call_user_func_array([$this->repository, $function], $args);
        }
    }

    /**
     * @param $function
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataWithoutCache($function, array $args)
    {
        return call_user_func_array([$this->repository, $function], $args);
    }

    /**
     * Find a single entity by key value.
     *
     * @param array $condition
     * @param array $select
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFirstBy(array $condition = [], array $select = [], array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param string $column
     * @param string $key
     * @return mixed
     * @author Sang Nguyen
     */
    public function pluck($column, $key = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * Get all models.
     *
     * @param array $with Eager load related models
     * @return mixed
     * @author Sang Nguyen
     */
    public function all(array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * Get all models by key/value.
     *
     * @param array $condition
     * @param array $with
     * @return Object with $items
     * @author Sang Nguyen
     */
    public function allBy(array $condition = [], array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * Get single model by slug.
     *
     * @param string $slug of model
     * @param array $with
     * @return object object of model information
     * @author Sang Nguyen
     */
    public function bySlug($slug, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $data
     * @return mixed
     * @author Sang Nguyen
     */
    public function create(array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $function
     * @param $args
     * @param boolean $flushCache
     * @author Sang Nguyen
     * @return mixed
     */
    public function flushCacheAndUpdateData($function, $args, $flushCache = true)
    {
        if ($flushCache) {
            $this->cache->flush();
        }

        return call_user_func_array([$this->repository, $function], $args);
    }

    /**
     * Create a new model.
     *
     * @param Model $model
     * @return mixed Model or false on error during save
     * @author Sang Nguyen
     */
    public function createOrUpdate(Model $model)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * Delete model.
     *
     * @param Model $model
     * @return bool
     * @author Sang Nguyen
     */
    public function delete(Model $model)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $data
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function firstOrCreate(array $data, array $with = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $condition
     * @param array $data
     * @return mixed
     * @author Sang Nguyen
     */
    public function update(array $condition, array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $select
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function select(array $select = ['*'], array $condition = [])
    {
        return $this->getDataWithoutCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteBy(array $condition = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function count(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $column
     * @param array $value
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getByWhereIn($column, array $value = [], array $args = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

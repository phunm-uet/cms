<?php

namespace Botble\Base\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array $fields
     * @return mixed
     */
    public function fill(array $fields);

    /**
     * Get empty model.
     * @return mixed
     * @author Sang Nguyen
     */
    public function getModel();

    /**
     * Get table name.
     *
     * @return string
     * @author Sang Nguyen
     */
    public function getTable();

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     * @author Sang Nguyen
     */
    public function make(array $with = []);

    /**
     * Find a single entity by key value.
     *
     * @param array $condition
     * @param array $select
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFirstBy(array $condition = [], array $select = [], array $with = []);

    /**
     * Retrieve model by id regardless of status.
     *
     * @param $id
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function findById($id, array $with = []);

    /**
     * @param string $column
     * @param string $key
     * @return mixed
     * @author Sang Nguyen
     */
    public function pluck($column, $key = null);

    /**
     * Get all models.
     *
     * @param array $with Eager load related models
     * @return mixed
     * @author Sang Nguyen
     */
    public function all(array $with = []);

    /**
     * Get all models by key/value.
     *
     * @param array $condition
     * @param array $with
     * @return array
     * @author Sang Nguyen
     */
    public function allBy(array $condition, array $with = []);

    /**
     * Get single model by Slug.
     *
     * @param string $slug slug
     * @param array $with related tables
     * @return mixed
     * @author Sang Nguyen
     */
    public function bySlug($slug, array $with = []);

    /**
     * @param array $data
     * @return mixed
     * @author Sang Nguyen
     */
    public function create(array $data);

    /**
     * Create a new model.
     *
     * @param Model $model
     * @return mixed Model or false on error during save
     * @author Sang Nguyen
     */
    public function createOrUpdate(Model $model);

    /**
     * Delete model.
     *
     * @param Model $model
     * @return bool
     * @author Sang Nguyen
     */
    public function delete(Model $model);

    /**
     * @param array $data
     * @param array $with
     * @return mixed
     * @author Sang Nguyen
     */
    public function firstOrCreate(array $data, array $with = []);

    /**
     * @param array $condition
     * @param array $data
     * @return mixed
     * @author Sang Nguyen
     */
    public function update(array $condition, array $data);

    /**
     * @param array $select
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function select(array $select = ['*'], array $condition = []);

    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteBy(array $condition = []);

    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    public function count(array $condition = []);

    /**
     * @param $column
     * @param array $value
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getByWhereIn($column, array $value = [], array $args = []);
}

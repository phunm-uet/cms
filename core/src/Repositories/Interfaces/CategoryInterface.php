<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface CategoryInterface extends RepositoryInterface
{

    /**
     * @param $name
     * @param $id
     * @author Sang Nguyen
     */
    public function createSlug($name, $id);

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status);

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedCategories($limit);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllCategories($active = true);

    /**
     * @param $id
     * @return mixed
     */
    public function getCategoryById($id);
}

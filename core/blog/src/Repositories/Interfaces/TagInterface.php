<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface TagInterface extends RepositoryInterface
{

    /**
     * @param $name
     * @param $id
     * @return mixed
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
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPopularTags($limit);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllTags($active = true);
}

<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\TagInterface;

class TagRepository extends RepositoriesAbstract implements TagInterface
{

    /**
     * @param $name
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereSlug($slug)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        return $slug;
    }

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status)
    {
        $data = $this->model->where(['tags.status' => $status, 'tags.slug' => $slug])
            ->select('tags.*')->first();
        return apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, TAG_MODULE_SCREEN_NAME);
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('tags.status', '=', 1)
            ->select('tags.*')
            ->orderBy('tags.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPopularTags($limit)
    {
        $data = $this->model->orderBy('tags.id', 'DESC')
            ->select('tags.*')
            ->limit($limit);
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllTags($active = true)
    {
        $data = $this->model->select('tags.*');
        if ($active) {
            $data = $data->where(['tags.status' => 1]);
        }

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)
            ->get();
    }
}

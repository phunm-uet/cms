<?php

namespace Botble\Page\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Page\Repositories\Interfaces\PageInterface;

class PageRepository extends RepositoriesAbstract implements PageInterface
{

    /**
     * @param $name
     * @param $id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $id)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereSlug($slug)->where('id', '!=', $id)->count() > 0) {
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
        $data = $this->model->where(['pages.status' => $status, 'pages.slug' => $slug])
            ->select('pages.*')->first();
        return apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, 'page');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->whereStatus(1)
            ->select('pages.*')
            ->orderBy('pages.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedPages($limit)
    {
        $data = $this->model->where(['pages.status' => 1, 'pages.featured' => 1])
            ->orderBy('pages.order', 'asc')
            ->select('pages.*')
            ->limit($limit)
            ->orderBy('pages.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $array
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function whereIn($array, $select = [])
    {
        $pages = $this->model->whereIn('pages.id', $array);
        if (empty($select)) {
            $select = 'pages.*';
        }
        $data = $pages->select($select)->orderBy('pages.order', 'ASC');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10)
    {
        $pages = $this->model->whereStatus(1);
        foreach (explode(' ', $query) as $term) {
            $pages = $pages->where('pages.name', 'LIKE', '%' . $term . '%');
        }

        $data = $pages->select('pages.*')->orderBy('pages.created_at', 'desc')
            ->limit($limit);
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPages($active = true)
    {
        $data = $this->model->select('pages.*');
        if ($active) {
            $data = $data->where(['pages.status' => 1]);
        }

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)
            ->get();
    }
}

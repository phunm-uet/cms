<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;

class CategoryRepository extends RepositoriesAbstract implements CategoryInterface
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
        $data = $this->model->where(['categories.status' => $status, 'categories.slug' => $slug])
            ->select('categories.*')->first();
        return apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, CATEGORY_MODULE_SCREEN_NAME);
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('categories.status', '=', 1)
            ->select('categories.*')
            ->orderBy('categories.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, CATEGORY_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedCategories($limit)
    {
        $data = $this->model->where(['categories.status' => 1, 'categories.featured' => 1])
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.icon')
            ->orderBy('categories.order', 'asc')
            ->select('categories.*')
            ->limit($limit);
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, CATEGORY_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllCategories($active = true)
    {
        $data = $this->model->select('categories.*');
        if ($active) {
            $data = $data->where(['categories.status' => 1]);
        }

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, CATEGORY_MODULE_SCREEN_NAME)
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategoryById($id)
    {
        $data = $this->model->where(['categories.id' => $id, 'categories.status' => 1]);

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, CATEGORY_MODULE_SCREEN_NAME)->first();
    }
}

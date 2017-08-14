<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\PostInterface;

class PostRepository extends RepositoriesAbstract implements PostInterface
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
        $data = $this->model->where(['posts.status' => $status, 'posts.slug' => $slug])
            ->select('posts.*')->first();
        return apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, POST_MODULE_SCREEN_NAME);
    }

    /**
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFeatured($limit = 5)
    {
        $data = $this->model->where(['posts.status' => 1, 'posts.featured' => 1])
            ->limit($limit)
            ->orderBy('posts.created_at', 'desc');

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param array $selected
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListPostNonInList(array $selected = [], $limit = 7)
    {
        $data = $this->model->where('posts.status', '=', 1)
            ->whereNotIn('posts.id', $selected)
            ->orderBy('posts.created_at', 'desc')
            ->limit($limit)
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $slug
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getRelated($slug, $limit = 3)
    {
        $data = $this->model->where('posts.status', '=', 1)
            ->where('posts.slug', '!=', $slug)
            ->limit($limit)
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $category_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByCategory($category_id, $paginate = 12, $limit = 0)
    {
        $data = $this->model->where('posts.status', '=', 1)
            ->join('post_category', 'post_category.post_id', '=', 'posts.id')
            ->join('categories', 'post_category.category_id', '=', 'categories.id')
            ->where('post_category.category_id', '=', $category_id)
            //->orWhere('categories.parent_id', '=', $category_id)
            ->select('posts.*')
            ->distinct()
            ->orderBy('posts.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME);
        if ($paginate != 0) {
            return $data->paginate($paginate);
        }
        return $data->limit($limit)->get();
    }

    /**
     * @param $user_id
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByUserId($user_id, $paginate = 6)
    {
        $data = $this->model->where(['posts.status' => 1, 'posts.user_id' => $user_id])
            ->select('posts.*')
            ->orderBy('posts.views', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->paginate($paginate);
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('posts.status', '=', 1)
            ->select('posts.*')
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $tag
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByTag($tag, $paginate = 12)
    {
        $data = $this->model->where('posts.status', '=', 1)
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', 'like', $tag . '%');
            })
            ->select('posts.*')
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->paginate($paginate);
    }

    /**
     * @param int $limit
     * @param int $category_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getRecentPosts($limit = 5, $category_id = 0)
    {
        $posts = $this->model->where(['posts.status' => 1]);

        if ($category_id != 0) {
            $posts = $posts->join('post_category', 'post_category.post_id', '=', 'posts.id')
                ->where('post_category.category_id', '=', $category_id);
        }

        $data = $posts->limit($limit)
            ->select('posts.*')
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10)
    {
        $posts = $this->model->whereStatus(1);
        foreach (explode(' ', $query) as $term) {
            $posts = $posts->where('name', 'LIKE', '%' . $term . '%');
        }

        $data = $posts->select('posts.*')
            ->limit($limit)
            ->orderBy('posts.created_at', 'desc');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPosts($active = true)
    {
        $data = $this->model->select('posts.*');
        if ($active) {
            $data = $data->where(['posts.status' => 1]);
        }

        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)
            ->get();
    }
}

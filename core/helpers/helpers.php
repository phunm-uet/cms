<?php

use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;

if (!function_exists('get_featured_posts')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_featured_posts($limit)
    {
        return app(PostInterface::class)->getFeatured($limit);
    }
}

if (!function_exists('get_latest_posts')) {
    /**
     * @param $limit
     * @param array $excepts
     * @return mixed
     * @author Sang Nguyen
     */
    function get_latest_posts($limit, $excepts = [])
    {
        return app(PostInterface::class)->getListPostNonInList($excepts, $limit);
    }
}


if (!function_exists('get_related_posts')) {
    /**
     * @param $current_slug
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_related_posts($current_slug, $limit)
    {
        return app(PostInterface::class)->getRelated($current_slug, $limit);
    }
}

if (!function_exists('get_posts_by_category')) {
    /**
     * @param $category_id
     * @param $paginate
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_category($category_id, $paginate = 12, $limit = 0)
    {
        return app(PostInterface::class)->getByCategory($category_id, $paginate, $limit);
    }
}

if (!function_exists('get_posts_by_tag')) {
    /**
     * @param $slug
     * @param $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_tag($slug, $paginate = 12)
    {
        return app(PostInterface::class)->getByTag($slug, $paginate);
    }
}

if (!function_exists('get_posts_by_user')) {
    /**
     * @param $user_id
     * @param $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_user($user_id, $paginate = 12)
    {
        return app(PostInterface::class)->getByUserId($user_id, $paginate);
    }
}

if (!function_exists('get_post_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_post_by_slug($slug)
    {
        return app(PostInterface::class)->getBySlug($slug, true);
    }
}


if (!function_exists('get_all_posts')) {
    /**
     * @param boolean $active
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_posts($active = true)
    {
        return app(PostInterface::class)->getAllPosts($active);
    }
}

if (!function_exists('get_recent_posts')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_recent_posts($limit)
    {
        return app(PostInterface::class)->getRecentPosts($limit);
    }
}


if (!function_exists('get_featured_categories')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_featured_categories($limit)
    {
        return app(CategoryInterface::class)->getFeaturedCategories($limit);
    }
}

if (!function_exists('get_category_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_category_by_slug($slug)
    {
        return app(CategoryInterface::class)->getBySlug($slug, true);
    }
}

if (!function_exists('get_all_categories')) {
    /**
     * @param boolean $active
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_categories($active = true)
    {
        return app(CategoryInterface::class)->getAllCategories($active);
    }
}

if (!function_exists('check_parent_category')) {
    /**
     * @param int $parent_id
     * @param string $indent
     * @param string $indent_type
     * @return string
     */
    function check_parent_category($parent_id = 0, $indent_type = '__', $indent = '')
    {

        if ($parent_id == 0 || true) {
            return $indent;
        }

        $parent = app(CategoryInterface::class)->findById($parent_id);

        return check_parent_category($parent->parent_id, $indent_type, $indent_type . $indent);
    }
}

if (!function_exists('get_tag_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_tag_by_slug($slug)
    {
        return app(TagInterface::class)->getBySlug($slug, true);
    }
}

if (!function_exists('get_all_tags')) {
    /**
     * @param boolean $active
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_tags($active = true)
    {
        return app(TagInterface::class)->getAllTags($active);
    }
}

if (!function_exists('get_popular_tags')) {
    /**
     * @param integer $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_popular_tags($limit = 10)
    {
        return app(TagInterface::class)->getPopularTags($limit);
    }
}

if (!function_exists('get_category_by_id')) {
    /**
     * @param integer $id
     * @return mixed
     * @author Sang Nguyen
     */
    function get_category_by_id($id)
    {
        return app(CategoryInterface::class)->getCategoryById($id);
    }
}
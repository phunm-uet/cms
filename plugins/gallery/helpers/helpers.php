<?php

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;

if (!function_exists('gallery_meta_data')) {
    /**
     * @param $id
     * @param $type
     * @return mixed
     * @author Sang Nguyen
     */
    function gallery_meta_data($id, $type)
    {
        $meta = app(GalleryMetaInterface::class)->getFirstBy(['content_id' => $id, 'reference' => $type]);
        if (!empty($meta)) {
            return $meta->images;
        }
        return null;
    }
}

if (!function_exists('get_galleries')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_galleries($limit)
    {
        return app(GalleryInterface::class)->getFeaturedGalleries($limit);
    }
}

if (!function_exists('render_galleries')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function render_galleries($limit)
    {
        return view('gallery::gallery', compact('limit'));
    }
}

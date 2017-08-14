<?php

namespace Botble\Gallery\Supports;

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Exception;
use Illuminate\Http\Request;

class Action
{
    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addGalleryBox'], 13, 3);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveGalleryData'], 20, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveGalleryData'], 20, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteGalleryMeta'], 55, 2);
        add_action(BASE_ACTION_REGISTER_SITE_MAP, [$this, 'registerSiteMap'], 234, 1);
    }

    /**
     * Trigger __construct function
     *
     * @return Action
     */
    public static function initialize()
    {
        return new self();
    }
    
    /**
     * @param $screen
     * @author Sang Nguyen
     */
    public function addGalleryBox($screen)
    {
        if (in_array($screen, [POST_MODULE_SCREEN_NAME, PAGE_MODULE_SCREEN_NAME, GALLERY_MODULE_SCREEN_NAME])) {
            add_meta_box('gallery_wrap', trans('gallery::gallery.gallery_box'), [$this, 'galleryMetaField'], $screen, 'side', 'default');
        }
    }
    /**
     * @author Sang Nguyen
     */
    public function galleryMetaField()
    {
        $value = null;
        $args = func_get_args();
        if (!empty($args[0])) {
            $value = gallery_meta_data($args[0]->id, $args[1]);
        }
        return view('gallery::gallery-box', compact('value'))->render();
    }

    /**
     * @param $type
     * @param Request $request
     * @param $object
     * @return mixed
     * @author Sang Nguyen
     */
    public function saveGalleryData($type, Request $request, $object)
    {
        try {
            if (empty($request->input('gallery'))) {
                app(GalleryMetaInterface::class)->deleteBy(['content_id' => $object->id, 'reference' => $type]);
                return false;
            }
            $meta = app(GalleryMetaInterface::class)->getFirstBy(['content_id' => $object->id, 'reference' => $type]);
            if (!$meta) {
                $meta = app(GalleryMetaInterface::class)->getModel();
                $meta->content_id = $object->id;
                $meta->reference = $type;
            }

            $meta->images = $request->input('gallery');
            app(GalleryMetaInterface::class)->createOrUpdate($meta);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteGalleryMeta($screen, $content)
    {
        try {
            if (in_array($screen, [POST_MODULE_SCREEN_NAME, PAGE_MODULE_SCREEN_NAME, GALLERY_MODULE_SCREEN_NAME])) {
                app(GalleryMetaInterface::class)->deleteBy(['content_id' => $content->id, 'reference' => $screen]);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @param $site_map
     * @return void
     * @author Sang Nguyen
     */
    public function registerSiteMap($site_map)
    {
        $site_map->add(route('public.galleries'), '2016-10-10 00:00', '0.8', 'weekly');
        $galleries = app(GalleryInterface::class)->getDataSiteMap();
        foreach ($galleries as $gallery) {
            $site_map->add(route('public.gallery', $gallery->slug), $gallery->updated_at, '0.8', 'daily');
        }
    }
}
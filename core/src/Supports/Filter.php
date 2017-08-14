<?php

namespace Botble\Blog\Supports;

use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Sentinel;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 21, 1);
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addStatsWidgets'], 13, 1);
        add_filter(MENU_FILTER_MENU_ITEM, [$this, 'addRelatedToMenuItem'], 11, 2);
    }

    /**
     * Trigger __construct function
     *
     * @return Filter
     */
    public static function initialize()
    {
        return new self();
    }

    /**
     * @param $widgets
     * @return array
     * @author Sang Nguyen
     */
    public function registerDashboardWidgets($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_posts_recent']);
        $widget_setting = app(DashboardWidgetSettingInterface::class)->getFirstBy(['widget_id' => $widget->id, 'user_id' => Sentinel::getUser()->getUserId()]);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('blog::posts.widgets.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('blog::posts.widgets.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addStatsWidgets($widgets)
    {
        $posts = app(PostInterface::class)->count(['status' => 1]);
        $categories = app(CategoryInterface::class)->count(['status' => 1]);

        $widgets = $widgets . view('blog::posts.widgets.stats', compact('posts'))->render();
        $widgets = $widgets . view('blog::categories.widgets.stats', compact('categories'))->render();

        return $widgets;
    }

    /**
     * @param $item
     * @param $args
     * @return mixed
     */
    public function addRelatedToMenuItem($item, $args)
    {
        if ($args['type'] == 'categories') {
            $category = app(CategoryInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($category) {
                if (trim($args['title']) == null) {
                    $item->name = $category->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.view', $category->slug);
                } else {
                    $item->url = route('categories.edit', $category->id);
                }
            }
        }

        if ($args['type'] == 'tags') {
            $tag = app(TagInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($tag) {
                if (trim($args['title']) == null) {
                    $item->name = $tag->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.tag', $tag->slug);
                } else {
                    $item->url = route('tags.edit', $tag->id);
                }
            }
        }

        return $item;
    }
}
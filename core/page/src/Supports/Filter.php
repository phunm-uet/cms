<?php

namespace Botble\Page\Supports;

use Botble\Page\Repositories\Interfaces\PageInterface;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addPageStatsWidget'], 15, 1);
        add_filter(MENU_FILTER_MENU_ITEM, [$this, 'addRelatedToMenuItem'], 10, 2);
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
     * @return string
     * @author Sang Nguyen
     */
    public function addPageStatsWidget($widgets)
    {
        $pages = app(PageInterface::class)->count(['status' => 1]);

        return $widgets . view('pages::partials.widgets.stats', compact('pages'))->render();
    }

    /**
     * @param $item
     * @param $args
     * @return mixed
     */
    public function addRelatedToMenuItem($item, $args)
    {
        if ($args['type'] == 'pages') {
            $page = app(PageInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($page) {
                if (trim($args['title']) == null) {
                    $item->name = $page->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.single.detail', $page->slug);
                } else {
                    $item->url = route('pages.edit', $page->id);
                }
            }
        }

        return $item;
    }
}
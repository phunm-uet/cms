<?php

namespace Botble\ACL\Supports;

use Botble\ACL\Repositories\Interfaces\UserInterface;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addUserStatsWidget'], 12, 1);
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
    public function addUserStatsWidget($widgets)
    {
        $users = app(UserInterface::class)->count();

        return $widgets . view('acl::partials.widgets.user-stats', compact('users'))->render();
    }
}
<?php

namespace Botble\Analytics\Supports;

use Assets;

class Action
{
    public function __construct()
    {
        add_action(DASHBOARD_ACTION_REGISTER_SCRIPTS, [$this, 'registerScripts'], 18);
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
     * @return void
     * @author Sang Nguyen
     */
    public function registerScripts()
    {
        Assets::addJavascript(['jvectormap', 'morris']);
        Assets::addStylesheets(['jvectormap', 'morris']);
    }
}
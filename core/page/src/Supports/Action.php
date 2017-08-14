<?php

namespace Botble\Page\Supports;

use Botble\Page\Repositories\Interfaces\PageInterface;
use Menu;

class Action
{

    /**
     * Action constructor.
     */
    public function __construct()
    {
        add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 10);
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
     * Register sidebar options in menu
     */
    public function registerMenuOptions()
    {
        $pages = Menu::generateSelect(['model' => app(PageInterface::class)->getModel(), 'theme' => false, 'options' => ['class' => 'list-item']]);
        echo view('pages::partials.menu-options', compact('pages'));
    }
}
<?php

namespace Botble\Blog\Supports;

use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Menu;

class Action
{

    /**
     * Action constructor.
     */
    public function __construct()
    {
        add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 2);
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
        $categories = Menu::generateSelect(['model' => app(CategoryInterface::class)->getModel(), 'theme' => false, 'options' => ['class' => 'list-item']]);
        echo view('blog::categories.partials.menu-options', compact('categories'));

        $tags = Menu::generateSelect(['model' => app(TagInterface::class)->getModel(), 'theme' => false, 'options' => ['class' => 'list-item']]);
        echo view('blog::tags.partials.menu-options', compact('tags'));
    }
}
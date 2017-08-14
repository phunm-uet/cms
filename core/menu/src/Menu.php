<?php

namespace Botble\Menu;

use Botble\Menu\Models\MenuNode;
use Botble\Menu\Repositories\Interfaces\MenuContentInterface;
use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Collective\Html\HtmlBuilder;
use Exception;
use Theme;

class Menu
{
    /**
     * @var mixed
     */
    protected $menuRepository;

    /**
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * @var MenuContentInterface
     */
    protected $menuContentRepository;

    /**
     * @var MenuNodeInterface
     */
    protected $menuNodeRepository;

    /**
     * Menu constructor.
     * @param MenuInterface $menu
     * @param HtmlBuilder $html
     * @param MenuContentInterface $menuContentRepository
     * @param MenuNodeInterface $menuNodeRepository
     * @author Sang Nguyen
     */
    public function __construct(MenuInterface $menu, HtmlBuilder $html, MenuContentInterface $menuContentRepository, MenuNodeInterface $menuNodeRepository)
    {
        $this->menuRepository = $menu;
        $this->html = $html;
        $this->menuContentRepository = $menuContentRepository;
        $this->menuNodeRepository = $menuNodeRepository;
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->menuRepository->getModel();
    }

    /**
     * @param $args
     * @return mixed|null|string
     * @author Sang Nguyen, Tedozi Manson
     */
    public function generateMenu($args = [])
    {
        $slug = array_get($args, 'slug');
        if (!$slug) {
            return null;
        }
        $parent_id = array_get($args, 'parent_id', 0);
        $view = array_get($args, 'view');
        $active = array_get($args, 'active', true);
        $theme = array_get($args, 'theme', true);
        $options = $this->html->attributes(array_get($args, 'options', []));

        $menu = $this->menuRepository->findBySlug($slug, $active, ['menus.id', 'menus.slug']);

        if (!$menu) {
            return null;
        }

        $menuContent = $this->menuContentRepository->getFirstBy(['menu_id' => $menu->id]);
        if (!$menuContent) {
            $menu_nodes = [];
        } else {
            $menu_nodes = $this->menuNodeRepository->getByMenuContentId($menuContent->id, $parent_id, [
                'id',
                'menu_content_id',
                'parent_id',
                'related_id',
                'icon_font',
                'css_class',
                'target',
                'url',
                'title',
                'type'
            ]);
        }


        if ($theme && $view) {
            return Theme::partial($view, compact('menu_nodes', 'menu', 'options'));
        } elseif ($view) {
            return view($view, compact('menu_nodes', 'menu', 'options'))->render();
        }
        return view('menu::partials.default', compact('menu_nodes', 'menu', 'options'))->render();
    }

    /**
     * @param array $args
     * @return mixed|null|string
     * @author Sang Nguyen, Tedozi Manson
     */
    public function generateSelect($args = [])
    {
        $model = array_get($args, 'model');
        if (!$model) {
            return null;
        }
        $parent_id = array_get($args, 'parent_id', 0);
        $view = array_get($args, 'view');
        $active = array_get($args, 'active', true);
        $theme = array_get($args, 'theme', true);
        $options = $this->html->attributes(array_get($args, 'options', []));

        $object = $model->whereParentId($parent_id);
        if ($active) {
            $object = $object->where('status', $active);
        }
        $object = $object->orderBy('name', 'asc')->get();

        if (empty($object)) {
            return null;
        }

        if ($theme && $view) {
            return Theme::partial($view, compact('object', 'model', 'options'));
        } elseif ($view) {
            return view($view, compact('object', 'model', 'options'))->render();
        }
        return view('menu::partials.select', compact('object', 'model', 'options'))->render();
    }

    /**
     * @param $slug
     * @param $active
     * @return bool
     * @author Sang Nguyen
     */
    public function hasMenu($slug, $active)
    {
        $menu = $this->menuRepository->findBySlug($slug, $active);
        if (!$menu) {
            return false;
        }
        return true;
    }

    /**
     * @param $menu_nodes
     * @param $menu_content_id
     * @param $parent_id
     * @author Sang Nguyen, Tedozi Manson
     */
    public function recursiveSaveMenu($menu_nodes, $menu_content_id, $parent_id)
    {
        try {
            foreach ($menu_nodes as $row) {
                $parent = $this->saveMenuNode($row, $menu_content_id, $parent_id);
                if (!empty($parent)) {
                    $this->recursiveSaveMenu(array_get($row, 'children'), $menu_content_id, $parent);
                }
            }
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * @param $menu_item
     * @param $menu_content_id
     * @param $parent_id
     * @return mixed
     * @author Sang Nguyen, Tedozi Manson
     */
    private function saveMenuNode($menu_item, $menu_content_id, $parent_id)
    {
        $item = MenuNode::find(array_get($menu_item, 'id'));
        if (!$item) {
            $item = new MenuNode();
        }

        $item->title = array_get($menu_item, 'title');
        $item->url = array_get($menu_item, 'customUrl');
        $item->css_class = array_get($menu_item, 'class');
        $item->position = array_get($menu_item, 'position');
        $item->icon_font = array_get($menu_item, 'iconFont');
        $item->target = array_get($menu_item, 'target');
        $item->type = array_get($menu_item, 'type');
        $item->menu_content_id = $menu_content_id;
        $item->parent_id = $parent_id;

        switch ($item->type) {
            case 'custom-link':
                $item->related_id = 0;
                break;
            default:
                $item->related_id = (int)array_get($menu_item, 'relatedId');
                break;
        }
        $this->menuNodeRepository->createOrUpdate($item);

        return $item->id;
    }
}

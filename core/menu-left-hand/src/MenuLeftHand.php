<?php

namespace Botble\MenuLeftHand;

class MenuLeftHand
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $menu;

    public function __construct()
    {
        $this->menu = collect(json_decode(session()->get('menu_left_hand')));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return $this->menu;
    }

    /**
     * @param array $args
     * @param array $parent
     * @return $this
     */
    public function addItem(array $args, array $parent = [])
    {
        $data = (object)array_merge([
            'route' => '',
            'sequence' => 10,
            'name' => '',
            'defaultName' => '',
            'kind' => 'page',
            'icon' => null,
        ], $args);

        if (empty($parent)) {
            $this->menu[] = $data;
        } else {
            foreach ($this->menu as &$menu) {
                $parent = collect($parent);
                if (property_exists($menu, 'items')) {
                    $value = collect($menu)->forget('items');
                } else {
                    $value = collect($menu);
                }
                $intersect = $value->intersect($parent);
                if ($intersect->count() == $parent->count()) {
                    if (property_exists($menu, 'items')) {
                        $menu->items[] = $data;
                    } else {
                        $menu->items = [$data];
                    }
                    break;
                }
            }
        }

        $this->menu = $this->menu->sortBy('sequence');
    }

    /**
     * @param array $conditions
     * @return static
     */
    public function removeItem(array $conditions)
    {
        $filtered = $this->menu->reject(function ($value) use ($conditions) {
            $conditions = collect($conditions);
            if (property_exists($value, 'items')) {
                foreach ($value->items as $key => $item) {
                    $intersect = collect($item)->intersect($conditions);
                    if ($intersect->count() == $conditions->count()) {
                        unset($value->items[$key]);
                        break;
                    }
                }

                $parent = collect($value)->forget('items');
            } else {
                $parent = $value;
            }

            $intersect = collect($parent)->intersect($conditions);
            if ($intersect->count() == $conditions->count()) {
                return true;
            }

            return false;
        });

        $this->menu = $filtered;
    }
}

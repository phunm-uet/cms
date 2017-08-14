<?php

namespace Botble\MenuLeftHand\Models;

use Baum\Node;
use Botble\ACL\Models\PermissionFlag;
use Botble\ACL\Models\Feature;
use Botble\MenuLeftHand\Observers\MenuLeftHandObserver;
use Lang;
use Route;
use Sentinel;

class MenuLeftHand extends Node
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'config_menu_left_hand';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @author Sang Nguyen
     */
    public static function boot()
    {
        parent::boot();
        MenuLeftHand::observe(new MenuLeftHandObserver);
    }

    /**
     * @author Sang Nguyen
     */
    public static function buildMenu()
    {
        if (Sentinel::check()) {
            session()->forget('menu_left_hand');
            $arMenu = [];

            $featuresEnabled = Feature::join('permission_flags', 'features.feature_id', '=', 'permission_flags.id')
                ->pluck('flag', 'feature_id')->all();

            $currentRootNode = MenuLeftHand::where('kind', '=', 'root')->first();
            if ($currentRootNode == null) {
                // Revert to defaults
                $currentRootNode = MenuLeftHand::where('kind', '=', 'root')->first();
            }

            if ($currentRootNode != null) {
                $defaultTree = $currentRootNode->getImmediateDescendants();
                if (isset($defaultTree)) {
                    $indexTree = 0;
                    foreach ($defaultTree as $immediateDescendant) {
                        if ((!isset($featuresEnabled[$immediateDescendant->feature_id]) && $immediateDescendant->feature_id != null)
                            || (isset($featuresEnabled[$immediateDescendant->feature_id]) && !Sentinel::getUser()->hasPermission($featuresEnabled[$immediateDescendant->feature_id]))
                        ) {
                            continue;
                        }

                        $feature = $immediateDescendant->feature();
                        if ($feature != null) {
                            $arMenu[$indexTree]['route'] = $feature->flag;
                        } else {
                            $arMenu[$indexTree]['route'] = '#';
                        }

                        $arMenu[$indexTree]['sequence'] = $indexTree;
                        $arMenu[$indexTree]['feature_id'] = $immediateDescendant->feature_id;
                        $arMenu[$indexTree]['name'] = Lang::has('menu-left-hand::menu_left_hand.' . $immediateDescendant->name) ? trans('menu-left-hand::menu_left_hand.' . $immediateDescendant->name) : $immediateDescendant->name;
                        $arMenu[$indexTree]['defaultName'] = Lang::has('menu-left-hand::menu_left_hand.' . $immediateDescendant->default_name) ? trans('menu-left-hand::menu_left_hand.' . $immediateDescendant->default_name) : $immediateDescendant->default_name;
                        $arMenu[$indexTree]['kind'] = $immediateDescendant->kind;
                        $arMenu[$indexTree]['icon'] = $immediateDescendant->icon;

                        $children = $immediateDescendant->getImmediateDescendants();
                        if (isset($children)) {
                            $indexChildren = 0;
                            foreach ($children as $child) {
                                if ((!isset($featuresEnabled[$child->feature_id]) && $child->feature_id != null)
                                    || (isset($featuresEnabled[$child->feature_id]) && !Sentinel::getUser()->hasPermission($featuresEnabled[$child->feature_id]))
                                ) {
                                    continue;
                                }

                                $featureChildren = $child->feature();
                                if ($featureChildren != null && Route::has($featureChildren->flag)) {
                                    $arMenu[$indexTree]['items'][$indexChildren]['route'] = $featureChildren->flag;
                                } else {
                                    $arMenu[$indexTree]['items'][$indexChildren]['route'] = '#';
                                }

                                $arMenu[$indexTree]['items'][$indexChildren]['sequence'] = $indexChildren;
                                $arMenu[$indexTree]['items'][$indexChildren]['feature_id'] = $child->feature_id;
                                $arMenu[$indexTree]['items'][$indexChildren]['name'] = Lang::has('menu-left-hand::menu_left_hand.' . $child->name) ? trans('menu-left-hand::menu_left_hand.' . $child->name) : $child->name;
                                $arMenu[$indexTree]['items'][$indexChildren]['defaultName'] = Lang::has('menu-left-hand::menu_left_hand.' . $child->default_name) ? trans('menu-left-hand::menu_left_hand.' . $child->default_name) : $child->default_name;
                                $arMenu[$indexTree]['items'][$indexChildren]['kind'] = $child->kind;
                                $arMenu[$indexTree]['items'][$indexChildren]['icon'] = $child->icon;

                                $indexChildren++;
                            }
                        }
                        $indexTree++;
                    }
                }
            }

            session()->put('menu_left_hand', json_encode($arMenu, JSON_HEX_APOS));
        }
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function feature()
    {
        return $this->hasOne(PermissionFlag::class, 'id', 'feature_id')->first();
    }
}

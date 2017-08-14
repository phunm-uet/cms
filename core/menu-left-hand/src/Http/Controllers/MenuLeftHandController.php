<?php
namespace Botble\MenuLeftHand\Http\Controllers;

use App\Http\Controllers\Controller;
use Assets;
use Botble\ACL\Repositories\Interfaces\FeatureInterface;
use Botble\MenuLeftHand\Repositories\Interfaces\MenuLeftHandInterface;
use Botble\MenuLeftHand\Models\MenuLeftHand;
use Exception;
use Illuminate\Http\Request;

class MenuLeftHandController extends Controller
{

    /**
     * @var MenuLeftHandInterface
     */
    protected $menuLeftHandRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * MenuLeftHandController constructor.
     * @param MenuLeftHandInterface $menuLeftHandRepository
     * @param FeatureInterface $featureRepository
     * @author Sang Nguyen
     */
    public function __construct(MenuLeftHandInterface $menuLeftHandRepository, FeatureInterface $featureRepository)
    {
        $this->menuLeftHandRepository = $menuLeftHandRepository;
        $this->featureRepository = $featureRepository;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getEdit()
    {

        page_title()->setTitle(trans('menu-left-hand::menu_left_hand.config'));

        $data['default'] = [];
        $data['current'] = [];

        // Build defaults
        $featuresEnabled = $this->featureRepository->getModel()->pluck('feature_id')->all();

        $globalRootNode = $this->menuLeftHandRepository->getFirstBy(['kind' => 'root']);
        if ($globalRootNode) {
            $defaultTree = $globalRootNode->getImmediateDescendants();
        }

        if (isset($defaultTree)) {
            $indexTree = 0;
            foreach ($defaultTree as $immediateDescendant) {
                if (!in_array($immediateDescendant->feature_id, $featuresEnabled) && $immediateDescendant->feature_id != null) {
                    continue;
                }
                $data['default'][$indexTree]['id'] = $immediateDescendant->id;
                $data['default'][$indexTree]['kind'] = $immediateDescendant->kind;
                $data['default'][$indexTree]['feature_id'] = $immediateDescendant->feature_id;
                $data['default'][$indexTree]['name'] = $immediateDescendant->name;
                $data['default'][$indexTree]['defaultName'] = $immediateDescendant->default_name;
                $data['default'][$indexTree]['icon'] = $immediateDescendant->icon;

                $children = $immediateDescendant->getImmediateDescendants();
                if (isset($children)) {
                    $indexChildren = 0;
                    foreach ($children as $child) {
                        if (!in_array($child->feature_id, $featuresEnabled)) {
                            continue;
                        }
                        $data['default'][$indexTree]['items'][$indexChildren]['id'] = $child->id;
                        $data['default'][$indexTree]['items'][$indexChildren]['kind'] = $child->kind;
                        $data['default'][$indexTree]['items'][$indexChildren]['feature_id'] = $child->feature_id;
                        $data['default'][$indexTree]['items'][$indexChildren]['name'] = $child->name;
                        $data['default'][$indexTree]['items'][$indexChildren]['defaultName'] = $child->default_name;
                        $data['default'][$indexTree]['items'][$indexChildren]['icon'] = $child->icon;

                        $indexChildren++;
                    }
                }
                $indexTree++;
            }
        }

        $currentRootNode = $this->menuLeftHandRepository->getFirstBy(['kind' => 'root']);
        if ($currentRootNode != null) {
            $currentTree = $currentRootNode->getImmediateDescendants();
            if (isset($currentTree)) {
                $indexTree = 0;
                foreach ($currentTree as $immediateDescendant) {
                    if (!in_array($immediateDescendant->feature_id, $featuresEnabled) && $immediateDescendant->feature_id != null) {
                        continue;
                    }
                    $data['current'][$indexTree]['id'] = $immediateDescendant->id;
                    $data['current'][$indexTree]['kind'] = $immediateDescendant->kind;
                    $data['current'][$indexTree]['feature_id'] = $immediateDescendant->feature_id;
                    $data['current'][$indexTree]['name'] = $immediateDescendant->name;
                    $data['current'][$indexTree]['defaultName'] = $immediateDescendant->default_name;
                    $data['current'][$indexTree]['icon'] = $immediateDescendant->icon;

                    $children = $immediateDescendant->getImmediateDescendants();
                    if (isset($children)) {
                        $indexChildren = 0;
                        foreach ($children as $child) {
                            if (!in_array($child->feature_id, $featuresEnabled)) {
                                continue;
                            }
                            $data['current'][$indexTree]['items'][$indexChildren]['id'] = $child->id;
                            $data['current'][$indexTree]['items'][$indexChildren]['kind'] = $child->kind;
                            $data['current'][$indexTree]['items'][$indexChildren]['feature_id'] = $child->feature_id;
                            $data['current'][$indexTree]['items'][$indexChildren]['name'] = $child->name;
                            $data['current'][$indexTree]['items'][$indexChildren]['defaultName'] = $child->default_name;
                            $data['current'][$indexTree]['items'][$indexChildren]['icon'] = $child->icon;

                            $indexChildren++;
                        }
                    }
                    $indexTree++;
                }
            }

        } else {
            // Set defaults
            $data['current'] = $data['default'];
        }

        Assets::addAppModule(['admin-menu-left-hand']);
        Assets::addJavascript(['kendo']);
        Assets::addStylesheets(['kendo']);
        return view('menu-left-hand::list')
            ->with('default', json_encode($data['default'], JSON_HEX_APOS))
            ->with('current', json_encode($data['current'], JSON_HEX_APOS));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit(Request $request)
    {

        // Delete existing menu
        $currentRootNode = $this->menuLeftHandRepository->getFirstBy(['kind' => 'root']);
        if ($currentRootNode != null) {
            $currentRootNode->delete();
        }

        $root = $this->menuLeftHandRepository->getModel();
        $root->kind = 'root';
        $root->name = 'Root';
        $root->default_name = 'Root';
        $this->menuLeftHandRepository->createOrUpdate($root);

        $menuItems = $request->input('items');

        if (isset($menuItems)) {
            $arMenuItems = json_decode($menuItems);
            if (isset($arMenuItems)) {
                foreach ($arMenuItems as $menuItem) {
                    $category = $root->children()->create([
                        'kind' => $menuItem->kind,
                        'name' => isset($menuItem->name) ? $menuItem->name : $menuItem->defaultName,
                        'default_name' => $menuItem->defaultName != '' ? $menuItem->defaultName : $menuItem->name,
                        'feature_id' => isset($menuItem->feature_id) ? $menuItem->feature_id : null,
                        'icon' => isset($menuItem->icon) ? $menuItem->icon : null,
                    ]);
                    if (isset($menuItem->items)) {
                        foreach ($menuItem->items as $subItem) {
                            $category->children()->create([
                                'kind' => $subItem->kind,
                                'name' => isset($subItem->name) ? $subItem->name : $subItem->defaultName,
                                'default_name' => $subItem->defaultName != '' ? $subItem->defaultName : $subItem->name,
                                'feature_id' => isset($subItem->feature_id) ? $subItem->feature_id : null,
                                'icon' => isset($subItem->icon) ? $subItem->icon : null,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('system.menu.left-hand')
            ->with('success_msg', trans('bases::system.update_menu_left_hand_success'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {

        page_title()->setTitle(trans('menu-left-hand::menu_left_hand.create'));

        $allMenuItems = $this->menuLeftHandRepository->getMenuNotRootArray();
        $featuresEnabled = $this->featureRepository->getModel()->join('permission_flags', 'permission_flags.id', '=', 'features.feature_id')->where(['is_feature' => 1, 'feature_visible' => 1])->pluck('permission_flags.name', 'permission_flags.id')->all();

        return view('menu-left-hand::create', compact('allMenuItems', 'featuresEnabled'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(Request $request)
    {

        if ($request->input('sibling_id') != '') {
            $sibling = $this->menuLeftHandRepository->findById($request->input('sibling_id'));
            $allSiblings = $this->menuLeftHandRepository->allBy(['feature_id' => $sibling->feature_id, 'default_name' => $sibling->default_name]);

            if ($allSiblings) {
                foreach ($allSiblings as $sibling) {
                    $newNode = $this->menuLeftHandRepository->create([
                        'kind' => $request->input('kind'),
                        'name' => $request->input('name'),
                        'default_name' => $request->input('name'),
                        'feature_id' => $request->input('feature_id'),
                        'icon' => $request->input('icon'),
                    ]);

                    if ($request->input('position') == 'before') {
                        $newNode->makePreviousSiblingOf($sibling);
                    } else {
                        $newNode->makeNextSiblingOf($sibling);
                    }
                }

                return redirect()->route('system.menu.left-hand')
                    ->with('created', true)
                    ->with('success_msg', trans('bases::system.added_menu_left_hand_success'));
            }
        } else {
            // Recreate the tree
            $root = $this->menuLeftHandRepository->create([
                'kind' => 'root',
                'name' => 'Root',
                'default_name' => 'Root',
            ]);

            $root->children()->create([
                'kind' => $request->input('kind'),
                'parent_id' => $root->id,
                'name' => $request->input('name'),
                'default_name' => $request->input('name'),
                'feature_id' => $request->input('feature_id'),
                'icon' => $request->input('icon'),
            ]);
            return redirect()->route('system.menu.left-hand')
                ->with('created', true)
                ->with('success_msg', trans('bases::system.added_menu_left_hand_success'));
        }

        return redirect()->route('system.options')
            ->with('error_msg', trans('bases::system.position_not_found'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getDelete($id)
    {

        try {
            $this->menuLeftHandRepository->deleteBy(['id' => $id]);
        } catch (Exception $ex) {
            return redirect()->route('system.options')
                ->with('error_msg', trans('bases::system.menu_option_not_found'));
        }
        return redirect()->route('system.options')
            ->with('success_msg', trans('bases::system.deleted_menu_left_hand_success'));
    }
}

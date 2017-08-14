<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\DataTables\RoleDataTable;
use Botble\ACL\Events\RoleAssignmentEvent;
use Botble\ACL\Events\RoleUpdateEvent;
use App\Http\Controllers\Controller;
use Assets;
use Botble\ACL\Repositories\Interfaces\FeatureInterface;
use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\ACL\Repositories\Interfaces\RoleFlagInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Sentinel;

class RoleController extends Controller
{
    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var RoleUserInterface
     */
    protected $roleUserRepository;

    /**
     * @var RoleFlagInterface
     */
    protected $roleFlagRepository;

    /**
     * @var PermissionInterface
     */
    protected $permissionRepository;

    /**
     * RoleController constructor.
     * @param RoleInterface $roleRepository
     * @param UserInterface $userRepository
     * @param FeatureInterface $featureRepository
     * @param RoleFlagInterface $roleFlagRepository
     * @param RoleUserInterface $roleUserRepository
     * @param PermissionInterface $permissionRepository
     */
    public function __construct(
        RoleInterface $roleRepository,
        UserInterface $userRepository,
        FeatureInterface $featureRepository,
        RoleFlagInterface $roleFlagRepository,
        RoleUserInterface $roleUserRepository,
        PermissionInterface $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->featureRepository = $featureRepository;
        $this->roleFlagRepository = $roleFlagRepository;
        $this->roleUserRepository = $roleUserRepository;
        $this->permissionRepository = $permissionRepository;
    }


    /**
     * Show list roles
     *
     * @param RoleDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(RoleDataTable $dataTable)
    {

        page_title()->setTitle(trans('acl::permissions.list_role'));

        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $dataTable->render('acl::roles.roles');
    }

    /**
     * Delete a role
     *
     * @param $id
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete($id)
    {
        $role = $this->roleRepository->findById($id);
        if ($role->reference !== 'global') {
            $role->delete();
            return ['error' => false, 'message' => trans('acl::permissions.delete_success')];
        } else {
            return ['error' => true, 'message' => trans('acl::permissions.delete_global_role')];
        }
    }

    /**
     * Delete many roles
     *
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('acl::permissions.no_select')];
        }

        foreach ($ids as $id) {
            $role = $this->roleRepository->findById($id);
            if ($role->reference !== 'global') {
                $role->delete();
            }
        }
        return ['error' => false, 'message' => trans('acl::permissions.delete_success')];
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {

        Assets::addStylesheets(['jquery-ui', 'jqueryTree']);
        Assets::addJavascript(['jquery-ui', 'jqueryTree']);
        Assets::addAppModule(['role']);

        $role = $this->roleRepository->findById($id);
        if (!$role) {
            abort(404);
        }

        page_title()->setTitle(trans('acl::permissions.details') . ' - ' . e($role->name));

        $usableFlags = $this->permissionRepository->getVisiblePermissions();

        $availableFeatures = $this->featureRepository->getModel()->pluck('feature_id')->all();

        // Make a key value pair, send this through
        $flags = [];

        foreach ($usableFlags as $usableFlag) {
            if ($usableFlag->is_feature && in_array($usableFlag->id, $availableFeatures)) {
                $flags[$usableFlag->id] = $usableFlag;
            } elseif ($usableFlag->is_feature == 0) {
                $flags[$usableFlag->id] = $usableFlag;
            }

        }

        $sortedFlag = $flags;
        sort($sortedFlag);
        $children[0] = $this->getChildren(0, $sortedFlag, $availableFeatures);

        foreach ($flags as $flagDetails) {
            $childrenReturned = $this->getChildren($flagDetails->id, $flags, $availableFeatures);
            if (count($childrenReturned) > 0) {
                if ($flagDetails->is_feature && in_array($flagDetails->id, $availableFeatures)) {
                    $children[$flagDetails->id] = $childrenReturned;
                } elseif ($flagDetails->is_feature == 0) {
                    $children[$flagDetails->id] = $childrenReturned;
                }
            }
        }

        return view('acl::roles.role-edit')
            ->with('role', $role)
            ->with('active', $role->flags()->pluck('flag')->all())
            ->with('children', $children)
            ->with('flags', $flags);
    }

    /**
     * @param $parentId
     * @param $allFlags
     * @param $availableFeatures
     * @return mixed
     * @author Sang Nguyen
     */
    private function getChildren($parentId, $allFlags, $availableFeatures)
    {
        $newFlagArray = [];
        foreach ($allFlags as $flagDetails) {
            if ($flagDetails->parent_flag == $parentId) {
                if ($flagDetails->is_feature && in_array($flagDetails->id, $availableFeatures)) {
                    $newFlagArray[] = $flagDetails->id;
                } elseif ($flagDetails->is_feature == 0) {
                    $newFlagArray[] = $flagDetails->id;
                }
            }
        }
        return $newFlagArray;
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, Request $request)
    {
        $role = $this->roleRepository->findById($id);
        if (!$role) {
            abort(404);
        }

        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->updated_by = Sentinel::getUser()->getUserId();
        $role->is_staff = $request->input('is_Staff') !== null ? 1 : 0;
        $this->roleRepository->createOrUpdate($role);

        $this->roleFlagRepository->deleteBy(['role_id' => $role->id]);

        if (!empty($request->input('flags'))) {
            foreach ($request->input('flags') as $flag) {
                $this->roleFlagRepository->firstOrCreate(['role_id' => $role->id, 'flag_id' => $flag]);
            }
        }

        event(new RoleUpdateEvent($role));

        return redirect()->route('roles.list')
            ->with('success_msg', trans('acl::permissions.modified_success'));
    }

    /**
     * @return \Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {

        page_title()->setTitle(trans('acl::permissions.create_role'));

        Assets::addStylesheets(['jquery-ui', 'jqueryTree']);
        Assets::addJavascript(['jquery-ui', 'jqueryTree']);
        Assets::addAppModule(['role']);

        $usableFlags = $this->permissionRepository->getVisiblePermissions();

        $availableFeatures = $this->featureRepository->getModel()->pluck('feature_id')->all();

        // Make a key value pair, send this through
        $flags = [];

        if ($usableFlags) {
            foreach ($usableFlags as $usableFlag) {
                if ($usableFlag->is_feature && in_array($usableFlag->id, $availableFeatures)) {
                    $flags[$usableFlag->id] = $usableFlag;
                } elseif ($usableFlag->is_feature == 0) {
                    $flags[$usableFlag->id] = $usableFlag;
                }

            }
        }

        $sortedFlag = $flags;
        sort($sortedFlag);
        $children[0] = $this->getChildren(0, $sortedFlag, $availableFeatures);

        foreach ($flags as $flagDetails) {
            $childrenReturned = $this->getChildren($flagDetails->id, $flags, $availableFeatures);
            if (count($childrenReturned) > 0) {
                if ($flagDetails->is_feature && in_array($flagDetails->id, $availableFeatures)) {
                    $children[$flagDetails->id] = $childrenReturned;
                } elseif ($flagDetails->is_feature == 0) {
                    $children[$flagDetails->id] = $childrenReturned;
                }
            }
        }

        return view('acl::roles.role-create')
            ->with('flags', $flags)
            ->with('active', [])
            ->with('children', $children);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(Request $request)
    {
        $role = $this->roleRepository->getModel()->create([
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'description' => $request->input('description'),
            'is_staff' => $request->input('is_staff') !== null ? 1 : 0,
            'created_by' => Sentinel::getUser()->id,
            'updated_by' => Sentinel::getUser()->id,
        ]);

        $this->roleFlagRepository->deleteBy(['role_id' => $role->id]);

        if (!empty($request->input('flags'))) {
            foreach ($request->input('flags') as $flag) {
                $this->roleFlagRepository->firstOrCreate(['role_id' => $role->id, 'flag_id' => $flag]);
            }
        }

        return redirect()->route('roles.list')
            ->with('success_msg', trans('acl::permissions.create_success'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getDuplicate($id)
    {

        $baseRole = $this->roleRepository->findById($id);

        $role = $this->roleRepository->getModel();
        $role->name = $baseRole->name . ' (Duplicate)';
        $role->slug = $this->roleRepository->createSlug($baseRole->slug, 0);
        $role->description = $baseRole->description;
        $role->is_staff = $baseRole->is_staff;
        $role->created_by = $baseRole->created_by;
        $role->updated_by = $baseRole->updated_by;
        $this->roleRepository->createOrUpdate($role);

        foreach ($this->roleFlagRepository->allBy(['role_id' => $baseRole->id]) as $flag) {
            $this->roleFlagRepository->firstOrCreate(['role_id' => $role->id, 'flag_id' => $flag->flag_id]);
        }

        return redirect()->route('roles.list')
            ->with('success_msg', trans('acl::permissions.duplicated_success'));

    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getJson()
    {
        $pl = [];
        foreach ($this->roleRepository->all() as $role) {
            $pl[] = ['value' => $role->id, 'text' => $role->name];
        }

        return $pl;
    }

    /**
     * @param Request $request
     * @author Sang Nguyen
     */
    public function postAssignMember(Request $request)
    {
        $user = $this->userRepository->findById($request->input('pk'));
        $role = $this->roleRepository->findById($request->input('value'));
        $this->roleUserRepository->deleteBy(['user_id' => $user->id]);

        $ru = $this->roleUserRepository->getModel();
        $ru->user_id = $user->id;
        $ru->role_id = $role->id;
        $this->roleUserRepository->createOrUpdate($ru);

        event(new RoleAssignmentEvent($role, $user));
    }
}

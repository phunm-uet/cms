<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Botble\ACL\Http\Requests\UpdatePasswordRequest;
use Botble\ACL\Http\Requests\UpdateProfileRequest;
use Botble\ACL\Http\Requests\ChangeProfileImageRequest;
use Botble\ACL\Http\Requests\InviteRequest;
use Assets;
use Botble\ACL\Models\UserMeta;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\CropAvatar;
use EmailHandler;
use File;
use Illuminate\Http\Request;
use Sentinel;
use Storage;
use Exception;

class UserController extends Controller
{

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var RoleUserInterface
     */
    protected $roleUserRepository;

    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var InviteInterface
     */
    protected $inviteRepository;

    /**
     * UserController constructor.
     * @param UserInterface $userRepository
     * @param RoleUserInterface $roleUserRepository
     * @param RoleInterface $roleRepository
     * @param InviteInterface $inviteRepository
     */
    public function __construct(UserInterface $userRepository, RoleUserInterface $roleUserRepository, RoleInterface $roleRepository, InviteInterface $inviteRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleUserRepository = $roleUserRepository;
        $this->roleRepository = $roleRepository;
        $this->inviteRepository = $inviteRepository;
    }

    /**
     * Display all users
     * @param UserDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(UserDataTable $dataTable)
    {
        page_title()->setTitle(trans('acl::users.list'));

        Assets::addJavascript(['datatables', 'bootstrap-editable']);
        Assets::addStylesheets(['datatables', 'bootstrap-editable']);
        Assets::addAppModule(['datatables']);

        $roles = $this->roleRepository->getModel()->pluck('name', 'id')->all();

        return $dataTable->render('acl::users.list', compact('roles'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function getDelete($id, Request $request)
    {
        if (Sentinel::getUser()->id == $id) {
            return ['error' => true, 'message' => trans('acl::users.delete_user_logged_in')];
        }

        try {
            $user = $this->userRepository->findById($id);
            $this->userRepository->delete($user);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
            return ['error' => false, 'message' => trans('acl::users.deleted')];
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('acl::users.cannot_delete')];
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('acl::users.no_select')];
        }

        foreach ($ids as $id) {
            if (Sentinel::getUser()->id == $id) {
                return ['error' => true, 'message' => trans('acl::users.delete_user_logged_in')];
            }
            try {
                $user = $this->userRepository->findById($id);
                $this->userRepository->delete($user);
                do_action(BASE_ACTION_AFTER_DELETE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
            } catch (Exception $e) {
                return ['error' => true, 'message' => trans('acl::users.cannot_delete')];
            }
        }
        return ['error' => false, 'message' => trans('acl::users.deleted')];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeStatus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('acl::users.no_select')];
        }

        foreach ($ids as $id) {
            if ($request->input('status') == 0) {
                if (Sentinel::getUser()->id == $id) {
                    return ['error' => true, 'message' => trans('acl::users.lock_user_logged_in')];
                }
            }
            $user = $this->userRepository->findById($id);
            $user->activated = $request->input('status');
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
        }
        return ['error' => false, 'status' => $request->input('status'), 'message' => trans('acl::users.update_success')];
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View| \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getUserProfile($id)
    {
        page_title()->setTitle('User profile # ' . $id);

        Assets::addJavascript(['cropper', 'bootstrap-pwstrength']);
        Assets::addAppModule(['profile']);

        try {
            $user = $this->userRepository->findById($id);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error_msg', trans('acl::users.not_found'));
        }

        return view('acl::users.profile.base')
            ->with('user', $user);
    }

    /**
     * @param $id
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postUpdateProfile($id, UpdateProfileRequest $request)
    {
        $user = $this->userRepository->findById($id);

        if ((Sentinel::getUser()->hasPermission('users.update-profile') && Sentinel::getUser()->id === $user->id) || Sentinel::getUser()->isSuperUser()) {
            if ($user->email !== $request->input('email')) {
                $users = $this->userRepository->getModel()->where('email', '=', $request->input('email'))->count();
                if (!$users) {
                    $user->email = $request->input('email');
                } else {
                    return redirect()->route('user.profile.view', [$id])
                        ->with('error_msg', trans('acl::users.email.exist'))
                        ->withInput();
                }
            }

            if ($user->username !== $request->input('username')) {
                $users = $this->userRepository->getModel()->where('username', '=', $request->input('username'))->count();
                if (!$users) {
                    $user->username = $request->input('username');
                } else {
                    return redirect()->route('user.profile.view', [$id])
                        ->with('error_msg', trans('acl::users.username_exist'))
                        ->withInput();
                }
            }
        }

        $user->fill($request->all());

        $user->completed_profile = 1;
        $this->userRepository->createOrUpdate($user);
        do_action(USER_ACTION_AFTER_UPDATE_PROFILE, USER_MODULE_SCREEN_NAME, $request, $user);

        return redirect()->route('user.profile.view', [$id])
            ->with('success_msg', trans('acl::users.update_profile_success'));
    }

    /**
     * @param $id
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postChangePassword($id, UpdatePasswordRequest $request)
    {

        if (!Sentinel::getUser()->isSuperUser()) {

            $hash = Sentinel::getHasher();

            if (!$hash->check($request->input('old_password'), Sentinel::getUser()->getUserPassword())) {
                return redirect()->back()
                    ->with('error_msg', trans('acl::users.current_password_not_valid'));
            }
        }

        $user = $this->userRepository->findById($id);
        Sentinel::update($user, ['password' => $request->input('password')]);

        do_action(USER_ACTION_AFTER_UPDATE_PASSWORD, USER_MODULE_SCREEN_NAME, $request, $user);

        return redirect()->route('user.profile.view', [$id])
            ->with('success_msg', trans('acl::users.password_update_success'));
    }

    /**
     * @param ChangeProfileImageRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postModifyProfileImage(ChangeProfileImageRequest $request)
    {
        try {

            if (!$request->hasFile('avatar_file')) {
                return [
                    'error' => false,
                    'message' => trans('acl::users.error_update_profile_image'),
                ];
            }

            $user = $this->userRepository->findById($request->input('user_id'));

            $file = $request->file('avatar_file');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $avatar = [
                'path' => config('acl.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username . '/full-' . str_slug(basename($fileName, $fileExtension)) . '-' . time() . '.' . $fileExtension,
                'realPath' => config('acl.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username . '/thumb-' . str_slug(basename($fileName, $fileExtension)) . '-' . time() . '.' . $fileExtension,
                'ext' => $fileExtension,
                'mime' => $request->file('avatar_file')->getMimeType(),
                'name' => $fileName,
                'user' => $user->id,
                'size' => $request->file('avatar_file')->getSize(),

            ];

            config()->set('filesystems.disks.local.root', config('cms.upload.base_dir'));

            File::deleteDirectory(config('cms.upload.base_dir') . DIRECTORY_SEPARATOR . config('acl.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username);
            Storage::put($avatar['path'], file_get_contents($request->file('avatar_file')->getRealPath()), 'public');

            $crop = new CropAvatar($request->input('avatar_src'), $request->input('avatar_data'), $avatar);
            $user->profile_image = $crop->getResult();
            $this->userRepository->createOrUpdate($user);

            return [
                'error' => false,
                'message' => trans('acl::users.update_avatar_success'),
                'result' => $crop->getResult(),
            ];

        } catch (Exception $ex) {
            return  [
                'error' => true,
                'message' => $ex->getMessage()
            ];
        }
    }

    /**
     * Posts an invite to a user.
     *
     * @param InviteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postInviteUser(InviteRequest $request)
    {

        $user = $this->userRepository->getFirstBy(['email' => $request->input('email')]);

        $token = str_random(32);

        if (!$user) {
            $user = $this->userRepository->getModel();
            $user->email = $request->input('email');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->profile_image = config('acl.avatar.default');
            $user->username = $this->userRepository->getUniqueUsernameFromEmail($request->input('email'));

            $this->userRepository->createOrUpdate($user);

            $this->sentEmailInvite([
                'user' => $user->username,
                'token' => $token,
                'email' => $user->email,
                'content' => $request->input('message'),
                'name' => $user->getFullName(),
            ], [
                'name' => $user->getFullName(),
                'to' => $user->email,
            ]);

            $newInvite = $this->inviteRepository->getModel();

            $newInvite->token = $token;
            $newInvite->user_id = Sentinel::getUser()->id;
            $newInvite->invitee_id = $user->id;
            $newInvite->role_id = $request->input('role');
            $this->inviteRepository->createOrUpdate($newInvite);

            return redirect()->route('users.list')
                ->with('success_msg', trans('acl::users.invite_success'));
        } else {
            $existingInvite = $this->inviteRepository->getFirstBy(['invitee_id' => $user->id, 'accepted' => 0]);

            if (!$existingInvite) {
                $newInvite = $this->inviteRepository->getModel();

                $newInvite->token = $token;
                $newInvite->user_id = Sentinel::getUser()->id;
                $newInvite->invitee_id = $user->id;
                $newInvite->role_id = $request->input('role');
                $this->inviteRepository->createOrUpdate($newInvite);

            } else {
                $token = $existingInvite->token;
            }

            $this->sentEmailInvite([
                'user' => $user->username,
                'token' => $token,
                'email' => $user->email,
                'content' => $request->input('message'),
                'name' => $user->getFullName(),
            ], [
                'name' => $user->getFullName(),
                'to' => $user->email,
            ]);

            return redirect()->route('users.list')
                ->with('success_msg', trans('acl::users.invite_exist'));
        }
    }

    /**
     * @param $data
     * @param array $args
     * @return void
     * @author Sang Nguyen
     */
    protected function sentEmailInvite($data, $args = [])
    {
        EmailHandler::send(view('acl::emails.invite', compact('data'))->render(), trans('acl::auth.email.invite.title'), $args);
    }

    /**
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getLanguage($lang)
    {
        if (Sentinel::check()) {
            UserMeta::setMeta('admin-locale', $lang);
        } else {
            session()->put('admin-locale', $lang);
        }

        session()->forget('menu_left_hand');
        return redirect()->back();
    }

    /**
     * @param $theme
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getTheme($theme)
    {
        if (Sentinel::check()) {
            UserMeta::setMeta('admin-theme', $theme);
        } else {
            session()->put('admin-theme', $theme);
        }

        try {
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('access.login');
        }
    }
}

<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Events\RoleAssignmentEvent;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use App\Http\Controllers\Controller;
use Assets;
use Botble\ACL\Http\Requests\ForgotRequest;
use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Http\Requests\ResetRequest;
use Botble\ACL\Models\UserMeta;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\MenuLeftHand\Models\MenuLeftHand;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use EmailHandler;
use Illuminate\Http\Request;
use Sentinel;
use Socialite;
use Exception;

class AuthController extends Controller
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
     * Show login page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getLogin()
    {
        page_title()->setTitle(trans('acl::auth.login_title'));

        Assets::addJavascript(['jquery-validation']);
        Assets::addAppModule(['login']);
        return view('acl::auth.login');
    }

    /**
     * Show forgot password page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getForgotPassword()
    {
        page_title()->setTitle(trans('acl::auth.forgot_password.title'));

        Assets::addJavascript(['jquery-validation']);
        Assets::addAppModule(['login']);
        return view('acl::auth.forgot-password');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postLogin(LoginRequest $request)
    {
        try {
            $credentials = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];
            $remember = $request->input('remember') == 1 ? true : false;
            try {
                if (Sentinel::authenticate($credentials, $remember)) {

                    $locale = UserMeta::getMeta('admin-locale', false);

                    if ($locale != false) {
                        app()->setLocale($locale);
                    }

                    MenuLeftHand::buildMenu();
                    if (!session()->has('url.intended')) {
                        session()->flash('url.intended', url()->current());
                    }
                    do_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, AUTH_MODULE_SCREEN_NAME, $request, Sentinel::getUser());
                    return redirect()->intended()->with('success_msg', trans('acl::auth.login.success'));
                }
            } catch (ThrottlingException $e) {
                return redirect()->route('access.login')->with('error_msg', $e->getMessage())->withInput();
            }

            return redirect()->route('access.login')->with('error_msg', trans('acl::auth.login.fail'))->withInput();
        } catch (NotActivatedException $e) {
            return redirect()->route('access.login')->with('error_msg', trans('acl::auth.login.not_active'))->withInput();
        }
    }

    /**
     * @param ForgotRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postForgotPassword(ForgotRequest $request)
    {
        $user = $this->userRepository->getFirstBy(['username' => $request->input('username')]);
        if (!$user) {
            return redirect()->route('access.forgot-password')->with('error_msg', trans('acl::auth.reset.user_not_found'));
        }

        $reminder = Reminder::create($user);
        if (Reminder::exists($user)) {
            $data = [
                'user' => $user->username,
                'token' => $reminder->code,
                'name' => $user->getFullName(),
                'email' => $user->email,
            ];

            try {
                EmailHandler::send(view('acl::emails.reminder', $data)->render(), trans('acl::auth.reset.title'), ['name' => $user->getFullName(), 'to' => $user->email]);
            } catch (Exception $ex) {
                info($ex->getMessage());
                return redirect()->route('access.forgot-password')->with('error_msg', trans('acl::auth.reset.send.fail'));
            }
        }
        return redirect()->route('access.forgot-password')->with('success_msg', trans('acl::auth.reset.send.success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getResetPassword(Request $request)
    {
        page_title()->setTitle(trans('acl::auth.reset.title'));

        $username = $request->input('username');
        $token = $request->input('token');

        $user = $this->userRepository->getFirstBy(['username' => $username]);
        if (!$user) {
            return redirect()->route('access.login')->with('error_msg', trans('acl::auth.reset.user_not_found'));
        }
        Reminder::removeExpired();
        if (Reminder::exists($user)) {
            Assets::addJavascript(['jquery-validation']);
            Assets::addAppModule(['login']);
            return view('acl::auth.reset', compact('user', 'token'));
        }
        return redirect()->route('access.login')->with('error_msg', trans('acl::auth.reset.fail'));
    }

    /**
     * @param ResetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postResetPassword(ResetRequest $request)
    {
        $user = $this->userRepository->getFirstBy(['username' => $request->input('user')]);
        if (!$user) {
            return redirect()->route('access.reset-password', [$request->input('user'), $request->input('token')])
                ->with('error_msg', trans('acl::auth.reset.user_not_found'));
        }

        if (Reminder::complete($user, $request->input('token'), $request->input('password'))) {
            Sentinel::authenticateAndRemember(['username' => $request->input('username'), 'password' => $request->input('password')]);
            return redirect()->route('dashboard.index')->with('success_msg', trans('acl::auth.reset.success'));
        } else {
            return redirect()->route('access.reset-password', [$request->input('user'), $request->input('token')])->with('error_msg', trans('acl::auth.reset.fail'));
        }
    }

    /**
     * Logout
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getLogout()
    {
        do_action(AUTH_ACTION_AFTER_LOGOUT_SYSTEM, AUTH_MODULE_SCREEN_NAME, request(), Sentinel::getUser());
        Sentinel::logout();
        return redirect()->route('access.login')->with('success_msg', trans('acl::auth.login.logout_success'));
    }

    /**
     * Redirect the user to the {provider} authentication page.
     *
     * @param $provider
     * @return mixed
     * @author Sang Nguyen
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from {provider}.
     * @param $provider
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function handleProviderCallback($provider, Request $request)
    {
        try {
            $oAuth = Socialite::driver($provider)->user();
        } catch (Exception $ex) {
            return redirect()->route('access.login')->with('error_msg', $ex->getMessage());
        }

        $user = $this->userRepository->getFirstBy(['email' => $oAuth->getEmail()]);

        if ($user) {
            Sentinel::loginAndRemember($user);
            do_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, AUTH_MODULE_SCREEN_NAME, $request, Sentinel::getUser());
            MenuLeftHand::buildMenu();
            return redirect()->route('dashboard.index')->with('success_msg', trans('acl::auth.login.success'));
        } else {
            return redirect()->route('access.login')->with('error_msg', trans('acl::auth.login.dont_have_account'));
        }
    }

    /**
     * Function that fires when a user accepts an invite.
     *
     * @param string $token Generated token
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getAcceptInvite($token)
    {
        page_title()->setTitle(trans('acl::users.invite_user'));

        if (empty($token)) {
            return redirect()->route('dashboard.index')
                ->with('error_msg', trans('acl::users.invite_not_exist'));
        }

        $invite = $this->inviteRepository->getFirstBy(['token' => $token, 'accepted' => false]);

        if (!empty($invite)) {
            return view('acl::auth.invite', compact('token'));
        }
        return view('acl::auth.invite', ['error_msg' => trans('acl::users.invite_not_exist')]);
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postAcceptInvite(Request $request, $token)
    {
        if (empty($token)) {
            return redirect()->route('dashboard.index')
                ->with('error_msg', trans('acl::users.invite_not_exist'));
        }

        $invite = $this->inviteRepository->getFirstBy(['token' => $token, 'accepted' => false]);

        $user = $this->userRepository->findById($invite->invitee_id);
        if (!$user) {
            return redirect()->route('invite.accept')->with('error_msg', trans('acl::users.invite_not_exist'));
        }

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];
        if (Sentinel::validForCreation($credentials)) {
            $user = Sentinel::update($user, $credentials);

            $activation = Activation::create($user);

            if (Activation::complete($user, $activation->code)) {

                $role = $this->roleRepository->getFirstBy(['id' => $invite->role_id]);

                if (!empty($role)) {
                    $this->roleUserRepository->firstOrCreate(['user_id' => $user->id, 'role_id' => $invite->role_id]);

                    event(new RoleAssignmentEvent($role, $user));
                }

                $invite->accepted = true;
                $this->inviteRepository->createOrUpdate($invite);
            }

            Sentinel::authenticateAndRemember($credentials);
        }
        return redirect()->route('dashboard.index')->with('success_msg', trans('acl::users.accept_invite_success'));
    }
}

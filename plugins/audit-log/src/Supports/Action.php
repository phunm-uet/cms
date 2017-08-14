<?php

namespace Botble\AuditLog\Supports;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Illuminate\Http\Request;

class Action
{
    public function __construct()
    {
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'handleCreated'], 45, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'handleUpdated'], 45, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'handleDeleted'], 45, 3);

        add_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, [$this, 'handleLogin'], 45, 3);
        add_action(AUTH_ACTION_AFTER_LOGOUT_SYSTEM, [$this, 'handleLogout'], 45, 3);

        add_action(USER_ACTION_AFTER_UPDATE_PASSWORD, [$this, 'handleUpdatePassword'], 45, 3);
        add_action(USER_ACTION_AFTER_UPDATE_PASSWORD, [$this, 'handleUpdateProfile'], 45, 3);

        add_action(MEDIA_ACTION_AFTER_SHARE, [$this, 'handleActionShareMedia'], 45, 3);
        add_action(MEDIA_ACTION_AFTER_ATTACH, [$this, 'handleActionAttachMedia'], 45, 3);

        if (defined('BACKUP_ACTION_AFTER_BACKUP')) {
            add_action(BACKUP_ACTION_AFTER_BACKUP, [$this, 'handleBackup'], 45, 2);
            add_action(BACKUP_ACTION_AFTER_RESTORE, [$this, 'handleRestore'], 45, 2);
        }
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
     * @param $screen
     * @param Request $request
     * @param $data
     * @return string
     * @author Sang Nguyen
     */
    protected function getReferenceName($screen, $request, $data)
    {
        $name = null;
        switch ($screen) {
            case USER_MODULE_SCREEN_NAME:
            case AUTH_MODULE_SCREEN_NAME:
                $name = $data->getFullName();
                break;
            case SHARE_MODULE_SCREEN_NAME:
                $name = $request->input('name');
                break;
            case MEDIA_MODULE_SCREEN_NAME:
                $name = $data->mediaRelationName;
                break;
            default:
                if (!empty($data)) {
                    if (isset($data->name)) {
                        $name = $data->name;
                    } elseif (isset($data->title)) {
                        $name = $data->title;
                    }
                }
        }
        return $name;
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleCreated($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'created', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdated($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'updated', $data->id, self::getReferenceName($screen, $request, $data), 'primary'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleDeleted($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'deleted', $data->id, self::getReferenceName($screen, $request, $data), 'danger'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleLogin($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent('to the system', 'logged in', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleLogout($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent('of the system', 'logged out', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdateProfile($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'updated profile', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdatePassword($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'changed password', $data->id, self::getReferenceName($screen, $request, $data), 'danger'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleShareMedia($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'shared', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleAttachMedia($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'attached', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @author Sang Nguyen
     */
    public function handleBackup($screen, Request $request)
    {
        event(new AuditHandlerEvent($screen, 'backup', 0, '', 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @author Sang Nguyen
     */
    public function handleRestore($screen, Request $request)
    {
        event(new AuditHandlerEvent($screen, 'restored', 0, '', 'info'));
    }
}
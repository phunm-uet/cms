<?php

namespace Botble\ACL\Listeners;

use Botble\ACL\Events\RoleUpdateEvent;
use Botble\ACL\Models\User;
use Botble\MenuLeftHand\Models\MenuLeftHand;

class RoleUpdateListener
{
    /**
     * RoleUpdateListener constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RoleUpdateEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(RoleUpdateEvent $event)
    {
        info('Role ' . $event->role->name . ' updated; rebuilding permission sets');
        $permissions = [];
        foreach ($event->role->flags()->get() as $flag) {
            $permissions[$flag->flag] = true;
        }
        foreach ($event->role->users()->get() as $user) {
            // Insert permission flag
            $user_permissions = [];
            if ($user->super_user) {
                $user_permissions['superuser'] = true;
            } else {
                $user_permissions['superuser'] = false;
            }
            if ($user->manage_supers) {
                $user_permissions['manage_supers'] = true;
            } else {
                $user_permissions['manage_supers'] = false;
            }
            User::whereId($user->id)->update(['permissions' => json_encode(array_merge($permissions, $user_permissions))]);
        }
        MenuLeftHand::buildMenu();
    }
}

<?php

namespace Botble\ACL\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     * @author Sang Nguyen
     */
    protected $listen = [
        'Botble\ACL\Events\RoleUpdateEvent' => [
            'Botble\ACL\Listeners\RoleUpdateListener',
        ],
        'Botble\ACL\Events\RoleAssignmentEvent' => [
            'Botble\ACL\Listeners\RoleAssignmentListener',
        ],
    ];
}

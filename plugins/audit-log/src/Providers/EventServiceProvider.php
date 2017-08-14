<?php

namespace Botble\AuditLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Botble\AuditLog\Events\AuditHandlerEvent' => [
            'Botble\AuditLog\Listeners\AuditHandlerListener',
        ],
    ];
}

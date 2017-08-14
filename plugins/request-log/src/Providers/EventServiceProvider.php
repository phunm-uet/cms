<?php

namespace Botble\RequestLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Botble\RequestLog\Events\RequestHandlerEvent' => [
            'Botble\RequestLog\Listeners\RequestHandlerListener',
        ],
    ];
}

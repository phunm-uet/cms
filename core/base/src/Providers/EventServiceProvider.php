<?php

namespace Botble\Base\Providers;

use Event;
use File;
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
        'Botble\Base\Events\SendMailEvent' => [
            'Botble\Base\Listeners\SendMailListener',
        ],
    ];

    /** Boot the service provider.
    * @return void
    * @author Sang Nguyen
    */
    public function boot()
    {
        parent::boot();

        Event::listen(['cache:cleared'], function () {
            File::delete(config('cms.cache_store_keys'));
        });

        Event::listen(['eloquent.creating: *', 'eloquent.saving: *', 'eloquent.deleting: *'], function () {
            //throw new \Exception('Can not update data in demo mode!');
        });
    }
}

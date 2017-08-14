<?php

namespace Botble\Contact\Providers;

use Botble\Base\Supports\Helper;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Contact\Models\Contact;
use Botble\Contact\Repositories\Caches\ContactCacheDecorator;
use Botble\Contact\Repositories\Eloquent\ContactRepository;
use Botble\Base\Services\Cache\Cache;
use Botble\Contact\Supports\Filter;
use Botble\Contact\Supports\Shortcode;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {

        if (setting('enable_cache', false)) {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactCacheDecorator(new ContactRepository(new Contact()), new Cache($this->app['cache'], ContactRepository::class));
            });
        } else {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactRepository(new Contact());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/contact.php', 'contact');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'contact');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'contact');

        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/contact')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/contact')], 'lang');
            $this->publishes([__DIR__ . '/../../config/contact.php' => config_path('contact.php')], 'config');
        }

        Shortcode::initialize();
        Filter::initialize();

        add_admin_menu([
            'route' => 'contacts.list',
            'sequence' => 5,
            'name' => trans('contact::contact.menu'),
            'kind' => 'page',
            'icon' => 'fa fa-envelope-o',
        ]);
    }
}

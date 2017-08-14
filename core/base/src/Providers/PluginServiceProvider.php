<?php

namespace Botble\Base\Providers;

use Botble\Base\Repositories\Interfaces\PluginInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Schema;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        if (check_database_connection() && Schema::hasTable('plugins')) {
            $plugins = app(PluginInterface::class)->allBy(['status' => 1]);
            if ($plugins instanceof Collection && !empty($plugins)) {
                foreach ($plugins as $plugin) {
                    if (class_exists($plugin->provider)) {
                        $this->app->register($plugin->provider);
                    } else {
                        app(PluginInterface::class)->deleteBy(['provider' => $plugin->provider]);
                    }
                }
            }
        }
    }
}

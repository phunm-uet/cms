<?php

namespace Botble\Base\Providers;

use Botble\Base\Commands\DumpAutoload;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Commands\ClearLogCommand;
use Botble\Base\Commands\InstallCommand;
use Botble\Base\Commands\PluginActivateCommand;
use Botble\Base\Commands\PluginCreateCommand;
use Botble\Base\Commands\PluginDeactivateCommand;
use Botble\Base\Commands\PluginRemoveCommand;
use Botble\Base\Commands\RebuildPermissionsCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $this->commands([
            RebuildPermissionsCommand::class,
            DumpAutoload::class,
            PluginCreateCommand::class,
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            ClearLogCommand::class,
        ]);
    }
}

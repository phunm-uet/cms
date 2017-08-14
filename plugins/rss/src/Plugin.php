<?php

namespace Botble\Rss;

use Artisan;
use Botble\Base\Supports\Commands\Permission;
use Schema;
use Botble\Base\Interfaces\PluginInterface;

class Plugin implements PluginInterface
{

    /**
     * @return array
     * @author Sang Nguyen
     */
    public static function permissions()
    {
        return [
            [
                'name' => 'Rss',
                'flag' => 'rss.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'rss.create',
                'parent_flag' => 'rss.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'rss.edit',
                'parent_flag' => 'rss.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'rss.delete',
                'parent_flag' => 'rss.list'
            ]
        ];
    }

    /**
     * @author Sang Nguyen
     */
    public static function activate()
    {
        $permissions = self::permissions();
        Permission::registerPermission($permissions);
        Artisan::call('migrate', ['--force' => true, '--path' => 'plugins/rss/database/migrations']);
    }

    /**
     * @author Sang Nguyen
     */
    public static function deactivate()
    {

    }

    /**
     * @author Sang Nguyen
     */
    public static function remove()
    {
        $permissions = self::permissions();
        Permission::removePermission($permissions);
        Schema::dropIfExists('rss');
    }
}
<?php

namespace Botble\Block;

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
                'name' => 'Block',
                'flag' => 'block
                .list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'block.create',
                'parent_flag' => 'block.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'block.edit',
                'parent_flag' => 'block.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'block.delete',
                'parent_flag' => 'block.list'
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
        Artisan::call('migrate', ['--force' => true, '--path' => 'plugins/block/database/migrations']);
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
        Schema::dropIfExists('block');
    }
}
<?php
namespace Botble\Backup;

use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;

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
                'name' => 'Backup',
                'flag' => 'backups.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'backups.create',
                'parent_flag' => 'backups.list'
            ],
            [
                'name' => 'Restore',
                'flag' => 'backups.restore',
                'parent_flag' => 'backups.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'backups.delete',
                'parent_flag' => 'backups.list'
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
    }
}
<?php
namespace Botble\Language;

use Artisan;
use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;
use Schema;

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
                'name' => 'Languages',
                'flag' => 'languages.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'languages.create',
                'parent_flag' => 'languages.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'languages.edit',
                'parent_flag' => 'languages.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'languages.delete',
                'parent_flag' => 'languages.list'
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
        Artisan::call('migrate', ['--force' => true, '--path' => 'plugins/language/database/migrations']);
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
        Schema::dropIfExists('languages');
        Schema::dropIfExists('language_meta');
    }
}
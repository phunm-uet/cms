<?php

namespace Botble\Translation;

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
                'name' => 'Translation',
                'flag' => 'translations.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'translations.create',
                'parent_flag' => 'translations.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'translations.edit',
                'parent_flag' => 'translations.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'translations.delete',
                'parent_flag' => 'translations.list'
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
        Artisan::call('migrate', ['--force' => true, '--path' => 'plugins/translation/database/migrations']);
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
        Schema::dropIfExists('translations');
    }
}
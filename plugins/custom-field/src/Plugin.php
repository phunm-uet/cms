<?php
namespace Botble\CustomField;

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
                'name' => 'Custom Fields',
                'flag' => 'custom-fields.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'custom-fields.create',
                'parent_flag' => 'custom-fields.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'custom-fields.edit',
                'parent_flag' => 'custom-fields.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'custom-fields.delete',
                'parent_flag' => 'custom-fields.list'
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
        Artisan::call('migrate', ['--force' => true, '--path' => 'plugins/custom-field/database/migrations']);
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

        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('field_items');
        Schema::dropIfExists('field_groups');
    }
}
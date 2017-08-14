<?php
namespace Botble\Setting\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Botble\Setting\Models\SettingModel
 *
 * @mixin \Eloquent
 */
class Setting extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $fillable = [];
}

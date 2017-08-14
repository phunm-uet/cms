<?php
namespace Botble\Language\Models;

use Eloquent;

/**
 * Botble\Language\Models\Language
 *
 * @mixin \Eloquent
 */
class Language extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';

    protected $fillable = ['name', 'locale', 'code', 'is_rtl', 'flag', 'order'];
}

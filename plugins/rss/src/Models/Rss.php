<?php

namespace Botble\Rss\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Botble\Rss\Models\Rss
 *
 * @mixin \Eloquent
 */
class Rss extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rss';

    protected $fillable = ['name'];
}

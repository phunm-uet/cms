<?php

namespace Botble\Media\Models;

use Botble\ACL\Models\User;
use Eloquent;

class MediaShare extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     * @author Sang Nguyen
     */
    protected $table = 'media_shares';

    /**
     * The date fields for the model.clear
     *
     * @var array
     * @author Sang Nguyen
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $fillable = ['share_type', 'share_id', 'shared_by', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

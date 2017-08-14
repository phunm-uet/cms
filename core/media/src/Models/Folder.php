<?php

namespace Botble\Media\Models;

use Eloquent;

class Folder extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_folders';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    public $reservedNames = [
        'shared',
        'share',
        'shares',
        'type',
        'avatars'
    ];

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function isShared()
    {
        return MediaShare::where('share_id', '=', $this->id)->where('share_type', '=', 'folder')->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Sang Nguyen
     */
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Sang Nguyen
     */
    public function parentFolder()
    {
        return $this->hasOne(Folder::class, 'id', 'parent');
    }

    /**
     * @author Sang Nguyen
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($folder) {
            // called BEFORE delete()
            // Delete any shares of this folder
            MediaShare::where('share_id', '=', $folder->id)->where('share_type', '=', 'folder')->delete();
        });
    }

    /**
     * @author Sang Nguyen
     */
    public function __wakeup()
    {
        parent::boot();
    }
}

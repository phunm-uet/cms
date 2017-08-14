<?php

namespace Botble\Media\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Sentinel;

/**
 * Class FileSystemRepository
 * @package Botble\Media
 * @author Sang Nguyen
 * @since 19/08/2015 07:45 AM
 */
class FileRepository extends RepositoriesAbstract implements FileInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceUsed()
    {
        // personal quota
        return $this->model->where('user_id', '=', Sentinel::getUser()->id)
            ->sum('size');
    }

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function getSpaceLeft()
    {
        return ($this->getQuota() - $this->getSpaceUsed());
    }

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function getQuota()
    {
        // personal quota
        return Sentinel::getUser()->personal_quota;
    }

    /**
     * @return float
     * @author Sang Nguyen
     */
    public function getPercentageUsed()
    {
        if ($this->getQuota() === 0 || empty($this->getQuota())) {
            return round(100, 2);
        } else {
            return round(($this->getSpaceUsed() / $this->getQuota()) * 100, 2);
        }
    }

    /**
     * @param $name
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createName($name, $folder)
    {
        $index = 1;
        $baseName = $name;
        while ($this->model->where('name', '=', $name)
            ->where('folder_id', '=', $folder)
            ->where('user_id', '=', Sentinel::getUser()->id)
            ->first()) {
            $name = '(' . $index++ . ') ' . $baseName;
        }
        return $name;
    }

    /**
     * @param $name
     * @param $extension
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $extension, $folder)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereName($slug . '.' . $extension)->where('folder_id', '=', $folder)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        return $slug . '.' . $extension;
    }

    /**
     * @param $folder_id
     * @param array $type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFilesByFolderId($folder_id, $type = [])
    {
        $files = $this->model->where('folder_id', '=', $folder_id)
            ->where('user_id', '=', Sentinel::getUser()->id);
        if (!empty($type)) {
            $files = $files->whereIn('type', $type);
        }

        return $files->orderBy('name', 'asc')
            ->get();
    }
}

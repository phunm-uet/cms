<?php
namespace Botble\Media\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Sentinel;

/**
 * Class FileSystemRepository
 * @package Botble\Media
 * @author Sang Nguyen
 * @since 19/08/2015 07:45 AM
 */
class FolderRepository extends RepositoriesAbstract implements FolderInterface
{

    /**
     * @param $folderId
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFolderByParentId($folderId)
    {
        return $this->model->where('parent', '=', $folderId)
            ->where(function ($query) {
                $query->orWhere('user_id', '=', Sentinel::getUser()->id)
                    ->orWhere('user_id', '=', 0);
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param $name
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereSlug($slug)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        return $slug;
    }

    /**
     * @param $name
     * @param $parent
     * @return mixed
     * @author Sang Nguyen
     */
    public function createName($name, $parent)
    {
        $newName = $name;
        $index = 1;
        $baseSlug = $newName;
        while ($this->model->whereUserId(Sentinel::getUser()->id)
                ->whereName($newName)
                ->whereParent($parent)
                ->count() > 0) {
            $newName = $baseSlug . '-' . $index++;
        }

        return $newName;
    }
}

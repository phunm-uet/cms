<?php
namespace Botble\Media\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Media\Exceptions\MediaInvalidParent;
use Botble\Media\Models\File;
use Botble\Media\Models\Folder;
use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Exception;
use Sentinel;

/**
 * Class MediaShareRepository
 * @package Botble\Media
 */
class MediaShareRepository extends RepositoriesAbstract implements MediaShareInterface
{

    /**
     * @param $share_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getShareWithUser($share_id)
    {
        return $this->model->where('media_shares.id', '=', $share_id)
            ->select(['id', 'user_id', 'created_at'])
            ->with('user')
            ->first();
    }

    /**
     * @param $file
     * @return array
     * @throws Exception
     * @author Sang Nguyen
     */
    public function unshareFile($file)
    {
        if (!$file) {
            return ['error' => true, 'message' => trans('media::media.non_valid_file')];
        }
        $this->model->where('share_id', '=', $file)
            ->where('share_type', '=', 'file')
            ->delete();
        return ['error' => false, 'message' => trans('media::media.unshare_file_success')];
    }

    /**
     * @param int $folder
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder = 0)
    {
        return File::join('media_shares', 'media_storage.id', '=', 'media_shares.share_id')
            ->where('media_shares.share_type', '=', 'file')
            ->where('media_shares.user_id', '=', Sentinel::getUser()->id)
            ->select(['media_shares.share_id as id', 'media_storage.*'])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param int $folder
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder = 0)
    {

        return Folder::join('media_shares', 'media_folders.id', '=', 'media_shares.share_id')
            ->where('media_shares.share_type', '=', 'folder')
            ->where('media_shares.user_id', '=', Sentinel::getUser()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param $shareId
     * @param $shareType
     * @return array
     * @author Sang Nguyen
     */
    public function getFileShares($shareId, $shareType)
    {
        if (!$shareId || empty($shareId)) {
            return ['error' => true, 'message' => trans('media::media.invalid_share')];
        }

        try {
            $shares = $this->model->where('shared_by', '=', Sentinel::getUser()->id)
                ->where('share_id', '=', $shareId)
                ->where('share_type', '=', $shareType)
                ->leftJoin('users', 'users.id', '=', 'media_shares.user_id')
                ->select(['media_shares.id', 'user_id', 'first_name', 'last_name', 'media_shares.created_at'])
                ->get();
        } catch (Exception $e) {
            return ['error' => true,
                'message' => trans('media::media.get_list_share_error'),
                'message_detail' => $e->getMessage()
            ];
        }

        if (!count($shares)) {
            $row = view('media::partials.share-table-row-empty');
            return ['error' => false, 'data' => [$row->render()]];
        } else {
            $shareList = [];
            foreach ($shares as $share) {
                $row = view('media::partials.share-table-row')
                    ->with('share', $share);
                $shareList[] = $row->render();
            }
            return ['error' => false, 'data' => [$shareList]];
        }
    }

    /**
     * @param $folderId
     * @return array
     * @throws MediaInvalidParent
     * @author Sang Nguyen
     */
    public function getMyShareDirectory($folderId)
    {
        $folder = 0;
        if (is_string($folderId) && $folderId !== '0') {
            try {
                $folder = Folder::findBySlug($folderId);
            } catch (Exception $e) {
                throw new MediaInvalidParent;
            }
        }

        $parent = false;
        $parents = [0, $folderId];
        $pf = $folder;
        while ($parent !== 0) {
            $pf = $this->getFolderParent($pf);
            if ($pf !== 0) {
                $parents[] = $pf->id;
                $parent = $pf->id;
            } else {
                $parent = 0;
            }
        }

        $allowed = $this->model->whereIn('share_id', $parents)
            ->where('share_type', '=', 'folder')
            ->where('media_shares.user_id', '=', Sentinel::getUser()->id)
            ->count();
        if (!$allowed) {
            throw new MediaInvalidParent;
        }

        // Get the folders
        $folders = Folder::where('parent', '=', $folderId)
            ->orderBy('name', 'asc')
            ->get();

        // Get all the files
        $files = File::where('folder_id', '=', $folderId)
            ->orderBy('name', 'asc')
            ->get();

        // Get parent folder details
        if ($folderId == 0) {
            $parent = -1;
        } elseif ($folder->parent == 0) {
            $parent = 0;
        } else {
            $parent = $folder->parentFolder()
                ->first();
        }

        return ['folders' => $folders, 'files' => $files, 'parentFolder' => $parent];
    }

    /**
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    private function getFolderParent($folder)
    {
        if ($folder->parent == 0 || !is_object($folder)) {
            return 0;
        }
        return Folder::find($folder->parent);
    }
}

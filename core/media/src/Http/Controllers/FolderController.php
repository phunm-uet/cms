<?php
namespace Botble\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Media\Http\Requests\FolderRequest;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class FolderController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 * @since 19/08/2015 07:55 AM
 */
class FolderController extends Controller
{
    /**
     * @var FolderInterface
     */
    protected $folderRepository;

    /**
     * @var FileInterface
     */
    protected $fileRepository;

    /**
     * FolderController constructor.
     * @param FolderInterface $folderRepository
     * @param FileInterface $fileRepository
     * @author Sang Nguyen
     */
    public function __construct(FolderInterface $folderRepository, FileInterface $fileRepository)
    {
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param FolderRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postCreate(FolderRequest $request)
    {
        $name = $request->input('name');

        if (in_array($name, $this->folderRepository->getModel()->reservedNames)) {
            return ['error' => true, 'message' => trans('media::media.name_reserved'), 'name' => $name];
        } else {
            view()->share('media_show_url', 'files.gallery.ajax');
            try {
                $parent_id = $request->input('parent', 0);

                if (is_string($parent_id)) {
                    $parent = $this->folderRepository->getFirstBy(['slug' => $parent_id]);
                    if ($parent) {
                        $parent_id = $parent->id;
                    }
                }

                $folder = $this->folderRepository->getModel();
                $folder->user_id = Sentinel::getUser()->id;
                $folder->name = $this->folderRepository->createName($name, $parent_id);
                $folder->slug = $this->folderRepository->createSlug($name);
                $folder->parent = $parent_id;
                $folder = $this->folderRepository->createOrUpdate($folder);
                do_action(BASE_ACTION_AFTER_CREATE_CONTENT, FOLDER_MODULE_SCREEN_NAME, $request, $folder);
                return [
                    'error' => false,
                    'message' => trans('media::media.folder_created'),
                    'id' => $folder->id,
                    'name' => $name,
                    'slug' => $folder->slug,
                    'table_row' => view('media::partials.folder-row')->with('folder', $folder)->render()
                ];
            } catch (Exception $ex) {
                return ['error' => true, 'message' => $ex->getMessage()];
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function deleteFolder(Request $request)
    {
        $slug = $request->input('slug');
        try {
            $folder = $this->folderRepository->getFirstBy(['slug' => $slug]);
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('media::media.cannot_delete_folder')];
        }
        if (count($this->folderRepository->getFolderByParentId($folder->id)) || count($this->fileRepository->getFilesByFolderId($folder->id))) {
            return ['error' => true, 'message' => trans('media::media.folder_not_empty')];
        } else {
            $folder = $this->folderRepository->findById($folder->id);
            $this->folderRepository->delete($folder);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, FOLDER_MODULE_SCREEN_NAME, $request, $folder);
        }
        return ['error' => false, 'message' => trans('media::media.folder_deleted')];
    }

    /**
     * @param FolderRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function renameFolder(FolderRequest $request)
    {
        $folder = $this->folderRepository->findById($request->input('id'));

        if ($folder) {
            $folder->name = $this->folderRepository->createName($request->input('name'), $folder->parent);
            $this->folderRepository->createOrUpdate($folder);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, FOLDER_MODULE_SCREEN_NAME, $request, $folder);
            return ['message' => trans('media::media.rename_folder_success'), 'id' => $folder->slug];
        } else {
            return ['message' => trans('media::media.folder_not_exists')];
        }

    }
}

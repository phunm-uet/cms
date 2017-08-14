<?php
namespace Botble\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Media\Http\Requests\FileRequest;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Botble\Media\Services\UploadsManager;
use Exception;
use File;
use Illuminate\Http\Request;
use Image;
use Sentinel;

/**
 * Class FileController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 * @since 19/08/2015 07:50 AM
 */
class FileController extends Controller
{
    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * @var FileInterface
     */
    protected $fileRepository;

    /**
     * @var FolderInterface
     */
    protected $folderRepository;

    /**
     * @param FileInterface $fileRepository
     * @param FolderInterface $folderRepository
     * @param UploadsManager $uploadManager
     * @author Sang Nguyen
     */
    public function __construct(FileInterface $fileRepository, FolderInterface $folderRepository, UploadsManager $uploadManager)
    {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
        $this->uploadManager = $uploadManager;
    }

    /**
     * @param FileRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postEdit(FileRequest $request)
    {
        try {
            $name = $request->input('name');
            $folderId = $request->input('folder');
            $url = $request->input('url');
            $type = $request->input('type');
            $file = $this->fileRepository->getModel();

            $folder = $this->folderRepository->getFirstBy(['slug' => $folderId], ['id', 'slug']);
            if ($folder) {
                $folderId = $folder->id;
                $folderName = $folder->slug;
            } else {
                $folderId = 0;
                $folderName = null;
            }

            if ($type == 'youtube') {
                $file->name = $this->fileRepository->createName($name, $folderId);
                $file->public_url = $url;
                $file->size = 0;
                $file->mime_type = $type;
                $file->type = $type;
            } else {
                $fileUpload = $request->file('file');

                $fileName = time() . '-' . $this->fileRepository->createSlug(basename($fileUpload->getClientOriginalName(), $fileUpload->getClientOriginalExtension()), $fileUpload->getClientOriginalExtension(), $folderId);


                $path = str_finish($folderName, '/') . $fileName;
                $content = File::get($fileUpload->getRealPath());

                $this->uploadManager->saveFile($path, $content);

                if (is_image($this->uploadManager->fileMimeType($path))) {
                    $thumb_size = explode('x', config('media.thumb-size'));
                    $featured_size = explode('x', config('media.featured-size'));
                    Image::make($fileUpload)->fit($thumb_size[0], $thumb_size[1])->save($this->uploadManager->uploadPath(str_finish($folderName, '/')) . File::name($fileName) . '-' . config('media.thumb-size') . '.' . $fileUpload->getClientOriginalExtension());
                    Image::make($fileUpload)->fit($featured_size[0], $featured_size[1])->save($this->uploadManager->uploadPath(str_finish($folderName, '/')) . File::name($fileName) . '-' . config('media.featured-size') . '.' . $fileUpload->getClientOriginalExtension());
                }

                $data = $this->uploadManager->fileDetails($path);

                if (empty($data['mime_type'])) {
                    return [
                        'error' => true,
                        'message' => trans('media::media.can_not_detect_file_type')
                    ];
                }

                $file->name = $this->fileRepository->createName(File::name($fileUpload->getClientOriginalName()), $folderId);
                $file->public_url = $data['url'];
                $file->size = $data['size'];
                $file->mime_type = $data['mime_type'];
                $file->type = $data['mime_type'];
            }

            $file->folder_id = $folderId;
            $file->user_id = Sentinel::getUser()->id;
            $file = $this->fileRepository->createOrUpdate($file);

            do_action(BASE_ACTION_AFTER_CREATE_CONTENT, FILE_MODULE_SCREEN_NAME, $request, $file);
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage()
            ];
        }
        return [
            'error' => false,
            'message' => trans('media::media.add_file_success'),
            'data' => $file,
            'file_rows' => view('media::partials.file-row')->with('file', $file)->render()
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function deleteFile(Request $request)
    {
        $file = $this->fileRepository->findById($request->input('id'));
        $path = str_replace(config('filesystems.path'), '', $file->public_url);
        $this->uploadManager->deleteFile($path);

        try {
            $file = $this->fileRepository->findById($request->input('id'));
            $this->fileRepository->delete($file);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, FILE_MODULE_SCREEN_NAME, $request, $file);
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('media::media.cannot_delete_file')];
        }
        return ['error' => false, 'message' => trans('media::media.file_deleted')];
    }

    /**
     * @param FileRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function renameFile(FileRequest $request)
    {
        $file = $this->fileRepository->findById($request->input('id'));

        if ($file) {
            $file->name = $this->fileRepository->createName($request->input('name'), $file->folder_id);
            $this->fileRepository->createOrUpdate($file);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, FILE_MODULE_SCREEN_NAME, $request, $file);
            return ['error' => false, 'message' => trans('media::media.rename_success'), 'id' => $file->id];
        } else {
            return ['error' => true, 'message' => trans('media::media.file_not_exists')];
        }

    }
}

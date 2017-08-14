<?php

namespace Botble\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Assets;
use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Illuminate\Http\Request;
use Botble\Media\Exceptions\MediaInvalidParent;
use MediaLibrary;

/**
 * Class MediaController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 * @since 19/08/2015 08:05 AM
 */
class MediaController extends Controller
{
    /**
     * @var FileInterface
     */
    protected $fileRepository;

    /**
     * @var FolderInterface
     */
    protected $folderRepository;

    /**
     * @var MediaShareInterface
     */
    protected $mediaShareRepository;

    /**
     * MediaController constructor.
     * @param FileInterface $fileRepository
     * @param FolderInterface $folderRepository
     * @param MediaShareInterface $mediaShareRepository
     * @author Sang Nguyen
     */
    public function __construct(FileInterface $fileRepository, FolderInterface $folderRepository, MediaShareInterface $mediaShareRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
        $this->mediaShareRepository = $mediaShareRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getIndex(Request $request)
    {
        page_title()->setTitle('Media');

        MediaLibrary::registerMediaLibrary();

        $folderSlug = $request->input('folder');
        $contents = $this->getDirectory($folderSlug);
        $sharedFiles = $this->mediaShareRepository->getSharedFiles();
        $sharedFolders = $this->mediaShareRepository->getSharedFolders();
        return view('media::index')
            ->with('contents', $contents)
            ->with('filesystem', $this->fileRepository)
            ->with('sharedFolders', $sharedFolders)
            ->with('sharedFiles', $sharedFiles)
            ->with('currentFolder', $folderSlug)
            ->with('sharedFolder', 0);
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getQuota()
    {
        return [
            'quota' => human_file_size($this->fileRepository->getQuota()),
            'used' => human_file_size($this->fileRepository->getSpaceUsed()),
            'percent' => $this->fileRepository->getPercentageUsed(),
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getGallery(Request $request)
    {
        $action = $request->input('action');
        $folderSlug = $request->input('folder');

        session()->forget('media_action');
        session()->put('media_action', $action);

        try {
            $contents = $this->getDirectory($folderSlug);
        } catch (MediaInvalidParent $e) {
            return redirect()->route('files.gallery.show')
                ->with('error_msg', trans('acl::feature.folder_not_exist'));
        }

        return view('media::gallery')
            ->with('contents', $contents)
            ->with('filesystem', $this->fileRepository)
            ->with('currentFolder', $folderSlug);
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getAjaxMediaFolder(Request $request)
    {
        $folderSlug = $request->input('folder');
        try {
            $contents = $this->getDirectory($folderSlug);
        } catch (MediaInvalidParent $e) {
            return ['error' => true, 'message' => trans('acl::feature.cannot_read_folder')];
        }

        $files = null;
        foreach ($contents['files'] as $file) {
            $files .= view('media::partials.file-row')
                ->with('file', $file)
                ->render();
        }
        $uplevel = view('media::partials.uplevel')
            ->with('folder', $contents['parentFolder'])
            ->render();
        $folders = null;
        if (count($contents['folders']) > 0) {
            foreach ($contents['folders'] as $new_folder) {
                $folders .= view('media::partials.folder-row')
                    ->with('folder', $new_folder)
                    ->render();
            }
        }
        return [
            'error' => false,
            'files' => $files,
            'uplevel' => $uplevel,
            'folders' => $folders,
            'currentFolder' => $contents['currentFolder']
        ];
    }

    /**
     * @param null $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function getShared(Request $request)
    {
        $folder = $request->input('folder');
        $files = $this->mediaShareRepository->getSharedFiles($folder);
        $contents['files'] = $files;
        $folders = null;
        $contents['folders'] = $this->mediaShareRepository->getSharedFolders();
        foreach ($contents['folders'] as $sharedFolder) {
            $folders .= view('media::partials.folder-row')
                ->with('folder', $sharedFolder)
                ->render();
        }
        return view('media::gallery')
            ->with('contents', $contents)
            ->with('filesystem', $this->fileRepository)
            ->with('folders', $folders)
            ->with('shared', true)
            ->with('currentFolder', $folder);
    }

    /**
     * @param $folderSlug
     * @return array|\Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    private function getDirectory($folderSlug)
    {
        try {
            $contents = [];
            $folder = null;
            if (is_string($folderSlug) && $folderSlug !== null) {
                $folder = $this->folderRepository->getFirstBy(['slug' => $folderSlug]);
                if (!$folder) {
                    throw new MediaInvalidParent;
                }
                $folderId = $folder->id;
            } else {
                $folderId = 0;
            }
            // Get the folders
            $contents['folders'] = $this->folderRepository->getFolderByParentId($folderId);
            if (session('media_action') == 'featured_image') {
                // Get all the files
                $contents['files'] = $this->fileRepository->getFilesByFolderId($folderId, ['image/jpeg', 'image/jpg', 'image/png']);
            } else {
                // Get all the files
                $contents['files'] = $this->fileRepository->getFilesByFolderId($folderId);
            }
            // Get parent folder details
            if ($folderId == 0) {
                $contents['parentFolder'] = -1;
            } elseif ($folder->parent == 0) {
                $contents['parentFolder'] = null;
            } else {
                $contents['parentFolder'] = $folder->parentFolder()
                    ->first()->slug;
            }

            $contents['currentFolder'] = $folderSlug != null ? $folderSlug : 0;
            return $contents;
        } catch (MediaInvalidParent $e) {
            return redirect()->route('media.index')
                ->with('error_msg', trans('acl::feature.folder_not_exist'));
        }
    }
}

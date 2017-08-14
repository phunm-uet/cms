<?php
namespace Botble\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Assets;
use Botble\Media\Exceptions\MediaInvalidParent;
use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Media\Repositories\Interfaces\FileInterface;
use Botble\Media\Repositories\Interfaces\FolderInterface;
use Botble\Media\Http\Requests\MediaShareRequest;
use Illuminate\Http\Request;
use MediaLibrary;
use Sentinel;

/**
 * Class MediaShareController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 * @since 19/08/2015 08:05 AM
 */
class MediaShareController extends Controller
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
     * MediaShareController constructor.
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
     * @param MediaShareRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postShare(MediaShareRequest $request)
    {
        $shareWithUsers = $request->input('shareWithUsers', []);
        $type = $request->input('type', 'file');

        if (!count($shareWithUsers)) {
            return ['error' => true, 'message' => trans('media::media.no_people_selected')];
        }
        $shareList = [];
        foreach ($shareWithUsers as $userId) {
            $share = $this->mediaShareRepository->firstOrCreate([
                'share_type' => $type,
                'share_id' => $request->input('itemId', 0),
                'shared_by' => Sentinel::getUser()->id,
                'user_id' => $userId
            ]);

            do_action(MEDIA_ACTION_AFTER_SHARE, SHARE_MODULE_SCREEN_NAME, $request, $share);

            $shareList[] = view('media::partials.share-table-row')
                ->with('share', $this->mediaShareRepository->getShareWithUser($share->id))
                ->render();
        }
        return [
            'error' => false,
            'message' => trans('media::media.folder_share_success'),
            'data' => $shareList
        ];
    }

    /**
     * @param MediaShareRequest $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postUnshare(MediaShareRequest $request)
    {
        return $this->mediaShareRepository->unshare($request->input('itemId', 0));
    }

    /**
     * @param MediaShareRequest $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function getList(MediaShareRequest $request)
    {
        return $this->mediaShareRepository->getFileShares($request->input('shareId', 0), $request->input('shareType', 0));
    }

    /**
     * @param MediaShareRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postDelete(MediaShareRequest $request)
    {
        $shareId = $request->input('shareId', 0);
        if (!$shareId) {
            return ['error' => true, 'message' => trans('media::media.invalid_share')];
        }
        $share = $this->mediaShareRepository->findById($shareId);
        $this->mediaShareRepository->delete($share);
        return [
            'error' => false,
            'message' => trans('media::media.delete_share_success'),
            'data' => $shareId
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getSharedWithMe(Request $request)
    {
        $folder = $request->input('folder');

        MediaLibrary::registerMediaLibrary();

        try {
            // get root folder
            $contents = $this->folderRepository->getDirectory(null);
            $shareContents = $this->mediaShareRepository->getMyShareDirectory($folder);
        } catch (MediaInvalidParent $e) {
            return redirect()->route('media.index')
                ->with('error_msg', trans('acl::feature.folder_not_exist'));
        }

        $sharedFiles = $this->mediaShareRepository->getSharedFiles($folder);
        $sharedFolders = $this->mediaShareRepository->getSharedFolders($folder);

        view()->share('media_show_url', 'files.gallery.ajax');
        view()->share('media_index_url', 'media.index');
        view()->share('media_mode', 'system');

        return view('media::index')
            ->with('contents', $contents)
            ->with('shareContents', $shareContents)
            ->with('filesystem', $this->fileRepository)
            ->with('sharedFolders', $sharedFolders)
            ->with('sharedFiles', $sharedFiles)
            ->with('currentFolder', null)
            ->with('sharedFolder', $folder)
            ->with('hash', '#medialibrary-shared')
            ->with('action', null);
    }
}

<?php

namespace Botble\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Gallery\Http\DataTables\GalleryDataTable;
use Botble\Gallery\Http\Requests\GalleryRequest;
use Assets;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Exception;
use Illuminate\Http\Request;
use MediaLibrary;
use Sentinel;

class GalleryController extends Controller
{

    /**
     * @var GalleryInterface
     */
    protected $galleryRepository;

    /**
     * @param GalleryInterface $galleryRepository
     * @author Sang Nguyen
     */
    public function __construct(GalleryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * Display all galleries
     * @param GalleryDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(GalleryDataTable $dataTable)
    {
        page_title()->setTitle(trans('gallery::gallery.list'));

        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $dataTable->render('gallery::list');
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('gallery::gallery.create'));

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['are-you-sure']);
        Assets::addAppModule(['media', 'slug']);

        $galleries = $this->galleryRepository->pluck('name', 'id');
        $galleries[0] = 'None';
        $galleries = array_sort_recursive($galleries);

        return view('gallery::create', compact('galleries'));
    }

    /**
     * Insert new Gallery into database
     *
     * @param GalleryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(GalleryRequest $request)
    {
        $gallery = $this->galleryRepository->getModel();
        $gallery->fill($request->all());
        $gallery->user_id = Sentinel::getUser()->getUserId();
        $gallery->featured = $request->input('featured', false);

        $gallery = $this->galleryRepository->createOrUpdate($gallery);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, GALLERY_MODULE_SCREEN_NAME, $request, $gallery);

        if ($request->input('submit') === 'save') {
            return redirect()->route('galleries.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('galleries.edit', $gallery->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * Show edit form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {
        page_title()->setTitle(trans('gallery::gallery.edit') . ' #' . $id);

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['are-you-sure']);
        Assets::addAppModule(['slug']);

        $gallery = $this->galleryRepository->findById($id);
        $galleries = $this->galleryRepository->pluck('name', 'id');
        $galleries[0] = 'None';
        $galleries = array_sort_recursive($galleries);

        return view('gallery::edit', compact('gallery', 'galleries'));
    }

    /**
     * @param $id
     * @param GalleryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, GalleryRequest $request)
    {
        $gallery = $this->galleryRepository->findById($id);
        $gallery->fill($request->all());
        $gallery->featured = $request->input('featured', false);

        $this->galleryRepository->createOrUpdate($gallery);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, GALLERY_MODULE_SCREEN_NAME, $request, $gallery);

        if ($request->input('submit') === 'save') {
            return redirect()->route('galleries.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('galleries.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id)
    {
        try {
            $gallery = $this->galleryRepository->findById($id);
            $this->galleryRepository->delete($gallery);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, GALLERY_MODULE_SCREEN_NAME, $request, $gallery);

            return ['error' => false, 'message' => trans('bases::notices.deleted')];
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('bases::notices.cannot_delete')];
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('bases::notices.no_select')];
        }

        foreach ($ids as $id) {
            $gallery = $this->galleryRepository->findById($id);
            $this->galleryRepository->delete($gallery);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, GALLERY_MODULE_SCREEN_NAME, $request, $gallery);
        }

        return ['error' => false, 'message' => trans('bases::notices.delete_success_message')];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeStatus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('bases::notices.no_select')];
        }

        foreach ($ids as $id) {
            $gallery = $this->galleryRepository->findById($id);
            $gallery->status = $request->input('status');
            $this->galleryRepository->createOrUpdate($gallery);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, GALLERY_MODULE_SCREEN_NAME, $request, $gallery);
        }

        return ['error' => false, 'status' => $request->input('status'), 'message' => trans('bases::notices.update_success_message')];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->galleryRepository->createSlug($request->input('name'), $request->input('id'));
    }
}

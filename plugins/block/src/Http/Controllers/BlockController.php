<?php

namespace Botble\Block\Http\Controllers;

use Assets;
use Botble\Block\Http\Requests\BlockRequest;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MediaLibrary;
use MongoDB\Driver\Exception\Exception;
use Botble\Block\Http\DataTables\BlockDataTable;
use Sentinel;

class BlockController extends Controller
{
    /**
     * @var BlockInterface
     */
    protected $blockRepository;

    /**
     * BlockController constructor.
     * @param BlockInterface $blockRepository
     * @author Sang Nguyen
     */
    public function __construct(BlockInterface $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    /**
     * Display all block
     * @param BlockDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(BlockDataTable $dataTable)
    {
        page_title()->setTitle(trans('block::block.list'));

        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $dataTable->render('block::list');
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {

        page_title()->setTitle(trans('block::block.create'));

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['are-you-sure']);

        return view('block::create');
    }

    /**
     * Insert new Block into database
     *
     * @param BlockRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(BlockRequest $request)
    {
        $block = $this->blockRepository->getModel();
        $block->fill($request->all());
        $block->user_id = Sentinel::getUser()->getUserId();
        $block->alias = $this->blockRepository->createSlug($request->input('alias'), null);

        $block = $this->blockRepository->createOrUpdate($block);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $block);

        if ($request->input('submit') === 'save') {
            return redirect()->route('block.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('block.edit', $block->id)->with('success_msg', trans('bases::notices.create_success_message'));
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
        page_title()->setTitle(trans('block::blocks.edit') . ' # ' . $id);

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['are-you-sure']);

        $block = $this->blockRepository->findById($id);
        return view('block::edit', compact('block'));
    }

    /**
     * @param $id
     * @param BlockRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, BlockRequest $request)
    {
        $block = $this->blockRepository->findById($id);
        $block->fill($request->all());
        $block->alias = $this->blockRepository->createSlug($request->input('alias'), $id);

        $this->blockRepository->createOrUpdate($block);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, BLOCK_MODULE_SCREEN_NAME, $request, $block);

        if ($request->input('submit') === 'save') {
            return redirect()->route('block.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('block.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }

    /**
     * @param $id
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete($id)
    {
        try {
            $block = $this->blockRepository->findById($id);
            $this->blockRepository->delete($block);

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
            $block = $this->blockRepository->findById($id);
            $this->blockRepository->delete($block);
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
            $block = $this->blockRepository->findById($id);
            $block->status = $request->input('status');
            $this->blockRepository->createOrUpdate($block);
        }

        return ['error' => false, 'status' => $request->input('status'), 'message' => trans('bases::notices.update_success_message')];
    }
}

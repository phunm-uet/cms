<?php

namespace Botble\Rss\Http\Controllers;

use Assets;
use Botble\Rss\Http\Requests\RssRequest;
use Botble\Rss\Repositories\Interfaces\RssInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\Driver\Exception\Exception;
use Botble\Rss\Http\DataTables\RssDataTable;

class RssController extends Controller
{
    /**
     * @var RssInterface
     */
    protected $rssRepository;

    /**
     * RssController constructor.
     * @param RssInterface $rssRepository
     * @author Sang Nguyen
     */
    public function __construct(RssInterface $rssRepository)
    {
        $this->rssRepository = $rssRepository;
    }

    /**
     * Display all rss
     * @param RssDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(RssDataTable $dataTable)
    {

        page_title()->setTitle(trans('rss::rss.list'));

        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $dataTable->render('rss::list');
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('rss::rss.create'));

        return view('rss::create');
    }

    /**
     * Insert new Rss into database
     *
     * @param RssRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(RssRequest $request)
    {
        $rss = $this->rssRepository->getModel();
        $rss->fill($request->all());

        $rss = $this->rssRepository->createOrUpdate($rss);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, RSS_MODULE_SCREEN_NAME, $request, $rss);

        if ($request->input('submit') === 'save') {
            return redirect()->route('rss.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('rss.edit', $rss->id)->with('success_msg', trans('bases::notices.create_success_message'));
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
        page_title()->setTitle(trans('rss::rss.edit') . ' #' . $id);

        $rss = $this->rssRepository->findById($id);
        return view('rss::edit', compact('rss'));
    }

    /**
     * @param $id
     * @param RssRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, RssRequest $request)
    {
        $rss = $this->rssRepository->findById($id);
        $rss->fill($request->all());

        $this->rssRepository->createOrUpdate($rss);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, RSS_MODULE_SCREEN_NAME, $request, $rss);

        if ($request->input('submit') === 'save') {
            return redirect()->route('rss.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('rss.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $rss = $this->rssRepository->findById($id);
            $this->rssRepository->delete($rss);

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
            $rss = $this->rssRepository->findById($id);
            $this->rssRepository->delete($rss);
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
            $rss = $this->rssRepository->findById($id);
            $rss->status = $request->input('status');
            $this->rssRepository->createOrUpdate($rss);
        }

        return ['error' => false, 'status' => $request->input('status'), 'message' => trans('bases::notices.update_success_message')];
    }
}

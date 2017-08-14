<?php

namespace Botble\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Blog\Http\Requests\PostRequest;
use Assets;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Http\DataTables\PostDataTable;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use MediaLibrary;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

class PostController extends Controller
{

    /**
     * @var PostInterface
     */
    protected $postRepository;

    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @param PostInterface $postRepository
     * @param TagInterface $tagRepository
     * @param CategoryInterface $categoryRepository
     * @author Sang Nguyen
     */
    public function __construct(
        PostInterface $postRepository,
        TagInterface $tagRepository,
        CategoryInterface $categoryRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param PostDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(PostDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::posts.list'));

        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $dataTable->render('blog::posts.list');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::posts.create'));

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $categories = get_categories_with_children();

        return view('blog::posts.create', compact('categories'));
    }

    /**
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(PostRequest $request)
    {
        $post = $this->postRepository->getModel();
        $post->fill($request->all());
        $post->user_id = Sentinel::getUser()->id;
        $post->featured = $request->input('featured', false);

        $post = $this->postRepository->createOrUpdate($post);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        if ($request->input('tag') != null && $request->input('tag') != '') {
            $tagInputs = explode(',', $request->input('tag'));
            foreach ($tagInputs as $tagName) {
                $tag = $this->tagRepository->getFirstBy(['name' => $tagName]);
                if ($tag === null) {
                    $tag = $this->tagRepository->getModel();
                    $tag->name = $tagName;
                    $tag->slug = $this->tagRepository->createSlug($tagName, null);
                    $tag->user_id = Sentinel::getUser()->getUserId();
                    $tag = $this->tagRepository->createOrUpdate($tag);

                    do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
                }
                $post->tags()->attach($tag->id);
            }
        }
        $categories = $request->input('categories');
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $post->categories()->attach($category);
            }
        }

        if ($request->input('submit') === 'save') {
            return redirect()->route('posts.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('posts.edit', $post->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {

        page_title()->setTitle(trans('blog::posts.edit') . ' #' . $id);

        MediaLibrary::registerMediaLibrary();

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $post = $this->postRepository->findById($id);

        $selected_categories = [];
        if ($post->categories != null) {
            $selected_categories = $post->categories->pluck('id')->all();
        }

        $tags = $post->tags->pluck('name')->all();
        $tags = implode(',', $tags);
        $categories = get_categories_with_children();

        return view('blog::posts.edit', compact('post', 'tags', 'categories', 'selected_categories'));
    }

    /**
     * @param $id
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, PostRequest $request)
    {
        $post = $this->postRepository->findById($id);
        $post->fill($request->all());
        $post->featured = $request->input('featured', false);

        $this->postRepository->createOrUpdate($post);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tags = $post->tags->pluck('name')->all();
        if (implode(',', $tags) !== $request->input('tag')) {
            $post->tags()->detach();
            $tagInputs = explode(',', $request->input('tag'));
            foreach ($tagInputs as $tagName) {
                $tag = $this->tagRepository->getFirstBy(['name' => $tagName]);
                if ($tag === null) {
                    $tag = $this->tagRepository->getModel();
                    $tag->name = $tagName;
                    $tag->slug = $this->tagRepository->createSlug($tagName, null);
                    $tag->user_id = Sentinel::getUser()->getUserId();
                    $tag = $this->tagRepository->createOrUpdate($tag);

                    do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
                }
                $post->tags()->attach($tag->id);
            }
        }

        if (!empty($request->input('categories'))) {
            $post->categories()->detach();
            foreach ($request->input('categories') as $category) {
                $post->categories()->attach($category);
            }
        }

        if ($request->input('submit') === 'save') {
            return redirect()->route('posts.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('posts.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $post = $this->postRepository->findById($id);
            $this->postRepository->delete($post);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

            return ['error' => false, 'message' => trans('bases::notices.deleted')];
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('bases::notices.cannot_delete')];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return ['error' => true, 'message' => trans('blog::posts.notices.no_select')];
        }

        foreach ($ids as $id) {
            $post = $this->postRepository->findById($id);
            $this->postRepository->delete($post);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);
        }

        return ['error' => false, 'message' => trans('bases::notices.deleted')];
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
            return ['error' => true, 'message' => trans('blog::posts.notices.no_select')];
        }

        foreach ($ids as $id) {
            $post = $this->postRepository->findById($id);
            $post->status = $request->input('status');
            $this->postRepository->createOrUpdate($post);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);
        }

        return ['error' => false, 'status' => $request->input('status'), 'message' => trans('blog::posts.notices.update_success_message')];
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->postRepository->createSlug($request->input('name'), $request->input('id'));
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getWidgetRecentPosts()
    {
        $limit = request()->input('paginate', 10);
        $posts = $this->postRepository->getModel()->orderBy('created_at', 'desc')->paginate($limit);
        return ['error' => false, 'data' => view('blog::posts.widgets.posts', compact('posts', 'limit'))->render()];
    }
}

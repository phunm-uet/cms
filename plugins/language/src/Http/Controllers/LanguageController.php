<?php

namespace Botble\Language\Http\Controllers;

use Assets;
use Botble\ACL\Models\UserMeta;
use Botble\Base\Supports\Language;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Botble\Language\Http\Requests\LanguageRequest;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Setting;

class LanguageController extends Controller
{
    /**
     * @var LanguageInterface
     */
    protected $languageRepository;

    /**
     * @var LanguageMetaInterface
     */
    protected $LanguageMetaRepository;


    /**
     * LanguageController constructor.
     * @param LanguageInterface $languageRepository
     * @param LanguageMetaInterface $LanguageMetaRepository
     * @author Sang Nguyen
     */
    public function __construct(LanguageInterface $languageRepository, LanguageMetaInterface $LanguageMetaRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->LanguageMetaRepository = $LanguageMetaRepository;
    }

    /**
     * Get list language page
     * @author Sang Nguyen
     */
    public function getList()
    {
        page_title()->setTitle(trans('language::language.name'));

        Assets::addJavascriptsDirectly('vendor/core/plugins/language/js/language.js');
        $languages = Language::getListLanguages();
        $flags = Language::getListLanguageFlags();
        $active_languages = $this->languageRepository->all();
        return view('language::index', compact('languages', 'flags', 'active_languages'));
    }

    /**
     * @param LanguageRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postStore(LanguageRequest $request)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['code' => $request->input('code')]);
            if ($language) {
                return ['error' => true, 'message' => 'This language is added already!'];
            }
            $language = $this->languageRepository->getModel();
            $language->fill($request->all());
            $this->languageRepository->createOrUpdate($language);

            do_action(BASE_ACTION_AFTER_CREATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return ['error' => false, 'message' => trans('bases::notices.create_success_message'), 'data' => view('language::partials.language-item', ['item' => $language])->render()];
        } catch (Exception $ex) {
            return ['error' => true, 'message' => $ex->getMessage()];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postEdit(Request $request)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['code' => $request->input('code')]);
            $language->fill($request->all());
            $this->languageRepository->createOrUpdate($language);

            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return ['error' => false, 'message' => trans('bases::notices.update_success_message'), 'data' => view('language::partials.language-item', ['item' => $language])->render()];
        } catch (Exception $ex) {
            return ['error' => true, 'message' => $ex->getMessage()];
        }

    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeItemLanguage(Request $request)
    {
        $content_id = $request->input('content_id') ? $request->input('content_id') : $request->input('created_from');
        $current_language = $this->LanguageMetaRepository->getFirstBy(['content_id' => $content_id, 'reference' => $request->input('reference')]);
        $others = $this->LanguageMetaRepository->getModel();
        if ($current_language) {
            $others = $others->where('code', '!=', $request->input('current_language'))
                ->where('origin', $current_language->origin);
        }
        $others = $others->select('content_id', 'code')
            ->get();
        $data = [];
        foreach ($others as $other) {
            $language = $this->languageRepository->getFirstBy(['code' => $other->code], ['flag', 'name', 'code']);
            if (!empty($language) && !empty($current_language) && $language->code != $current_language->code) {
                $data[$language->code]['flag'] = $language->flag;
                $data[$language->code]['name'] = $language->name;
                $data[$language->code]['content_id'] = $other->content_id;
            }
        }

        $languages = $this->languageRepository->all();
        foreach ($languages as $language) {
            if (!array_key_exists($language->code, $data) && $language->code != $request->input('current_language')) {
                $data[$language->code]['flag'] = $language->flag;
                $data[$language->code]['name'] = $language->name;
                $data[$language->code]['content_id'] = null;
            }
        }

        return ['error' => false, 'data' => $data];
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
            $language = $this->languageRepository->findById($id);
            $this->languageRepository->delete($language);
            $delete_default = false;
            if ($language->is_default) {
                $default = $this->languageRepository->getFirstBy(['is_default' => 0]);
                $default->is_default = 1;
                $this->languageRepository->createOrUpdate($default);
                $delete_default = $default->id;
            }

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return ['error' => false, 'message' => trans('bases::notices.deleted'), 'data' => $delete_default];
        } catch (Exception $e) {
            return ['error' => true, 'message' => trans('bases::notices.cannot_delete')];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getSetDefault(Request $request)
    {
        $id = $request->input('id');

        $this->languageRepository->update(['is_default' => 1], ['is_default' => 0]);
        $language = $this->languageRepository->findById($id);
        $language->is_default = 1;
        $this->languageRepository->createOrUpdate($language);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

        return ['error' => false, 'message' => trans('bases::notices.update_success_message')];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getLanguage(Request $request)
    {
        $language = $this->languageRepository->findById($request->input('id'));
        return ['error' => false, 'data' => $language];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEditSettings(Request $request)
    {
        Setting::set('language_hide_default', $request->input('language_hide_default', false));
        Setting::set('language_display', $request->input('language_display'));
        Setting::set('language_switcher_display', $request->input('language_switcher_display'));
        Setting::set('language_hide_languages', json_encode($request->input('language_hide_languages', [])));
        Setting::save();
        return redirect()->back()->with('success_msg', trans('bases::notices.update_success_message'));
    }

    /**
     * @param $code
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getChangeDataLanguage($code)
    {
        UserMeta::setMeta('languages_current_data_language', $code);
        return redirect()->back();
    }
}

<?php

namespace Botble\Language\Supports;

use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Language;
use Request;
use Route;

class Action
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        if (!empty(Language::getDefaultLanguage())) {
            add_action(BASE_ACTION_META_BOXES, [$this, 'addLanguageBox'], 50, 3);
            add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveLanguageData'], 55, 3);
            add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveLanguageData'], 55, 3);
            add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteLanguageMeta'], 55, 2);
            add_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, [$this, 'addCurrentLanguageEditingAlert'], 55, 3);
            add_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, [$this, 'addCurrentLanguageEditingAlert'], 55, 3);
        }
    }

    /**
     * Trigger __construct function
     *
     * @return Action
     */
    public static function initialize()
    {
        return new self();
    }
    
    /**
     * @param $screen
     * @author Sang Nguyen
     */
    public function addLanguageBox($screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            add_meta_box('language_wrap', trans('language::language.name'), [$this, 'languageMetaField'], $screen, 'top', 'default');
        }
    }

    /**
     * @author Sang Nguyen
     */
    public function languageMetaField()
    {
        $languages = Language::getActiveLanguage();
        if ($languages->isEmpty()) {
            return null;
        }

        $related = [];
        $value = null;
        $args = func_get_args();

        $meta = null;

        $current_route = explode('.', Route::currentRouteName());
        $route = [
            'create' => Route::currentRouteName(),
            'edit' => $current_route[0] . '.' . 'edit'
        ];

        if (!empty($args[0])) {
            $meta = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => $args[0]->id, 'reference' => $args[1]]);
            if (!empty($meta)) {
                $value = $meta->code;
            }

            $current_route = $current_route = explode('.', Route::currentRouteName());

            if (count($current_route) > 2) {
                $route = $current_route[0] . '.' . $current_route[1];
            } else {
                $route = $current_route[0];
            }

            $route = [
                'create' => $route . '.' . 'create',
                'edit' => Route::currentRouteName()
            ];
        } elseif (Request::get('from')) {
            $meta = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => Request::get('from'), 'reference' => $args[1]]);
            $value = Request::get('lang');
        }
        if ($meta) {
            $related = Language::getRelatedLanguageItem($meta->content_id, $meta->origin);
        }
        $current_language = self::checkCurrentLanguage($languages, $value);

        $route = apply_filters(LANGUAGE_FILTER_ROUTE_ACTION, $route);
        return view('language::language-box', compact('args', 'languages', 'current_language', 'related', 'route'))->render();
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $object
     * @return mixed
     * @author Sang Nguyen
     */
    public function saveLanguageData($screen, $request, $object)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            if ($request->input('language')) {
                $unique_key = null;
                $meta = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => $object->id, 'reference' => $screen]);
                if (!$meta && !$request->input('from')) {
                    $unique_key = md5($object->id . $screen . time());
                } elseif ($request->input('from')) {
                    $unique_key = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => $request->input('from'), 'reference' => $screen])->origin;
                }

                if (!$meta) {
                    $meta = app(LanguageMetaInterface::class)->getModel();
                    $meta->content_id = $object->id;
                    $meta->reference = $screen;
                    $meta->origin = $unique_key;
                }

                $meta->code = $request->input('language');
                app(LanguageMetaInterface::class)->createOrUpdate($meta);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $value
     * @param $languages
     * @return mixed
     * @author Sang Nguyen
     */
    public function checkCurrentLanguage($languages, $value)
    {
        $current_language = null;
        foreach ($languages as $language) {
            if ($value) {
                if ($language->code == $value) {
                    $current_language = $language;
                }
            } else {
                if (Request::get('lang')) {
                    if ($language->code == Request::get('lang')) {
                        $current_language = $language;
                    }
                } else {
                    if ($language->is_default) {
                        $current_language = $language;
                    }
                }
            }
        }

        if (empty($current_language)) {
            foreach ($languages as $language) {
                if ($language->is_default) {
                    $current_language = $language;
                }
            }
        }

        return $current_language;
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteLanguageMeta($screen, $content)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            app(LanguageMetaInterface::class)->deleteBy(['content_id' => $content->id, 'reference' => $screen]);
        }
        return true;
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @return void
     * @author Sang Nguyen
     * @since 2.1
     */
    public function addCurrentLanguageEditingAlert($screen, $request, $data = null)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $code = null;
            $language = null;
            if ($request->has('lang')) {
                $code = $request->get('lang');
            } else {
                if (!empty($data)) {
                    $meta = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => $data->id, 'reference' => $screen]);
                    if (!empty($meta)) {
                        $code = $meta->code;
                    }
                }
            }

            if (empty($code)) {
                $code = Language::getDefaultLocaleCode();
            }

            if (!empty($code)) {
                $language = app(LanguageInterface::class)->getFirstBy(['code' => $code]);
                if (!empty($language)) {
                    $language = $language->name;
                }
            }
            echo view('language::partials.notification', compact('language'))->render();
        }
        echo null;
    }
}
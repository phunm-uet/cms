<?php

namespace Botble\Language\Supports;

use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Language;
use MenuLeftHand;
use Route;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(BASE_FILTER_GET_LIST_DATA, [$this, 'addLanguageColumn'], 50, 2);
        add_filter(BASE_FILTER_TABLE_HEADINGS, [$this, 'addLanguageTableHeading'], 50, 2);
        add_filter(LANGUAGE_FILTER_SWITCHER, [$this, 'languageSwitcher']);
        add_filter(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, [$this, 'checkItemLanguageBeforeShow'], 50, 3);
        add_filter(BASE_FILTER_BEFORE_GET_BY_SLUG, [$this, 'getRelatedDataForOtherLanguage'], 50, 3);
        add_filter(BASE_FILTER_GROUP_PUBLIC_ROUTE, [$this, 'addLanguageMiddlewareToPublicRoute'], 958, 1);
        add_filter(BASE_FILTER_DATATABLES_BUTTONS, [$this, 'addLanguageSwitcherToTable'], 247, 2);
        add_filter(BASE_FILTER_DATATABLES_QUERY, [$this, 'getDataByCurrentLanguage'], 157, 3);
    }

    /**
     * Trigger __construct function
     *
     * @return Filter
     */
    public static function initialize()
    {
        return new self();
    }
    
    /**
     * @param $headings
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function addLanguageTableHeading($headings, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $languages = Language::getActiveLanguage();
            $heading = '';
            foreach ($languages as $language) {
                $heading .= language_flag($language->flag, $language->name);
            }
            return array_merge($headings, ['language' => ['name' => 'id', 'title' => $heading, 'className' => 'text-center language-header']]);
        }
        return $headings;
    }

    /**
     * @param $data
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function addLanguageColumn($data, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            return $data->addColumn('language', function ($item) use ($screen) {
                $current_language = app(LanguageMetaInterface::class)->getFirstBy(['content_id' => $item->id, 'reference' => $screen]);
                $related_languages = [];
                if ($current_language) {
                    $related_languages = Language::getRelatedLanguageItem($current_language->content_id, $current_language->origin);
                    $current_language = $current_language->code;
                }
                $languages = Language::getActiveLanguage();
                $data = '';
                $current_route = explode('.', Route::currentRouteName());

                if (count($current_route) > 2) {
                    $route = $current_route[0] . '.' . $current_route[1];
                } else {
                    $route = $current_route[0];
                }

                foreach ($languages as $language) {
                    if ($language->code == $current_language) {
                        $data .= view('language::partials.status.active', compact('route', 'item'))->render();
                    } else {
                        $added = false;
                        if (!empty($related_languages)) {
                            foreach ($related_languages as $key => $related_language) {
                                if ($key == $language->code) {
                                    $data .= view('language::partials.status.edit', compact('route', 'related_language'))->render();
                                    $added = true;
                                }
                            }
                        }
                        if (!$added) {
                            $data .= view('language::partials.status.plus', compact('route', 'item', 'language'))->render();
                        }
                    }
                }

                return view('language::partials.language-column', compact('data'))->render();
            });
        }
        return $data;
    }

    /**
     * @param array $options
     * @return string
     * @author Sang Nguyen
     */
    public function languageSwitcher($options = [])
    {
        $languages = Language::getActiveLanguage();
        return view('language::partials.switcher', compact('languages', 'options'))->render();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $data
     * @param $screen
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     * @author Sang Nguyen
     */
    public function checkItemLanguageBeforeShow($data, $model, $screen)
    {
        $table = $model->getTable();
        return $data->join('language_meta', 'language_meta.content_id', $table . '.id')
                ->where('language_meta.reference', '=', $screen)
                ->where('language_meta.code', '=', Language::getCurrentLocaleCode());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $data
     * @param $screen
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @return string
     * @author Sang Nguyen
     */
    public function getRelatedDataForOtherLanguage($data, $model, $screen)
    {
        if (!empty($data)) {
            $current = app(LanguageMetaInterface::class)->getFirstBy(['reference' => $screen, 'content_id' => $data->id]);
            if ($current) {
                if ($current->code != Language::getCurrentLocaleCode()) {
                    $meta = app(LanguageMetaInterface::class)->getModel()
                        ->where('origin', '=', $current->origin)
                        ->where('content_id', '!=', $data->id)
                        ->where('code', '=', Language::getCurrentLocaleCode())
                        ->first();
                    if ($meta) {
                        $data = $model->where('id', '=', $meta->content_id)->first();
                        if ($data) {
                            return $data;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param $data
     * @return array
     * @author Sang Nguyen
     */
    public function addLanguageMiddlewareToPublicRoute($data)
    {
        return array_merge_recursive($data, [
            'prefix' => Language::setLocale(),
            'middleware' => [
                'localeSessionRedirect',
                'localizationRedirect',
            ],
        ]);
    }

    /**
     * @param $buttons
     * @param $screen
     * @return array
     * @author Sang Nguyen
     * @since 2.2
     */
    public function addLanguageSwitcherToTable($buttons, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $active_languages = get_active_languages();
            $language_buttons = [];
            $current_language = Language::getCurrentDataLanguage();
            foreach ($active_languages as $item) {
                $language_buttons[] = [
                    'className' => 'change-data-language-item ' . ($item->code == $current_language ? 'active' : ''),
                    'text' => '<span data-href="' . route('languages.change.data.language', $item->code) . '">' . $item->name . '</span>',
                ];
            }

            $language_buttons[] = [
                'className' => 'change-data-language-item ' . ('all' == $current_language ? 'active' : ''),
                'text' => '<span data-href="' . route('languages.change.data.language', 'all') . '">' . trans('language::language.show_all') . '</span>',
            ];

            $flag = app(LanguageInterface::class)->getFirstBy(['code' => $current_language]);
            if (!empty($flag)) {
                $flag = language_flag($flag->flag, $flag->name);
            } else {
                $flag = '<i class="fa fa-flag"></i>';
            }

            $language = [
                'language' => [
                    'extend' => 'collection',
                    'text' => $flag . ' <span> ' . trans('language::language.change_language') . ' <span class="caret"></span></span>',
                    'buttons' => $language_buttons,
                ],
            ];
            $buttons = array_merge($buttons, $language);
        }

        return $buttons;
    }

    /**
     * @param $query
     * @param $model
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getDataByCurrentLanguage($query, $model, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage()) && Language::getCurrentDataLanguage() != 'all') {
            $table = $model->getTable();
            $query = $query->join('language_meta', 'language_meta.content_id', $table . '.id')
                ->where('language_meta.reference', '=', $screen)
                ->where('language_meta.code', '=', Language::getCurrentDataLanguage());
        }
        return $query;
    }
}
<?php

namespace Botble\Note\Supports;

use Botble\Note\Repositories\Interfaces\NoteInterface;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(BASE_FILTER_REGISTER_CONTENT_TABS, [$this, 'addNoteTab'], 50, 2);
        add_filter(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, [$this, 'addNoteContent'], 50, 3);
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
     * @author Sang Nguyen
     * @since 2.0
     */
    public static function screenUsingNote()
    {
        return apply_filters(NOTE_FILTER_MODEL_USING_NOTE, [POST_MODULE_SCREEN_NAME, PAGE_MODULE_SCREEN_NAME]);
    }

    /**
     * @param $tabs
     * @param $screen
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     */
    public static function addNoteTab($tabs, $screen)
    {
        if (in_array($screen, Filter::screenUsingNote())) {
            return $tabs . view('note::tab')->render();
        }
        return $tabs;
    }

    /**
     * @param $tabs
     * @param $screen
     * @param $data
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     */
    public static function addNoteContent($tabs, $screen, $data = null)
    {
        if (in_array($screen, Filter::screenUsingNote())) {
            $notes = [];
            if (!empty($data)) {
                $notes = app(NoteInterface::class)->allBy(['reference_id' => $data->id, 'reference_type' => $screen]);
            }
            return $tabs . view('note::content', compact('notes'))->render();
        }
        return $tabs;
    }
}
<?php

namespace Botble\Note\Supports;

use Botble\Note\Repositories\Interfaces\NoteInterface;
use Sentinel;

class Action
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveNote'], 50, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveNote'], 50, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteNote'], 50, 2);
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
     * @param $request
     * @param $object
     * @author Sang Nguyen
     */
    public static function saveNote($screen, $request, $object)
    {
        if (in_array($screen, Filter::screenUsingNote()) && $request->input('note')) {
            $note = app(NoteInterface::class)->getModel();
            $note->note = $request->input('note');
            $note->user_id = Sentinel::getUser()->id;
            $note->created_by = Sentinel::getUser()->id;
            $note->reference_type = $screen;
            $note->reference_id = $object->id;
            app(NoteInterface::class)->createOrUpdate($note);
        }
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public static function deleteNote($screen, $content)
    {
        $note = app(NoteInterface::class)->getFirstBy(['reference_id' => $content->id, 'reference_type' => $screen]);
        if (!empty($note)) {
            $note->delete();
        }
        return true;
    }
}
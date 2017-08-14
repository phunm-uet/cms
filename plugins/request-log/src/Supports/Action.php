<?php
namespace Botble\RequestLog\Supports;

use Botble\RequestLog\Events\RequestHandlerEvent;

class Action
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_action(BASE_ACTION_SITE_ERROR, [$this, 'handleSiteError'], 125, 1);
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
     * Fire event log
     *
     * @param $code
     * @author Sang Nguyen
     */
    public function handleSiteError($code)
    {
        event(new RequestHandlerEvent($code));
    }
}
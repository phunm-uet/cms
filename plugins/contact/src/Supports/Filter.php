<?php

namespace Botble\Contact\Supports;

use Botble\Contact\Repositories\Interfaces\ContactInterface;
use MenuLeftHand;
use Sentinel;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnReadCount'], 120, 2);
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
     * @param string $options
     * @return string
     * @author Sang Nguyen
     */
    public function registerTopHeaderNotification($options)
    {
        if (Sentinel::getUser()->hasPermission('contacts.edit')) {
            $contacts = app(ContactInterface::class)->getUnread();

            return $options . view('contact::partials.notification', compact('contacts'))->render();
        }
        return null;
    }

    /**
     * @param $number
     * @param $route
     * @return string
     * @author Sang Nguyen
     */
    public function getUnreadCount($number, $route)
    {
        if ($route == 'contacts.list') {
            $unread = app(ContactInterface::class)->countUnread();
            if ($unread > 0) {
                return '<span class="badge badge-success">' . $unread . '</span>';
            }
        }
        return $number;
    }
}
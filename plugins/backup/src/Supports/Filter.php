<?php

namespace Botble\Backup\Supports;

class Filter
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_filter(BASE_FILTER_REGISTER_PLATFORM_ADMIN_OPTIONS, [$this, 'registerAdminOption'], 5, 1);
        if (app()->environment() == 'demo') {
            add_filter(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, [$this, 'registerAdminAlert'], 5, 1);
        }
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
    public function registerAdminOption($options)
    {
        return $options . view('backup::partials.admin-option')->render();
    }

    /**
     * @param string $alert
     * @return string
     * @author Sang Nguyen
     */
    public function registerAdminAlert($alert)
    {
        return $alert . view('backup::partials.admin-alert')->render();
    }
}
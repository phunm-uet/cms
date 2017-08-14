<?php

namespace Botble\Base\Supports;

use Breadcrumbs;
use Route;

class AdminBreadcrumb
{
    /**
     * @return string
     * @author Sang Nguyen
     */
    public function render()
    {
        return Breadcrumbs::render('pageTitle', page_title()->getTitle(false), Route::currentRouteName());
    }
}
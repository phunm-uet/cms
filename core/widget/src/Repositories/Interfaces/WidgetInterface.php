<?php

namespace Botble\Widget\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface WidgetInterface extends RepositoryInterface
{
    /**
     * Get all theme widgets
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByTheme();
}

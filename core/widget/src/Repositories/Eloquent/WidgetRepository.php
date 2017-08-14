<?php

namespace Botble\Widget\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetRepository extends RepositoriesAbstract implements WidgetInterface
{
    /**
     * Get all theme widgets
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByTheme()
    {
        return $this->model->where('theme', '=', setting('theme'))->get();
    }
}

<?php
namespace Botble\Dashboard\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Sentinel;

class DashboardWidgetSettingRepository extends RepositoriesAbstract implements DashboardWidgetSettingInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getListWidget()
    {
        return $this->model->select('id', 'order', 'settings', 'widget_id')
            ->with('widget')
            ->orderBy('order')
            ->where('user_id', '=', Sentinel::getUser()->id)
            ->get();
    }
}
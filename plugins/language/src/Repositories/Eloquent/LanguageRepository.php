<?php
namespace Botble\Language\Repositories\Eloquent;

use Botble\Base\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Language\Repositories\Interfaces\LanguageInterface;

class LanguageRepository extends RepositoriesAbstract implements LanguageInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getActiveLanguage()
    {
        return $this->model->orderBy('order', 'asc')->get();
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getDefaultLanguage()
    {
        return $this->model->where('is_default', 1)->first();
    }
}

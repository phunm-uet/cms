<?php

namespace Botble\Language\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface LanguageInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getActiveLanguage();

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getDefaultLanguage();
}

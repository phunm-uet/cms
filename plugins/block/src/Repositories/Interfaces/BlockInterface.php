<?php

namespace Botble\Block\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface BlockInterface extends RepositoryInterface
{
    /**
     * @param $name
     * @param $id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $id);
}

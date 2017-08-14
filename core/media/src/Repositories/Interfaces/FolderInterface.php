<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface FolderInterface extends RepositoryInterface
{

    /**
     * @param $folderId
     * @author Sang Nguyen
     */
    public function getFolderByParentId($folderId);

    /**
     * @param $name
     * @author Sang Nguyen
     */
    public function createSlug($name);

    /**
     * @param $name
     * @param $parent
     * @author Sang Nguyen
     */
    public function createName($name, $parent);
}

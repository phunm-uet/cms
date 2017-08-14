<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface FileInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceUsed();

    /**
     * @return mixed
     */
    public function getSpaceLeft();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getQuota();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPercentageUsed();

    /**
     * @param $name
     * @param $folder
     * @author Sang Nguyen
     */
    public function createName($name, $folder);

    /**
     * @param $name
     * @param $extension
     * @param $folder
     * @author Sang Nguyen
     */
    public function createSlug($name, $extension, $folder);

    /**
     * @param $folder_id
     * @param array $type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFilesByFolderId($folder_id, $type = []);
}

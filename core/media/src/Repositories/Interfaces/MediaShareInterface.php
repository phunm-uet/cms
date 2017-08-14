<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface MediaShareInterface extends RepositoryInterface
{
    /**
     * @param $share_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getShareWithUser($share_id);

    /**
     * @param $shareId
     * @param $shareType
     * @author Sang Nguyen
     */
    public function getFileShares($shareId, $shareType);

    /**
     * @param $file
     * @author Sang Nguyen
     */
    public function unshareFile($file);

    /**
     * @param $folder
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder = 0);

    /**
     * @param $folder
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder = 0);

    /**
     * @param $folder
     * @author Sang Nguyen
     */
    public function getMyShareDirectory($folder);
}

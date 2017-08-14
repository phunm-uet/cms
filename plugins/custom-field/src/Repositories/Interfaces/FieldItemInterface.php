<?php

namespace Botble\CustomField\Repositories\Interfaces;

use Botble\Base\Repositories\Interfaces\RepositoryInterface;

interface FieldItemInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @param int $fieldGroupId
     * @param int $parentId
     * @param string $slug
     * @return string
     */
    public function makeUniqueSlug($id, $fieldGroupId, $parentId, $slug);
}

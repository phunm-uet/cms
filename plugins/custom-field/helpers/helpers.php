<?php

use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;

if (!function_exists('get_field')) {
    /**
     * @param $id
     * @param $screen
     * @param null $slug
     * @param null $default
     * @return null
     */
    function get_field($id, $screen, $slug = null, $default = null)
    {
        $customFieldRepository = app(CustomFieldInterface::class);

        if ($slug === null || !trim($slug)) {
            return $customFieldRepository->allBy([
                'use_for' => $screen,
                'use_for_id' => $id
            ]);
        }

        $field = $customFieldRepository->getFirstBy([
            'use_for' => $screen,
            'use_for_id' => $id,
            'slug' => $slug
        ]);

        if (!$field) {
            return $default;
        }

        return $field->resolved_value;
    }
}

if (!function_exists('has_field')) {
    /**
     * @param $object
     * @param null $alias
     * @return bool
     */
    function has_field($object, $alias = null)
    {
        if (!get_field($object, $alias)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('get_sub_field')) {
    /**
     * @param array $parentField
     * @param $alias
     * @param null $default
     * @return mixed
     */
    function get_sub_field(array $parentField, $alias, $default = null)
    {
        foreach ($parentField as $field) {
            if (array_get($field, 'slug') === $alias) {
                return array_get($field, 'value', $default);
            }
        }
        return $default;
    }
}

if (!function_exists('has_sub_field')) {
    /**
     * @param array $parentField
     * @param $alias
     * @return bool
     */
    function has_sub_field(array $parentField, $alias)
    {
        if (!get_sub_field($parentField, $alias)) {
            return false;
        }
        return true;
    }
}

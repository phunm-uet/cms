<?php

namespace Botble\CustomField\Supports;

use Assets;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Blog\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Sentinel;
use Botble\CustomField\Repositories\Eloquent\CustomFieldRepository;
use Botble\CustomField\Repositories\Eloquent\FieldGroupRepository;
use Botble\CustomField\Repositories\Eloquent\FieldItemRepository;
use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use CustomField;

class Action
{
    /**
     * @var CustomFieldRepository
     */
    protected $customFieldRepository;

    /**
     * @var FieldGroupRepository
     */
    protected $fieldGroupRepository;

    /**
     * @var FieldItemRepository
     */
    protected $fieldItemRepository;

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'showCustomField'], 125, 3);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveCustomFields'], 11, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveCustomFields'], 11, 3);

        $this->customFieldRepository = app(CustomFieldInterface::class);
        $this->fieldGroupRepository = app(FieldGroupInterface::class);
        $this->fieldItemRepository = app(FieldItemInterface::class);
    }

    /**
     * Trigger __construct function
     *
     * @return Action
     */
    public static function initialize()
    {
        return new self();
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $object
     */
    public function saveCustomFields($screen, $request, $object)
    {
        $data = $this->parseRawData($request->get('custom_fields', []));
        foreach ($data as $row) {
            $this->saveCustomField($screen, $object->id, $row);
        }
    }

    /**
     * @param $jsonString
     * @return array
     */
    protected function parseRawData($jsonString)
    {
        try {
            $fieldGroups = json_decode($jsonString);
        } catch (Exception $exception) {
            return [];
        }

        $result = [];
        foreach ($fieldGroups as $fieldGroup) {
            foreach ($fieldGroup->items as $item) {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Save custom field
     * @param int $screen
     * @param $object_id
     * @param $data
     * @internal param $id
     */
    public function saveCustomField($screen, $object_id, $data)
    {

        $currentMeta = $this->customFieldRepository->getFirstBy([
            'field_item_id' => $data->id,
            'slug' => $data->slug,
            'use_for' => $screen,
        ]);

        $value = $this->parseFieldValue($data);

        if (!is_string($value)) {
            $value = json_encode($value);
        }

        if ($currentMeta) {
            $currentMeta->type = $data->type;
            $currentMeta->value = $value;
            $this->customFieldRepository->createOrUpdate($currentMeta);
        } else {
            $meta = $this->customFieldRepository->getModel();
            $meta->use_for = $screen;
            $meta->use_for_id = $object_id;
            $meta->field_item_id = $data->id;
            $meta->slug = $data->slug;
            $meta->type = $data->type;
            $meta->value = $value;

            $this->customFieldRepository->createOrUpdate($meta);
        }
    }

    /**
     * Get field value
     * @param $field
     * @return array|string
     */
    private function parseFieldValue($field)
    {
        switch ($field->type) {
            case 'repeater':
                if (!isset($field->value)) {
                    return [];
                }

                $value = [];
                foreach ($field->value as $row) {
                    $groups = [];
                    foreach ($row as $item) {
                        $groups[] = [
                            'field_item_id' => $item->id,
                            'type' => $item->type,
                            'slug' => $item->slug,
                            'value' => $this->parseFieldValue($item),
                        ];
                    }
                    $value[] = $groups;
                }
                return $value;
                break;
            case 'checkbox':
                $value = isset($field->value) ? (array)$field->value : [];
                break;
            default:
                $value = isset($field->value) ? $field->value : '';
                break;
        }
        return $value;
    }

    /**
     * @param $screen
     * @param $priority
     * @param $object
     * @author Sang Nguyen
     */
    public function showCustomField($screen, $priority, $object = null)
    {
        if ($priority == 'advanced') {

            Assets::addJavascriptsDirectly(config('cms.editor.ckeditor.js'));
            Assets::addJavascript(['jquery-ui']);
            Assets::addStylesheetsDirectly('vendor/core/plugins/custom-field/css/custom-field.css');
            Assets::addJavascriptsDirectly('vendor/core/plugins/custom-field/js/use-custom-fields.js');

            /**
             * Every models will have these rules by default
             */
            CustomField::addRules([
                'logged_in_user' => Sentinel::getUser()->getUserId(),
                'logged_in_user_has_role' => app(RoleInterface::class)->pluck('id')
            ]);

            switch ($screen) {
                case PAGE_MODULE_SCREEN_NAME:
                    CustomField::addRules([
                        'page_template' => isset($object->template) ? $object->template : '',
                        'page' => isset($object->id) ? $object->id : '',
                        'model_name' => PAGE_MODULE_SCREEN_NAME,
                    ]);
                    break;
                case POST_MODULE_SCREEN_NAME:
                    /**
                     * @var Post $object
                     */
                    $relatedCategories = !empty($object) ? $object->categories() : [];
                    $relatedCategoriesIds = !empty($object) ? $relatedCategories->allRelatedIds()->toArray() : [];
                    CustomField::addRules([
                        'model_name' => POST_MODULE_SCREEN_NAME,
                        'blog.post_with_related_category' => $relatedCategoriesIds,
                    ]);
                    break;
                case CATEGORY_MODULE_SCREEN_NAME:
                    CustomField::addRules([
                        'blog.category' => isset($object->id) ? $object->id : '',
                        'model_name' => CATEGORY_MODULE_SCREEN_NAME,
                    ]);
                    break;
            }

            $id = $object ? $object->id : 0;

            $customFieldBoxes = CustomField::exportCustomFieldsData($screen, $id);

            if (!$customFieldBoxes) {
                return;
            }

            echo view('custom-field::custom-fields-boxes-renderer', ['customFieldBoxes' => json_encode($customFieldBoxes)]);
        }
    }
}

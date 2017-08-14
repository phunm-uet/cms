<?php

use Botble\Widget\AbstractWidget;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;

class TagsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $widgetDirectory = 'tags';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Tags'),
            'description' => __('Popular tags'),
            'number_display' => 5,
        ]);
    }
}
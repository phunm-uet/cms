<?php

namespace Botble\Widget\Widgets;

use Botble\Widget\AbstractWidget;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;

class Text extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $frontendTemplate = 'widgets::widgets.text.frontend';

    protected $backendTemplate = 'widgets::widgets.text.backend';

    protected $widgetDirectory;

    protected $is_core = true;

    /**
     * Text constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => trans('widgets::global.widget_text'),
            'description' => trans('widgets::global.widget_text_description'),
            'content' => null,
        ]);
    }
}
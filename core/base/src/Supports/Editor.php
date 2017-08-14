<?php

namespace Botble\Base\Supports;

use Assets;

class Editor
{
    public function __construct()
    {
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, [$this, 'registerAssets'], 12, 1);
    }

    public function registerAssets()
    {
        Assets::addJavascriptsDirectly(config('cms.editor.' . config('cms.editor.primary') . '.js'));
        Assets::addAppModule(['editor']);
    }

    /**
     * @param $name
     * @param null $value
     * @return string
     */
    public function render($name, $value = null)
    {
        return view('bases::elements.forms.editor', compact('name', 'value'))->render();
    }
}
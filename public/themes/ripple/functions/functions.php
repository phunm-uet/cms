<?php

register_page_template([
    'default' => __('default')
]);
ThemeOption::setSection([
    'title' => __('General'),
    'desc' => __('General settings'),
    'id' => 'opt-text-subsection-general',
    'subsection' => true,
    'icon' => 'fa fa-home',
]);

ThemeOption::setSection([
    'title' => __('Logo'),
    'desc' => __('Change logo'),
    'id' => 'opt-text-subsection-logo',
    'subsection' => true,
    'icon' => 'fa fa-image',
    'fields' => [
        [
            'id' => 'logo',
            'type' => 'mediaImage',
            'label' => __('Logo'),
            'attributes' => [
                'name' => 'logo',
                'value' => null,
            ],
        ],
    ],
]);

ThemeOption::setField([
    'id' => 'copyright',
    'section_id' => 'opt-text-subsection-general',
    'type' => 'text',
    'label' => __('Copyright'),
    'attributes' => [
        'name' => 'copyright',
        'value' => '© 2016 Botble Technologies. All right reserved. Designed by Nghia Minh',
        'options' => [
            'class' => 'form-control',
            'placeholder' => __('Change copyright'),
            'data-counter' => 120,
        ]
    ],
    'helper' => __('Copyright on footer of site'),
]);

ThemeOption::setArgs(['debug' => true]);
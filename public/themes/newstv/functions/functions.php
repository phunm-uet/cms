<?php

register_page_template([
    'default' => __('default')
]);

register_page_template([
    'no-sidebar' => __('No Sidebar')
]);

add_shortcode('google-map', 'add_google_map_shortcode');

function add_google_map_shortcode ($shortcode) {
    return Theme::partial('google-map', ['address' => $shortcode->content]);
}

add_shortcode('youtube-video', 'add_youtube_video_shortcode');

function add_youtube_video_shortcode ($shortcode) {
    return Theme::partial('video', ['url' => $shortcode->content]);
}

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
        'value' => '© 2017 Botble Technologies. All right reserved. Designed by Nghia Minh',
        'options' => [
            'class' => 'form-control',
            'placeholder' => __('Change copyright'),
            'data-counter' => 120,
        ]
    ],
    'helper' => __('Copyright on footer of site'),
]);

ThemeOption::setField([
    'id' => 'theme-color',
    'section_id' => 'opt-text-subsection-general',
    'type' => 'select',
    'label' => __('Theme color'),
    'attributes' => [
        'name' => 'theme_color',
        'attributes' => ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'],
        'value' => ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'],
        'options' => [
            'class' => 'form-control',
        ]
    ],
    'helper' => __('Primary theme color'),
]);

ThemeOption::setField([
    'id' => 'top-banner',
    'section_id' => 'opt-text-subsection-general',
    'type' => 'text',
    'label' => __('Top banner'),
    'attributes' => [
        'name' => 'top_banner',
        'value' => '/themes/newstv/assets/images/banner.png',
        'options' => [
            'class' => 'form-control',
            'placeholder' => __('Input image URL...'),
        ]
    ],
]);

ThemeOption::setArgs(['debug' => true]);
<?php

return [
    'cache_store_keys' => storage_path() . '/cache_keys.json',
    'admin_dir' => env('ADMIN_DIR', 'admin'),
    'version' => env('VERSION', '2.2'),
    'plugin-default-img' => '/vendor/core/images/plugin.png',
    'plugin_path' => base_path() . '/plugins',
    'media-default-img' => '/vendor/core/images/default-image.jpg',
    'upload' => [
      'base_dir' => public_path('uploads'),
    ],
    'default-theme' => env('DEFAULT_THEME', 'default'),
    'base_name' => env('BASE_NAME', 'Botble Technologies'),
    'logo' => '/vendor/core/images/logo_white.png',
    'favicon' => '/vendor/core/images/favicon.png',
    'editor' => [
        'ckeditor' => [
            'js' => [
                '/vendor/core/packages/ckeditor/ckeditor.js',
            ],
        ],
        'tinymce' => [
          'js' => [
              '/vendor/core/packages/tinymce/tinymce.min.js',
          ],
        ],
        'primary' => env('PRIMARY_EDITOR', 'ckeditor'),
    ],
    'email_template' => 'bases::system.email',
    'slug' => [
        'pattern' => '--slug--',
    ]
];

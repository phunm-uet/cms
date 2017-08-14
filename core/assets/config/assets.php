<?php
/**
 * Created by Sublime Text 3.
 * User: Sang Nguyen
 * Date: 22/07/2015
 * Time: 8:11 PM
 */

return [
    'offline' => env('OFFLINE', true),
    'javascript' => [
        'jquery',
        'csrf',
        'jquery-migrate',
        'modernizr',
        'bootstrap',
        'uniform',
        'select2',
        'script',
        'utility',
        'cookie',
        'toastr',
        'pace',
        'custom-scrollbar',
        'stickytableheaders',
        'tabdrop',
        'core',
        'jquery-waypoints',
    ],
    'stylesheets' => [
        'bootstrap',
        'fontawesome',
        'simple-line-icons',
        'select2',
        'pace',
        'toastr',
        'custom-scrollbar',
        'tabdrop',
        'datepicker',
    ],
    'resources' => [
        'javascript' => [
            'core' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => 'vendor/core/js/core.js',
                ],
            ],
            'jquery' => [
                'use_cdn' => false,
                'fallback' => 'jQuery',
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/jquery/jquery.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.2/jquery.min.js',
                ],
            ],
            'jquery-migrate' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-migrate.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js',
                ],
            ],
            'modernizr' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/modernizr/modernizr.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js',
                ],
            ],
            'kendo' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/kendo/kendo.min.js',
                ],
            ],
            'blockui' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery.blockui.min.js',
                ],
            ],
            'cropper' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/cropper/dist/cropper.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/cropper/0.7.9/cropper.min.js',
                ],
            ],
            'videojs' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'include_style' => true,
                'src' => [
                    'local' => [
                        '/vendor/core/packages/videojs/video.min.js',
                        '/vendor/core/packages/videojs/Youtube.js',
                    ],
                    'cdn' => [
                        '//vjs.zencdn.net/5.8/video.min.js',
                        '/vendor/core/packages/videojs/Youtube.js',
                    ],
                ],
            ],
            'datetimepicker' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-datetimepicker.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js',
                ],
            ],
            'chart' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/Chart.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js',
                ],
            ],
            'moment' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/moment-with-locales.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js',
                ],
            ],
            'uploader' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/uploader/plupload.full.min.js',
                        '/vendor/core/packages/uploader/plupload.queue.min.js',
                    ],
                    'cdn' => [
                        '//cdnjs.cloudflare.com/ajax/libs/plupload/2.1.8/plupload.full.min.js',
                        '//cdnjs.cloudflare.com/ajax/libs/plupload/2.1.8/jquery.plupload.queue/jquery.plupload.queue.min.js',
                    ],
                ],
            ],
            'jquery-validation' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/jquery-validation/js/jquery.validate.min.js',
                        '/vendor/core/packages/jquery-validation/js/additional-methods.min.js',
                    ]
                ],
            ],
            'jquery-ui' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-ui/jquery-ui.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
                ],
            ],
            'bootstrap' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap/dist/js/bootstrap.min.js',
                    'cdn' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
                ],
            ],
            'cookie' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-cookie/jquery.cookie.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
                ],
            ],
            'jqueryTree' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'include_style' => true,
                'src' => [
                    'local' => '/vendor/core/packages/jquery-tree/jquery.tree.min.js',
                    'cdn' => '',
                ],
            ],
            'floatThead' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/floatThead/floatThead.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/floatthead/1.4.0/jquery.floatThead.min.js',
                ],
            ],
            'bootstrap-editable' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js',
                ],
            ],
            'toastr' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/toastr/toastr.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.js',
                ],
            ],
            'pace' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/pace/pace.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
            'fancybox' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/fancybox/source/jquery.fancybox.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js`',
                ],
            ],
            'datatables' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/datatables/media/js/jquery.dataTables.min.js',
                        '/vendor/core/packages/datatables/extensions/Buttons/js/dataTables.buttons.min.js',
                        '/vendor/core/packages/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js',
                        '/vendor/core/packages/datatables/media/js/dataTables.bootstrap.min.js',
                        '/vendor/core/packages/datatables/extensions/Buttons/js/buttons.bootstrap.min.js',
                    ],
                ],
            ],
            'bootstrap-tagsinput' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js',
                ],
            ],
            'bootstrap-pwstrength' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js',
                ],
            ],
            'highlight' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/highlight/highlight.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js',
                ],
            ],
            'typeahead' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/typeahead.js/dist/typeahead.jquery.min.js',
                        '/vendor/core/packages/typeahead.js/dist/bloodhound.min.js',
                    ],
                    'cdn' => [
                        '//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.jquery.min.js',
                        '//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/bloodhound.min.js',
                    ],
                ],
            ],
            'jvectormap' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/jvectormap/jquery-jvectormap-1.2.2.min.js',
                        '/vendor/core/packages/jvectormap/jquery-jvectormap-world-mill-en.js',
                        '/vendor/core/packages/jvectormap/raphael-min.js',
                    ],
                ],
            ],
            'morris' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/morris/morris.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
                ],
            ],
            'select2' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/select2/dist/js/select2.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js',
                ],
            ],
            'uniform' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery.uniform/jquery.uniform.min.js',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/Uniform.js/2.1.2/jquery.uniform.min.js',
                ],
            ],
            'datepicker' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                    ],
                    'cdn' => [''],
                ],
            ],
            'sortable' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/Sortable/Sortable.min.js',
                ],
            ],
            'custom-scrollbar' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/mcustom-scrollbar/jquery.mCustomScrollbar.js',
                ],
            ],
            'stickytableheaders' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/stickytableheaders/jquery.stickytableheaders.js',
                ],
            ],
            'counterup' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/counterup/jquery.waypoints.min.js',
                        '/vendor/core/packages/counterup/jquery.counterup.min.js',
                    ]
                ],
            ],
            'equal-height' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jQuery.equalHeights/jquery.equalheights.min.js',
                ],
            ],
            'jquery-nestable' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-nestable/jquery.nestable.js',
                ],
            ],
            'are-you-sure' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery.are-you-sure/jquery.are-you-sure.js',
                ],
            ],
            'selectables' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/selectables/selectables.js',
                ],
            ],
            'jquery-waypoints' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-waypoints/jquery.waypoints.min.js',
                ],
            ],

            // End JS
        ],
        /* -- STYLESHEET ASSETS -- */
        'stylesheets' => [
            'bootstrap' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap/dist/css/bootstrap.min.css',
                    'cdn' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
                ],
            ],
            'fontawesome' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/font-awesome/css/font-awesome.min.css',
                    'cdn' => '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css'
                ],
            ],
            'simple-line-icons' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/simple-line-icons/css/simple-line-icons.css',
                ],
            ],
            'core' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => 'vendor/core/css/core.css',
                ],
            ],
            'jqueryTree' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-tree/jquery.tree.min.css',
                ],
            ],
            'videojs' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/videojs/video-js.min.css',
                    'cdn' => '//vjs.zencdn.net/5.8/video-js.min.css',
                ],
            ],
            'jquery-ui' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-ui/jquery-ui.min.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css',
                ],
            ],
            'toastr' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/toastr/toastr.min.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css',
                ],
            ],
            'pace' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/pace/themes/blue/pace-theme-minimal.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-minimal.css',
                ],
            ],
            'kendo' => [
                'use_cdn' => false,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/kendo/kendo.min.css',
                    'cdn' => '',
                ],
            ],
            'datatables' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => [
                        '/vendor/core/packages/datatables/extensions/Buttons/css/buttons.bootstrap.min.css',
                        '/vendor/core/packages/datatables/extensions/ColReorder/css/colReorder.bootstrap.min.css',
                        '/vendor/core/packages/datatables/media/css/dataTables.bootstrap.min.css',
                    ],
                ],
            ],
            'bootstrap-editable' => [
                'use_cdn' => true,
                'location' => 'bottom',
                'src' => [
                    'local' => '/vendor/core/packages/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css',
                ],
            ],
            'bootstrap-tagsinput' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css',
                ],
            ],
            'jvectormap' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/jvectormap/jquery-jvectormap-1.2.2.css',
                ],
            ],
            'morris' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/morris/morris.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
                ],
            ],
            'datepicker' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ],
            ],
            'select2' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/select2/dist/css/select2.min.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css',
                ],
            ],
            'fancybox' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/fancybox/source/jquery.fancybox.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css',
                ],
            ],
            'custom-scrollbar' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/mcustom-scrollbar/jquery.mCustomScrollbar.css'
                ],
            ],
            'jquery-nestable' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-nestable/jquery.nestable.css'
                ],
            ],
            'tabdrop' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-tabdrop/css/tabdrop.css'
                ],
            ],
            'datetimepicker' => [
                'use_cdn' => true,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/bootstrap-datetimepicker.min.css',
                    'cdn' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css'
                ],
            ],
            'jquery-file-tree' => [
                'use_cdn' => false,
                'location' => 'top',
                'src' => [
                    'local' => '/vendor/core/packages/jquery-filetree/jQueryFileTree.min.css'
                ],
            ],
        ],
    ],
];

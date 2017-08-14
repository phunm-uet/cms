var BEditor = {
    initEditor: function (element, extraConfig) {
        if (element.length) {
            if ($('.editor-ckeditor').length > 0) {
                var config = {
                    extraPlugins: 'codesnippet',
                    codeSnippet_theme: 'monokai_sublime',
                    height: 356,
                    allowedContent: true
                };
                var mergeConfig = {};
                $.extend(mergeConfig, config, extraConfig);
                CKEDITOR.replace(element.prop('id'), mergeConfig);
            }

            if ($('.editor-tinymce').length > 0) {
                tinymce.init({
                    selector:'#' + element.prop('id'),
                    plugins: [
                        'bootstrap',
                        'image'
                    ],
                    bootstrapConfig: {
                        'imagesPath': '/editor/' // replace with your images folder path
                    },
                    toolbar: 'bootstrap'
                });
            }
        }
    }
};

$(document).ready(function () {
    if ($('.editor-ckeditor').length > 0) {
        BEditor.initEditor($('.editor-ckeditor'), {});
    }
    if ($('.editor-tinymce').length > 0) {
        BEditor.initEditor($('.editor-tinymce'), {});
    }
});
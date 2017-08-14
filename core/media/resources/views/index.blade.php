@extends('bases::layouts.master')
@section('content')
    <div class="block" style="margin-top: 15px;">
        <div class="tabbable-custom tabbable-tabdrop green">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#medialibrary-myfiles" data-toggle="tab">{{ trans('media::media.my_files') }}</a></li>
                <li><a href="#medialibrary-shared" id="trigger-media-shared" data-toggle="tab">{{ trans('media::media.share_files') }}</a></li>
            </ul>

            <div class="tab-content with-padding">
                <div class="tab-pane fade in active" id="medialibrary-myfiles"></div>
                <div class="tab-pane fade" id="medialibrary-shared"></div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            window.MediaGallery = window.MediaGallery || {};

            window.MediaGallery.target = 'images';
            window.MediaGallery.action = 'media';
            $('.galleryLoading').show();
            $('#medialibrary-myfiles').load(BMedia.routes.files_show + '?action=' + window.MediaGallery.action, function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    $('.galleryLoading').fadeOut(500);
                    $('.btn_gallery').addClass('active');
                }
            });
            $('#trigger-media-shared').on('click', function () {
                $('#medialibrary-shared').load(BMedia.routes.shared_show + '?action=' + window.MediaGallery.action, function (data) {
                    if (data.error) {
                        Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                    } else {
                        $('.galleryLoading').fadeOut(500);
                        $('.btn_gallery').addClass('active');
                    }
                });
            });
        });
    </script>
@stop

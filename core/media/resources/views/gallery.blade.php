<div class="galleryLoading">
    <div class="gallery-spin"><i class="icon-spinner7 spin"></i> {{ trans('media::media.media_loading') }}</div>
    <div class="bg"></div>
</div>
<div class="list-files">
    <div class="upload-controls" data-folder="0">
        @if (!isset($shared) || !$shared)
            <a data-toggle="modal" data-target="#modal-file-upload" class="btn btn-primary"><i class="fa fa-upload"></i> {{ trans('media::media.upload_files') }}</a>
            <a class="btn btn-danger" data-backdrop="static" data-keyboard="false" id="youtube_add"><i class="fa fa-youtube"></i> {{ trans('media::media.add_from_youtube') }}</a>
            <a class="btn btn-info" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_new_folder"><i class="fa fa-plus"></i> {{ trans('media::media.create_folder') }}</a>
            <button class="btn btn-primary" id="refresh_media"><i class="fa fa-refresh"></i> {{ trans('media::media.refresh') }}</button>
        @endif
    </div>
    <div class="file-rows">
        <ul class="list-thumbnails clearfix" id="list-media-item">
            @if (count($contents['folders']))
                @foreach ($contents['folders'] as $folder)
                    @include('media::partials.folder-row', ['folder' => $folder])
                @endforeach
            @endif
            @if (count($contents['files']))
                @foreach ($contents['files'] as $file)
                    @include('media::partials.file-row', ['file' => $file, 'shared' => isset($shared) ? true : false])
                @endforeach
            @endif
            @if (!count($contents['folders']) && !count($contents['files']))
                <p id="mediaNoFiles">{{ trans('media::media.no_file_found') }}</p>
            @endif
        </ul>

    </div>
    <div class="user-quota">
        <span class="quota_used">{{ human_file_size($filesystem->getSpaceUsed()) }}</span> {{ trans('media::media.of') }}
        <span class="quota_total">{{ human_file_size($filesystem->getQuota()) }}</span> {{ trans('media::media.used') }}
        (<span class="quota_percent">{{ $filesystem->getPercentageUsed() }}</span>%).
    </div>
</div>
<div class="clearfix"></div>

<script type="text/javascript" src="/vendor/core/packages/videojs/video.min.js"></script>
<script type="text/javascript" src="/vendor/core/packages/videojs/Youtube.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('[data-rel="fancybox"]').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            width: 840,
            height: 585,
            overlayShow: true,
            overlayOpacity: 0.7,
            helpers: {
                media: {}
            }
        });
    });
</script>

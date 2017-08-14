<li data-id="{{ $file->id }}" class="file-item">

    @if (session('media_action') == 'media')
        @if ($file->document_type === 'video' || $file->document_type === 'youtube')
            <div style="display: none;">
                <div id="inline_video_{{ $file->id }}">
                    <video id="video_{{ $file->id }}" class="video-js vjs-default-skin" controls
                           preload="auto" width="800" height="446"
                           data-setup='{@if ($file->document_type === 'youtube') "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "{{ url($file->public_url) }}"}] @endif}'>
                        <source src="{{ url($file->public_url) }}" type='video/mp4'>
                        <p class="vjs-no-js">{{ trans('media::media.no_support_videojs') }}</p>
                    </video>

                </div>
            </div>
            <a href="#inline_video_{{ $file->id }}" title="{{ $file->name }}" data-rel="fancybox">
                <div class="other-files"><i class="icon icon-video-camera"></i></div>
                <div class="text">
                    <div class="inner"><i class="fa fa-eye"></i></div>
                </div>
            </a>
        @else
            <a href="{{ url($file->public_url) }}" title="{{ $file->name }}"
               @if (is_image($file->mime_type)) data-rel="fancybox" @else target="_blank" @endif>
                @if (is_image($file->mime_type))
                    <img width="150" height="150"
                         src="{{ url(get_file_by_size($file->public_url, config('media.thumb-size'))) }}"
                         alt="{{ $file->name }}"/>
                @else
                    <div class="other-files"><i class="fa fa-{{ $file->getIconAttribute() }}"></i></div>
                @endif
                <div class="text">
                    <div class="inner"><i class="fa fa-eye"></i></div>
                </div>
            </a>
        @endif
    @else
        <a href="#" data-action="attach" data-type="{{ $file->document_type }}" data-name="{{ $file->name }}"
           @if (is_image($file->mime_type)) data-thumb="{{ url(get_file_by_size($file->public_url, config('media.thumb-size'))) }}"
           @endif data-src="{{ $file->public_url }}">
            @if (is_image($file->mime_type))
                <img width="150" height="150"
                     src="{{ url(get_file_by_size($file->public_url, config('media.thumb-size'))) }}"
                     alt="{{ $file->name }}"/>
            @else
                <div class="other-files"><i class="fa fa-{{ $file->getIconAttribute() }}"></i></div>
            @endif
            <div class="text">
                <div class="inner"><i class="fa fa-paperclip"></i></div>
            </div>
        </a>
    @endif

    <div class="item-name">
        <p>{{ $file->name }}</p>
    </div>

    <div class="tools tools-bottom">
        <a href="#" data-name="{{ $file->name }}" data-action="share" data-pk-id="{{ $file->id }}" data-type="file">
            <i class="fa fa-link"></i>
        </a>

        @if (session('media_action') == 'media')
            <a href="#" data-action="edit" data-pk-id="{{ $file->id }}" data-type="file">
                <i class="fa fa-edit"></i>
            </a>
        @else
            <a href="{{ url($file->public_url) }}" title="{{ $file->name }}" data-rel="fancybox">
                <i class="fa fa-eye"></i>
            </a>
        @endif

        <a href="#" data-toggle="modal" data-target="#file_detail_modal">
            <i class="fa fa-question"></i>
        </a>

        <a href="#" data-action="delete" data-id="{{ $file->id }}" data-type="file">
            <i class="fa fa-times text-danger"></i>
        </a>
    </div>
</li>

@if (empty($folder))
    <li class="folder_item up_level_item">
        <a data-href="{{ route('files.gallery.ajax', ['folder' => null, 'action' => session('media_action')]) }}">
            <i class="icon icon-folder-images"></i>
            <p>.. {{ trans('media::media.up_level') }}</p>
        </a>
    </li>
@elseif ($folder != -1)
    <li class="folder_item up_level_item" data-id="{{ $folder }}">
        <a data-href="{{ route('files.gallery.ajax', ['folder' => $folder, 'action' => session('media_action')]) }}">
            <i class="icon icon-folder-images"></i>
            <p>.. {{ trans('media::media.up_level') }}</p>
        </a>
    </li>
@endif


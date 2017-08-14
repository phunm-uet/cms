<li class="folder_item" data-id="{{ $folder->slug }}">
    <a data-href="{{ route('files.gallery.ajax', ['action' => session('media_action'), 'folder' => $folder->slug]) }}"
       title="{{ $folder->name }}">
        <i class="@if (array_key_exists($folder->slug, config('media.special_folders'))) {{ config('media.special_folders.' . $folder->slug . '.icon') }} @else icon icon-folder-images @endif"></i>
    </a>

    <div class="item-name">
        <p>{{ $folder->name }}</p>
    </div>

    <div class="tools tools-bottom">
        <a href="#" data-name="{{ $folder->name }}" data-action="share" data-pk-id="{{ $folder->id }}" data-type="folder">
            <i class="fa fa-link"></i>
        </a>
        <a href="#" data-action="edit" data-pk-id="{{ $folder->id }}" data-type="folder">
            <i class="fa fa-edit"></i>
        </a>
        <a class="deleteFolder" data-slug="{{ $folder->slug }}">
            <i class="fa fa-times text-danger" title="{{ trans('media::media.delete_this_folder') }}"></i>
        </a>
    </div>
</li>


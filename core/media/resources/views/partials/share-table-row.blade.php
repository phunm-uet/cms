<tr data-shareid="{{ $share->id }}">
    <td>{{ trans('media::media.personal') }}</td>
    <td>
        @if (!empty($share->user_id))
            {{ $share->user->getFullName() }}
        @else
            {{ trans('bases::layouts.n_a') }}
        @endif
    </td>
    <td>{{ $share->created_at->diffForHumans() }}</td>
    <td>
        <button class="btn btn-danger btn-icon removeShare tip" data-share-id="{{ $share->id }}" data-original-title="{{ trans('media::media.remove_share') }}"><i class="fa fa-trash"></i></button>
    </td>
</tr>

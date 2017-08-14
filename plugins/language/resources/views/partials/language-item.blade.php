<tr data-code="{{ $item->code }}">
    <td class="text-left">
        <a data-original-title="{{ trans('language::language.edit_tooltip') }}" class="tip edit-language-button" data-id="{{ $item->id }}" href="#">{{ $item->name }}</a>
    </td>
    <td>{{ $item->locale }}</td>
    <td>{{ $item->code }}</td>
    <td>
        @if ($item->is_default)
            <i class="fa fa-star" data-id="{{ $item->id }}" data-name="{{ $item->name }}"></i>
        @else
            <a data-section="{{ route('languages.set.default') }}?id={{ $item->id }}" class="set-language-default tip" data-original-title="{{ trans('language::language.choose_default_language', ['language' => $item->name]) }}"><i class="fa fa-star" data-id="{{ $item->id }}" data-name="{{ $item->name }}"></i></a>
        @endif</td>
    <td>{{ $item->order }}</td>
    <td>
        {!! language_flag($item->flag, $item->name) !!}
    </td>
    <td>
        <span>
            <a data-original-title="Edit this language" class="tip edit-language-button" data-id="{{ $item->id }}" href="#">{{ trans('language::language.edit') }}</a> |
        </span>
        <span>
            <a class="deleteDialog tip" data-toggle="modal" data-section="{{ route('languages.delete', $item->id) }}" role="button" data-original-title="{{ trans('language::language.delete_tooltip') }}">{{ trans('language::language.delete') }}</a>
        </span>
    </td>
</tr>
<div id="select-post-language">
    <table class="select-language-table">
        <tbody>
            <tr>
                <td class="active-language">
                    {!! language_flag($current_language->flag, $current_language->name) !!}
                </td>
                <td class="translation-column">
                    <select name="language" id="post_lang_choice" class="form-control select-full">
                        @foreach($languages as $language)
                            @if (!array_key_exists(json_encode([$language->code]), $related))
                                <option value="{{ $language->code }}" @if ($language->code == $current_language->code) selected="selected" @endif data-flag="{{ $language->flag }}">{{ $language->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<p><strong>{{ trans('language::language.translations') }}</strong>
    <div id="list-others-language">
        @foreach($languages as $language)
            @if ($language->code != $current_language->code)
                {!! language_flag($language->flag, $language->name) !!}
                @if (array_key_exists($language->code, $related))
                    <a href="{{ route($route['edit'], $related[$language->code]) }}"> {{ $language->name }} <i class="fa fa-edit"></i></a>
                    <br>
                @else
                    <a href="{{ route($route['create']) }}?from={{ !empty($args[0]) ? $args[0]->id : 0 }}&lang={{ $language->code }}"> {{ $language->name }} <i class="fa fa-plus"></i></a>
                    <br>
                @endif
            @endif
        @endforeach
    </div>
</p>
<input type="hidden" id="created_from" name="from" value="{{ Request::get('from') }}">
<input type="hidden" id="content_id" value="{{ $args[0] ? $args[0]->id : '' }}">
<input type="hidden" id="reference" value="{{ $args[1] }}">
<input type="hidden" id="route_create" value="{{ route($route['create']) }}">
<input type="hidden" id="route_edit" value="{{ route($route['edit'], $args[0] ? $args[0]->id : '') }}">
<input type="hidden" id="language_flag_path" value="{{ BASE_LANGUAGE_FLAG_PATH }}">

<div data-change-language-route="{{ route('languages.change.item.language') }}"></div>

{!! Form::modalAction('confirm-change-language-modal', trans('language::language.confirm-change-language'), 'warning', trans('language::language.confirm-change-language-message'), 'confirm-change-language-button', trans('language::language.confirm-change-language-btn')) !!}
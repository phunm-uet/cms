@if (setting('language_switcher_display', 'dropdown') == 'dropdown')
    {!! array_get($options, 'before') !!}
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            @if (array_get($options, 'flag', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'flag'))
                {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
            @endif
            @if (array_get($options, 'name', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'name'))
                {{ Language::getCurrentLocaleName() }}
            @endif
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu language_bar_chooser {{ array_get($options, 'class') }}">
            @foreach (Language::getSupportedLocales() as $localeCode => $properties)
                <li @if ($localeCode == Language::getCurrentLocale()) class="active" @endif>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ Language::getLocalizedURL($localeCode) }}">
                        @if (array_get($options, 'flag', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'flag')){!! language_flag($properties['flag'], $properties['name']) !!}@endif
                        @if (array_get($options, 'name', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'name'))<span>{{ $properties['name'] }}</span>@endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    {!! array_get($options, 'after') !!}
@else
    <ul class="language_bar_list {{ array_get($options, 'class') }}">
        @foreach (Language::getSupportedLocales() as $localeCode => $properties)
            <li @if ($localeCode == Language::getCurrentLocale()) class="active" @endif>
                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ Language::getLocalizedURL($localeCode) }}">
                    @if (array_get($options, 'flag', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'flag')){!! language_flag($properties['flag'], $properties['name']) !!}@endif
                    @if (array_get($options, 'name', true) && (setting('language_display', 'all') == 'all' || setting('language_display') == 'name'))<span>{{ $properties['name'] }}</span>@endif
                </a>
            </li>
        @endforeach
    </ul>
@endif
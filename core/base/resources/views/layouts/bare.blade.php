@extends('bases::layouts.base')

@section('body-class') full-width page-condensed @stop

@section('page')

    <!-- Navbar -->
    <div class="navbar navbar-inverse" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('dashboard.index') }}">
                <img class="logo">
            </a>
        </div>

        <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
            @if (isset($brands) && setting()->get('enable_change_admin_theme') != false)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span>{{ trans('bases::layouts.brand') }}</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right">

                        @foreach ($brands as $name => $file)
                            @if (session()->has('brand') && session('brand') === $name)
                                <li class="active"><a href="{{ route('user.brand', [$name]) }}">{{ $name }}</a></li>
                            @else
                                <li><a href="{{ route('user.brand', [$name]) }}">{{ $name }}</a></li>
                            @endif
                        @endforeach
                        @if (!session()->has('brand'))
                            <li class="active"><a href="{{ route('user.brand', ['botble']) }}">Botble</a></li>
                        @else
                            <li><a href="{{ route('user.brand', ['botble']) }}">Botble</a></li>
                        @endif

                    </ul>
                </li>
            @endif
            @if (setting()->get('enable_multi_language_in_admin') != false)
                <li class="language dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/images/flags/{{ config('cms.locales')[app()->getLocale()]['flag'] }}.png">
                        <span>{{ config('cms.locales')[app()->getLocale()]['text'] }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right">

                        @foreach (config()->get('cms.locales') as $key => $value)
                            @if (app()->getLocale() == $key)
                                <li class="active">
                                    <a href="{{ route('change-language', $key) }}">
                                        <img src="/images/flags/{{ $value['flag'] }}.png" alt="{{ $value['text'] }}"> {{ $value['text'] }}
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('change-language', $key) }}">
                                        <img src="/images/flags/{{ $value['flag'] }}.png" alt="{{ $value['text'] }}"> {{ $value['text'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                </li>
            @endif
        </ul>
    </div>
    <!-- /navbar -->

    @yield('content')

    <!-- Footer -->
    <div class="footer clearfix center-block row">
        <div class="col-xs-12 col-sm-8">{!! trans('bases::layouts.copyright') !!}</div>

        <div class="hidden-xs col-sm-4 text-right">
            <strong>{{ trans('bases::layouts.powered_by') }}</strong>
            <a href="http://www.botble.com"><img src="{{ url('/images/logos/logo.png') }}"/></a>
        </div>
    </div>
    <!-- /footer -->
@stop

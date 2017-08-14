@extends('bases::layouts.master')
@section('content')
    <div id="theme-option-header">
        <div class="display_header">
            <h2>Theme options</h2>
            @if (ThemeOption::getArg('debug') == true) <span class="theme-option-dev-mode-notice">Developer Mode Enabled</span>@endif
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="theme-option-intro-text">This is a theme options for {{ studly_case(setting('theme')) }} theme, please make sure that you can control what you changes.</div>
    <div class="theme-option-container">
        <div class="theme-option-sidebar">
            <ul class="nav nav-tabs tab-in-left">
                @foreach(ThemeOption::constructSections() as $section)
                    <li @if ($loop->first) class="active" @endif>
                        <a href="#tab_{{ $section['id'] }}" data-toggle="tab">@if (!empty($section['icon']))<i class="{{ $section['icon'] }}"></i> @endif {{ $section['title']  }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="theme-option-main">
            {!! Form::open(['route' => 'theme.options']) !!}
                <div class="tab-content tab-content-in-right">
                    <div class="theme-option-sticky">
                        <div class="info_bar">
                            <div class="theme-option-action_bar">
                                <span class="fa fa-spin fa-spinner hidden"></span>
                                <input type="submit" class="btn btn-primary" value="Save Changes">
                                {{--<input type="submit" class="btn btn-info" value="Reset Section">
                                <input type="submit" class="btn btn-info" value="Reset All">--}}
                            </div>
                        </div>
                    </div>
                    @foreach(ThemeOption::constructSections() as $section)
                        <div class="tab-pane @if ($loop->first) active @endif" id="tab_{{ $section['id'] }}">
                            @foreach (ThemeOption::constructFields($section['id']) as $field)
                                <div class="form-group @if ($errors->has($field['attributes']['name'])) has-error @endif">
                                    {!! Form::label($field['attributes']['name'], $field['label'], ['class' => 'control-label']) !!}
                                    {!! ThemeOption::renderField($field) !!}
                                    @if (array_key_exists('helper', $field))
                                        <span class="help-block">{!! $field['helper'] !!}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="theme-option-sticky sticky-bottom">
                        <div class="info_bar">
                            <div class="theme-option-action_bar">
                                <span class="fa fa-spin fa-spinner hidden"></span>
                                <input type="submit" class="btn btn-primary" value="Save Changes">
                                {{--<input type="submit" class="btn btn-info" value="Reset Section">
                                <input type="submit" class="btn btn-info" value="Reset All">--}}
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <style>
        #theme-option-header{
            text-align: right;
            padding: 6px 10px;
            background: #23282d;
            border-bottom: 3px solid #0073aa;
        }

        #theme-option-header .display_header {
            float: left;
            margin: 20px 10px;
            text-align: left;
        }

        #theme-option-header .display_header h2 {
            font-style: normal;
            padding-right: 5px;
            color: #fff;
        }

        #theme-option-header .display_header span.theme-option-dev-mode-notice {
            background-color: #f0ad4e;
            padding: .2em .5em .2em;
            font-weight: 700;
            color: #fff !important;
            border-radius: .25em;
        }
        #theme-option-intro-text {
            background: #f3f3f3;
            border-bottom: 1px solid #dedede;
            -moz-box-shadow: inset 0 1px 0 #fcfcfc;
            -webkit-box-shadow: inset 0 1px 0 #fcfcfc;
            box-shadow: inset 0 1px 0 #fcfcfc;
            padding: 10px 10px;
        }

        .theme-option-container {
            background-color: #f5f5f5;
            background-repeat: repeat-x;
            background-image: -moz-linear-gradient(top, #f2f2f2 0%, #f5f5f5 100%);
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f2f2f2), color-stop(100%, #f5f5f5));
            background-image: -webkit-linear-gradient(top, #f2f2f2 0%, #f5f5f5 100%);
            background-image: -ms-linear-gradient(top, #f2f2f2 0%, #f5f5f5 100%);
            background-image: -o-linear-gradient(top, #f2f2f2 0%, #f5f5f5 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5f5f5', endColorstr='#eeeeee', GradientType=0);
            background-image: -linear-gradient(top, #f2f2f2 0%, #f5f5f5 100%);
            border: 1px solid #dedede;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            -moz-box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            border-top: none;
            overflow: hidden;
        }
        .theme-option-sticky {
            min-height: 32px;
            margin: -10px -20px 0 -20px;
        }
        .theme-option-sticky.sticky-bottom {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
        }
        .theme-option-sticky .info_bar {
            height: 38px;
            background: #f3f3f3;
            border-bottom: 1px solid #dedede;
            padding: 6px 10px 6px 6px;
            text-align: right;
            -moz-box-shadow: inset 0 1px 0 #fcfcfc;
            -webkit-box-shadow: inset 0 1px 0 #fcfcfc;
            box-shadow: inset 0 1px 0 #fcfcfc;
        }
        .theme-option-sticky .info_bar .btn {
            margin: 0 10px 0;
            padding: 4px 6px;
            font-size: 12px;
        }
        .theme-option-action_bar {
            float: right;
        }
        .theme-option-sidebar {
            width: 202px;
            float: left;
            min-height: 300px;
        }
        .theme-option-main {
            background: #FCFCFC;
            margin-left: 202px;
            border-left: 1px solid #D8D8D8;
            padding: 10px 20px 0;
            -moz-box-shadow: inset 0 1px 0 #fff;
            -webkit-box-shadow: inset 0 1px 0 #FFF;
            box-shadow: inset 0 1px 0 #FFF;
        }
        .tab-in-left li {
            float: none;
        }
        .tab-in-left li a {
            display: block;
            padding: 10px 4px 10px 14px;
            border-bottom-color: #E7E7E7;
            font-weight: 600;
            text-decoration: none;
            -webkit-transition: none;
            transition: none;
            background: #E7E7E7;
            opacity: 0.7;
            color: #555;
            width: 100%;
            border: none !important;
        }
        .nav-tabs.tab-in-left li.active a, .nav-tabs.tab-in-left li a:hover {
            color: #23282d;
            background: #FCFCFC;
            opacity: 1;
        }
        .tab-in-left li a:hover {
            background: #e5e5e5;
            color: #777;
        }
        .tab-content-in-right {
            border: none;
            background: none;
            padding: 0;
            position: relative;
            height: 100%;
            min-height: 300px;
        }

        .tab-content-in-right .tab-pane {
            margin: 20px 0 60px;
        }
    </style>
@stop

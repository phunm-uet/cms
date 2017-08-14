@extends('bases::layouts.master')
@section('content')
    {!! Form::open(['route' => ['rss.edit', $rss->id]]) !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, RSS_MODULE_SCREEN_NAME, request(), $rss) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-body">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                            {!! Form::text('name', $rss->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name_placeholder'), 'data-counter' => 120]) !!}
                        </div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'advanced', $rss) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @include('bases::elements.forms.status', ['selected' => $rss->status])
                @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'top', $rss) @endphp
                @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'side', $rss) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop

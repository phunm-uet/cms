@extends('bases::layouts.master')
@section('content')
        {!! Form::open(['route' => 'rss.create']) !!}
            @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, RSS_MODULE_SCREEN_NAME, request(), null) @endphp
            <div class="row">
                <div class="col-md-9">
                    <div class="main-form">
                        <div class="form-body">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name_placeholder'), 'data-counter' => 120]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'advanced') @endphp
                </div>
                <div class="col-md-3 right-sidebar">
                    @include('bases::elements.form-actions')
                    @include('bases::elements.forms.status')
                    @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'top') @endphp
                    @php do_action(BASE_ACTION_META_BOXES, RSS_MODULE_SCREEN_NAME, 'side') @endphp
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@stop

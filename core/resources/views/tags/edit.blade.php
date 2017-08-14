@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::tags.edit'), Route::currentRouteName()) !!}
    <div class="clearfix"></div>
    {!! Form::open() !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, TAG_MODULE_SCREEN_NAME, request(), $tag) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name" class="control-label required">{{ trans('blog::tags.form.name') }}</label>
                        {!! Form::text('name', $tag->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::tags.form.name'), 'data-counter' => 120]) !!}
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label for="description" class="control-label">{{ trans('blog::tags.form.description') }}</label>
                        {!! Form::textarea('description', $tag->description, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::tags.form.description_placeholder'), 'data-counter' => 400]) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'advanced', $tag) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'top', $tag) @endphp
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'side', $tag) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop

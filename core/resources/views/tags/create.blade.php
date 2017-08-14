@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::tags.create'), Route::currentRouteName()) !!}
    <div class="clearfix"></div>
    {!! Form::open() !!}
        @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, TAG_MODULE_SCREEN_NAME, request(), null) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-body">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name" class="control-label required">{{ trans('blog::tags.form.name') }}</label>
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::tags.form.name_placeholder'), 'data-counter' => 120]) !!}
                        </div>
                        <div class="form-group @if ($errors->has('description')) has-error @endif">
                            <label for="description" class="control-label">{{ trans('blog::tags.form.description') }}</label>
                            {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::tags.form.description_placeholder'), 'data-counter' => 400]) !!}
                        </div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'advanced') @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'top') @endphp
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop

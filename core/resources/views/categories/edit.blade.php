@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::categories.edit'), Route::currentRouteName()) !!}
    <div class="clearfix"></div>
        {!! Form::open() !!}
            @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, CATEGORY_MODULE_SCREEN_NAME, request(), $category) @endphp
            <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-body">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                            {!! Form::text('name', $category->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name_placeholder'), 'data-counter' => 120]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::permalink('slug', $category->slug, $category->id, route('categories.create.slug')) !!}
                        </div>
                        <div class="form-group @if ($errors->has('parent_id')) has-error @endif">
                            <label for="parent_id" class="control-label">{{ trans('bases::forms.parent') }}</label>
                            {!! Form::select('parent_id', $categories, $category->parent_id, ['class' => 'select-search-full', 'id' => 'parent_id']) !!}
                        </div>
                        <div class="form-group @if ($errors->has('description')) has-error @endif">
                            <label for="description" class="control-label required">{{ trans('bases::forms.description') }}</label>
                            {!! Form::textarea('description', $category->description, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('bases::forms.description_placeholder'), 'data-counter' => 400]) !!}
                        </div>
                        <div class="form-group @if ($errors->has('is_default')) has-error @endif">
                            {!! Form::onOff('is_default', $category->is_default) !!}
                            <label for="is_default">{{ trans('bases::forms.is_default') }}</label>
                        </div>
                        <div class="form-group @if ($errors->has('icon')) has-error @endif">
                            <label for="icon" class="control-label">{{ trans('bases::forms.icon') }}</label>
                            {!! Form::text('icon', $category->icon, ['class' => 'form-control', 'id' => 'icon', 'placeholder' => trans('bases::forms.icon_placeholder'), 'data-counter' => 60]) !!}
                        </div>
                        <div class="form-group @if ($errors->has('order')) has-error @endif">
                            <label for="order" class="control-label">{{ trans('bases::forms.order_by') }}</label>
                            {!! Form::text('order', $category->order, ['class' => 'form-control', 'id' => 'order', 'placeholder' => trans('bases::forms.order_by_placeholder'), 'data-counter' => 60]) !!}
                        </div>
                        <div class="form-group @if ($errors->has('featured')) has-error @endif">
                            {!! Form::onOff('featured', $category->featured) !!}
                            <label for="featured">{{ trans('bases::forms.featured') }}</label>
                        </div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, 'category', 'advanced', $category) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, 'category', 'top', $category) @endphp
                @php do_action(BASE_ACTION_META_BOXES, 'category', 'side', $category) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop

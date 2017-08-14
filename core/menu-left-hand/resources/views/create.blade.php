@extends('bases::layouts.master')

@section('content')
    <div class="main-form">
        {!! Form::open(['route' => 'system.menu.left-hand.item.create', 'id' => 'menuItemForm', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <div class="row form-group">
                <label class="control-label col-sm-2 text-right">{{ trans('menu-left-hand::menu_left_hand.kind') }}</label>
                <div class="col-sm-5">
                    {!! Form::select('kind', ['category' => 'category', 'page' => 'page'], 'category', ['class' => 'select-full']) !!}
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-sm-2 text-right">{{ trans('menu-left-hand::menu_left_hand.name') }}<span
                        class="mandatory">*</span></label>
                <div class="col-sm-5">
                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-sm-2 text-right">{{ trans('menu-left-hand::menu_left_hand.feature') }}</label>
                <div class="col-sm-5">
                    {!! Form::select('feature_id', $featuresEnabled, null, ['class' => 'select-search-full']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2 text-right">{{ trans('menu-left-hand::menu_left_hand.icon') }}</label>
                <div class="col-sm-5">
                    {!! Form::text('icon', null, ['class' => 'form-control', 'placeholder' => trans('bases::forms.icon_placeholder')]) !!}
                    <div class="help-ts">{!! trans('menu-left-hand::menu_left_hand.icon_helper') !!}</div>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-sm-2 text-right">{{ trans('menu-left-hand::menu_left_hand.insert') }}</label>
                <div class="col-sm-3">
                    {!! Form::select('position', ['before' => trans('menu-left-hand::menu_left_hand.before'), 'after' => trans('menu-left-hand::menu_left_hand.after')], 'before', ['class' => 'select-search-full']) !!}
                </div>
                <div class="col-sm-5">
                    {!! Form::select('sibling_id', $allMenuItems, null, ['class' => 'select-search-full']) !!}
                </div>
            </div>
            <div class="form-actions text-center">
                <input type="submit" value="{{ trans('bases::forms.save') }}" class="btn btn-success">
            </div>
        {!! Form::close() !!}
    </div>
@stop

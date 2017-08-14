@extends('bases::layouts.master')
@section('content')
    @if ($role->reference !== 'global')
        {!! Form::open() !!}
    @endif
    <div class="main-form">
        @if ($role->reference == 'global')
            <div class="alert alert-warning fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="icon-info"></i> {{ trans('acl::permissions.global_role_msg') }}
            </div><br/>
        @endif
        <div class="form-group">
            <label>{{ trans('acl::permissions.role_name') }}</label>
            @if ($role->reference == 'global')
                {!! Form::text('name', $role->name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            @else
                {!! Form::text('name', $role->name, ['class' => 'form-control']) !!}
            @endif

        </div>

        <div class="form-group">
            <label>{{ trans('acl::permissions.role_description') }}</label>
            @if ($role->reference == 'global')
                {!! Form::textarea('description', $role->description, ['class' => 'form-control', 'disabled' => 'disabled', 'rows' => 4]) !!}
            @else
                {!! Form::textarea('description', $role->description, ['class' => 'form-control', 'rows' => 4]) !!}
            @endif

        </div>

        <div class="form-group">
            <input type="checkbox" id="is_staff" @if ($role->reference == 'global') disabled="disabled" @endif name="is_staff" value="1" @if ($role->is_staff) checked="checked" @endif>
            <label for="is_staff">{{ trans('acl::permissions.is_staff') }}</label>

        </div>

        <div class="form-actions text-right">
            <a href="{{ route('roles.list') }}" class="btn btn-default" id="cancelButton">{{ trans('acl::permissions.cancel') }}</a>
            <input type="reset" value="{{ trans('acl::permissions.reset') }}" class="btn btn-default">
            <a href="{{ route('roles.duplicate', [$role->id]) }}" class="btn btn-primary">{{ trans('acl::permissions.duplicate') }}</a>
            <input type="submit" value="{{ trans('acl::permissions.save') }}" @if ($role->reference == 'global') disabled="disabled" @endif class="btn btn-success">
        </div>
    </div>

    <div class="widget">
        <div class="widget-title">
            <h4><i class="box_img_sale"></i><span> {{ trans('acl::permissions.permission_flags') }}</span></h4>
        </div>
        <div class="widget-body">
            <!-- Include New UI of Permission Flags -->
            @include('acl::roles.role-permissions')
            <!-- Include New UI of Permission Flags -->
            <div class="form-actions text-right">
                <a href="{{ route('roles.list') }}" class="btn btn-default" id="cancelButton">{{ trans('acl::permissions.cancel') }}</a>
                <input type="reset" value="{{ trans('acl::permissions.reset') }}" class="btn btn-default">
                <a href="{{ route('roles.duplicate', [$role->id]) }}" class="btn btn-primary">{{ trans('acl::permissions.duplicate') }}</a>
                <input type="submit" value="{{ trans('acl::permissions.save') }}" @if ($role->reference == 'global') disabled="disabled" @endif class="btn btn-success">
            </div>
        </div>
    </div>

    @if ($role->reference !== 'global')
        {!! Form::close() !!}
    @endif
@stop

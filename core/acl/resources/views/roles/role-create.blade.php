@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
        <div class="main-form">
            <div class="form-group">
                <label>{{ trans('acl::permissions.role_name') }}</label>
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                <label>{{ trans('acl::permissions.role_description') }}</label>
                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4]) !!}
            </div>

            <div class="form-group">
                <input type="checkbox" class="styled" name="is_staff" value="1" checked="checked">
                <label>{{ trans('acl::permissions.is_staff') }}</label>
            </div>
        </div>

        <div class="widget">
            <div class="widget-title">
                <h4>
                    <i class="box_img_sale"></i><span> {{ trans('acl::permissions.permission_flags') }}
                </h4>
            </div>
            <div class="widget-body">
                <!-- Include New UI of Permission Flags -->
            @include('acl::roles.role-permissions')
            <!-- Include New UI of Permission Flags -->

                <div class="form-actions text-right">
                    <a href="{{ route('roles.list') }}" class="btn btn-default" id="cancelButton">{{ trans('acl::permissions.cancel') }}</a>
                    <input type="reset" value="{{ trans('acl::permissions.reset') }}" class="btn btn-default">
                    <input type="submit" value="{{ trans('acl::permissions.save') }}" class="btn btn-success">
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@stop

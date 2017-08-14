@extends('bases::layouts.bare')

@section('body-id','login')

@section('content')

    <div class="login-wrapper">
        <div class="popup-header">
            <span class="text-semibold">{{ trans('acl::users.invite_user') }}</span>
        </div>
        <div class="well">
            @if (isset($error_msg))
                <div class="alert alert-error">
                    <p>{{ $error_msg }}</p>
                </div>
            @else
                {!! Form::open(['route' => ['invite.accept', $token]]) !!}
                <div class="form-group @if ($errors->has('username')) has-error @endif">
                    <label for="username" class="control-label">{{ trans('acl::users.username') }}</label>
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'id' => 'username', 'placeholder' => trans('acl::users.username')]) !!}
                </div>
                <div class="form-group has-feedback">
                    <label>{{ trans('acl::users.new_password') }}</label>
                    {!! Form::password('password', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.new-password')]) !!}
                    <i class="icon icon-lock form-control-feedback"></i>
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('acl::users.confirm_new_password') }}</label>
                    {!! Form::password('repassword', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.repassword')]) !!}
                    <i class="icon icon-lock form-control-feedback"></i>
                </div>

                <div class="row form-actions">
                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-warning pull-right">
                            <i class="icon-menu2"></i>
                            {{ trans('acl::users.save') }}
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>

@stop

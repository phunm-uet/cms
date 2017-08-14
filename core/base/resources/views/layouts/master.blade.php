@extends('bases::layouts.base')

@section ('page')

    @include('bases::layouts.partials.top-header')

    <!-- Page container -->
    <div class="page-container col-md-12 @yield('page-class')">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-content">
                <!-- User dropdown -->
                <div class="user-menu dropdown">
                    <a href="{{ route('user.profile.view', ['id' => Sentinel::getUser()->id]) }}"
                       class="dropdown-toggle">
                        <img alt="profile image" src="{{ url(Sentinel::getUser()->getProfileImage()) }}"
                             class="img-circle">
                        <div class="user-info">
                            {{ Sentinel::getUser()->getFullName() }}
                            <span>{{ Sentinel::getUser()->job_position }}</span>
                        </div>
                    </a>

                </div>
                <!-- /user dropdown -->
                <!-- Main navigation -->
                @include('bases::layouts.partials.sidebar')
                <!-- /main navigation -->
            </div>
        </div>
        <!-- /sidebar -->

        <!-- Page content -->
        <div class="page-content" style="min-height: calc(100vh - 55px)">
            {!! AdminBreadcrumb::render() !!}
            <div class="clearfix"></div>
            @yield('content')

        </div>
        <!-- /page content -->
        <div class="clearfix"></div>
    </div>
    <!-- /page container -->
    @include('media::partials.media')
@stop

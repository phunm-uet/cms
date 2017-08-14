<nav class="admin-navbar" id="headerAdminBar">
    <div class="admin-logo">
        <a href="{{ route('dashboard.index') }}">
            <img src="{{ url(config('cms.logo')) }}" alt="logo" class="logo-default"/>
        </a>
    </div>
    <ul class="admin-navbar-nav">
        <li class="dropdown">
            <a href="{{ route('dashboard.index') }}" data-target="#" class="dropdown-toggle"
               data-toggle="dropdown"> {{ trans('bases::layouts.appearance') }}
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('menus.list') }}">
                        {{ trans('menu::menu.name') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.options') }}">
                        {{ trans('settings::setting.title') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="/" data-target="#" class="dropdown-toggle"
               data-toggle="dropdown"> {{ trans('bases::layouts.add_new') }}
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('pages.create') }}">
                        {{ trans('pages::pages.model') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('posts.create') }}">
                        {{ trans('blog::posts.model') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.create') }}">
                        {{ trans('blog::categories.model') }}
                    </a>
                </li>
            </ul>
        </li>
        @if (isset($currentFrontEditLink) && sizeof($currentFrontEditLink) > 0)
            <li>
                <a href="{{ $currentFrontEditLink['link'] }}" title="{{ $currentFrontEditLink['title'] }}">
                    {{ $currentFrontEditLink['title'] }}
                </a>
            </li>
        @endif
    </ul>
    <ul class="admin-navbar-nav-right admin-navbar-nav">
        <li class="dropdown">
            <a href="{{ route('user.profile.view', ['id' => Sentinel::getUser()->getUserId()]) }}" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                {{ Sentinel::getUser()->getFullName() }}
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="{{ route('user.profile.view', ['id' => Sentinel::getUser()->getUserId()]) }}">{{ trans('bases::layouts.profile') }}</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ route('access.logout') }}">{{ trans('bases::layouts.logout') }}</a></li>
            </ul>
        </li>
    </ul>
</nav>

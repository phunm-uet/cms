<ul class="navigation">
    @foreach ($menuLeftHand as $menu)
        <li class="nav-item @if (str_contains(Route::currentRouteName(), [substr($menu->route, 0, 10)])) active @endif">
            <a href="@if ($menu->route !== '#' && Route::has($menu->route)) {{ route($menu->route) }} @else # @endif" class="nav-link nav-toggle">
                <i class="{{ $menu->icon }}"></i>
                <span class="title">{{ $menu->name }} {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $menu->route) !!}</span>
                @if (isset($menu->items)) <span class="arrow"></span> @endif
            </a>
            @if (isset($menu->items))
                <ul class="sub-menu">
                    @foreach ($menu->items as $item)
                        <li class="nav-item @if (Route::currentRouteName() == $item->route) active @endif">
                            <a href="@if ($item->route !== '#' && Route::has($item->route)) {{ route($item->route) }} @else # @endif" class="nav-link">
                                <i class="{{ $item->icon }}"></i>
                                {{ $item->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
    @if (Sentinel::getUser()->isSuperUser())
        <li @if (str_contains(Route::currentRouteName(), ['system.'])) class="active" @endif>
            <a href="{{ route('system.options') }}">
                <i class="fa fa-shield"></i>
                <span class="title">{{ trans('bases::layouts.platform_admin') }}</span>
            </a>
        </li>
    @endif
</ul>

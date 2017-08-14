 <header class="header" id="header">
        <div class="header-wrap">
            <nav class="nav-top">
                <div class="container">

                    {!!
                        Menu::generateMenu([
                            'slug' => 'right-menu',
                            'options' => ['class' => 'pull-left'],
                        ])
                    !!}

                    <div class="pull-right">
                        <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
                            <a href="{{ setting('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                            <a href="{{ setting('twitter') }}" title="Twitter" class="hi-icon fa fa-google-plus"></a>
                            <a href="{{ setting('google_plus') }}" title="Google" class="hi-icon fa fa-youtube"></a>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="header-content">
                <div class="container">
                    <h1 class="logo">
                        <a href="{{ url('/') }}" title="{{ setting('site_title') }}">
                            <img src="{{ url(ThemeOption::getOption('logo')) }}" alt="{{ setting('site_title') }}">
                        </a>
                    </h1>
                    <div class="header-content-right">
                        <p><img alt="Banner" src="{{ url(ThemeOption::getOption('top_banner', '/themes/newstv/assets/images/banner.png')) }}" style="width: 728px; height: 90px;"></p>
                    </div>
                </div>
            </div>
        </div>
        <section class="header-hotnews">
            <div class="container">
                <div class="hotnews-content">
                    <h2 class="hotnews-tt">{{ __('Hot of the day') }}</h2>
                    <div class="hotnews-dv">
                        <div class="hotnews-slideshow">
                            <div class="js-marquee">
                                @foreach (get_featured_posts(5) as $feature_item)
                                <a href="{{ route('public.single.detail', $feature_item->slug) }}"
                                   title="{{ $feature_item->name }}">{{ $feature_item->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand"  href="{{ url('/') }}" title="{{ setting('site_title') }}">
                        <img src="{{ url(ThemeOption::getOption('logo')) }}" alt="{{ setting('site_title') }}">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">

                    {!!
                        Menu::generateMenu([
                            'slug' => 'main-menu',
                            'options' => ['class' => 'nav navbar-nav'],
                            'view' => 'main-menu'
                        ])
                    !!}

                    <form class="navbar-form navbar-right" role="search"
                          accept-charset="UTF-8"
                          action="{{ route('public.search') }}"
                          method="GET">
                        <div class="tn-searchtop">
                            <button type="button" class="btn btn-default js-btn-searchtop">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ __('Search news...') }}" name="q">
                            </div>
                        </div>
                        <button id="tn-searchtop" class="js-btn-searchtop" type="button"><i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </header>


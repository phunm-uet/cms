<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="canonical" href="{{ url('/') }}">
        <meta http-equiv="content-language" content="en">
        <link rel="shortcut icon" href="{{ ThemeOption::getOption('logo') }}">

        {!! SeoHelper::render() !!}

        {!! Theme::asset()->styles() !!}
        {!! Theme::asset()->scripts() !!}
    </head>
    <body>
        <div class="wrapper" id="site_wrapper">
            {!! Theme::partial('header') !!}

            <main class="main" id="main">
                <div class="container">
                    <div class="main-content">
                        <div class="main-full">
                            {!! Theme::content() !!}
                        </div>
                    </div>
                </div>
            </main>

            {!! Theme::partial('footer') !!}
        </div>

        {!! Theme::asset()->container('footer')->scripts() !!}

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58b80e5cfacf57001271be31&product=sticky-share-buttons"></script>

        <script>
            $(document).ready(function () {
                $('.banner-slider-wrap').slick({
                    dots: true
                });
            });
        </script>

        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=867766230033521";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

    </body>
</html>

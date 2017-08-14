<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
        <div class="footer-txt">
            <p>
                <a href=".">
                    <img src="{{ url(ThemeOption::getOption('logo')) }}" alt="{{ setting('site_title') }}">
                </a>
            </p>
            <p>{{ setting('site_title') }}</p>
            <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
                <a href="{{ setting('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                <a href="{{ setting('twitter') }}" title="Twitter" class="hi-icon fa fa-google-plus"></a>
                <a href="{{ setting('google_plus') }}" title="Google" class="hi-icon fa fa-youtube"></a>
            </div>
        </div>
    </div>
    <div class="footer-end">
        <div class="container">
            <p>{!! __(ThemeOption::getOption('copyright')) !!}</p>
        </div>
    </div>
</footer>

<div class="theme-panel-wrap">
        <span class="theme-panel-control">
            <i class="fa fa-cogs"></i>
            <i class="fa fa-times"></i>
        </span>
    <div class="theme-panel">
        <div class="theme-options">
            <div class="theme-option theme-colors">
                <h4>THEME COLOR</h4>
                <ul>
                    <li><a href="#" data-style="blue"></a></li>
                    <li><a href="#" data-style="green"></a></li>
                    <li><a href="#" data-style="red"></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
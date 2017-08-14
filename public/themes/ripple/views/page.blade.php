<section data-background="{{ Theme::asset()->url('images/page-intro-02.jpg') }}" class="section page-intro pt-100 pb-100 bg-cover">
    <div style="opacity: 0.7" class="bg-overlay"></div>
    <div class="container">
        <h3 class="page-intro__title">{{ $page->name }}</h3>
        {!! Theme::breadcrumb()->render() !!}
    </div>
</section>
<section class="section pt-50 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-content">
                    <article class="post post--single">
                        <div class="post__content">
                            @if (!empty(gallery_meta_data($page->id, 'page')))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="c-content-media-2-slider" data-slider="owl" data-single-item="true" data-auto-play="4000">
                                            <div class="owl-carousel owl-theme c-theme owl-single">
                                                @foreach (gallery_meta_data($page->id, 'page') as $image)
                                                    <div class="item">
                                                        <div class="c-content-media-2 c-bg-img-center" style="background-image: url('{{ url(array_get($image, 'img')) }}'); min-height: 380px;">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! $page->content !!}
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="page-sidebar">
                    {!! Theme::partial('sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section>


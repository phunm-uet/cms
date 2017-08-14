<section data-background="{{ Theme::asset()->url('images/page-intro-02.jpg') }}" class="section page-intro pt-100 pb-100 bg-cover">
    <div style="opacity: 0.7" class="bg-overlay"></div>
    <div class="container">
        <h3 class="page-intro__title">{{ $post->name }}</h3>
        {!! Theme::breadcrumb()->render() !!}
    </div>
</section>
<section class="section pt-50 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-content">
                    <article class="post post--single">
                        <header class="post__header">
                            <h3 class="post__title">{{ $post->name }}</h3>
                            <div class="post__meta">
                                @if (!$post->categories->isEmpty())
                                    <span class="post-category"><i class="ion-cube"></i>
                                        <a href="{{ route('public.single.detail', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                                    </span>
                                @endif
                                <span class="post__created-at"><i class="ion-clock"></i><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span>
                                <span class="post__author"><i class="ion-android-person"></i><a href="{{ route('public.author', $post->user->username) }}">{{ $post->user->getFullName() }}</a></span>

                                @if (!$post->tags->isEmpty())
                                    <span class="post__tags"><i class="ion-pricetags"></i>
                                        @foreach ($post->tags as $tag)
                                            <a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                        @endforeach
                                    </span>
                                @endif
                            </div>
                            <div class="post__social"></div>
                        </header>
                        <div class="post__content">
                            @if (!empty(gallery_meta_data($post->id, 'post')))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="c-content-media-2-slider" data-slider="owl" data-single-item="true" data-auto-play="4000">
                                            <div class="c-content-label">{{ $post->categories()->first() ? $post->categories()->first()->name : __('Uncategorized') }}</div>
                                            <div class="owl-carousel owl-theme c-theme owl-single">
                                                @foreach (gallery_meta_data($post->id, 'post') as $image)
                                                    @if ($image)
                                                        <div class="item">
                                                            <div class="c-content-media-2 c-bg-img-center" style="background-image: url('{{ url(array_get($image, 'img')) }}'); min-height: 380px;">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! $post->content !!}
                            <div class="fb-like" data-href="{{ Request::url() }}" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                        </div>
                        <footer class="post__footer">
                            <div class="row">
                                @foreach (get_related_posts($post->slug, 2) as $related_item)
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="post__relate-group @if ($loop->last) post__relate-group--right @endif">
                                            <h4 class="relate__title">@if ($loop->first) {{ __('Previous Post') }} @else {{ __('Next Post') }} @endif</h4>
                                            <article class="post post--related">
                                                <div class="post__thumbnail"><a href="{{ route('public.single.detail', $related_item->slug) }}" class="post__overlay"></a>
                                                    <img src="{{ url($related_item->image) }}" alt="{{ $related_item->name }}">
                                                </div>
                                                <header class="post__header"><a href="{{ route('public.single.detail', $related_item->slug) }}" class="post__title"> {{ $related_item->name }}</a></header>
                                            </article>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </footer>
                        <br />
                        @if (is_plugin_active('comment'))
                            <div id="my-comments"></div>
                            {!! render_comment_block('#my-comments', Request::url()) !!}
                        @else
                            <div class="fb-comments" data-href="{{ Request::url() }}" data-numposts="5"></div>
                        @endif
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
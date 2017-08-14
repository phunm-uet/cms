<section class="main-box">
    <div class="main-box-header">
        {!! Theme::breadcrumb()->render() !!}
    </div>
    <div class="main-box-content">
        <h1 class="article-content-title">{{ $post->name }}</h1>

        <div class="post-meta">
            <span><i class="fa fa-user"></i> {{ $post->user->getFullName() }}</span>
            <span><i class="fa fa-calendar"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</span>
            @if (!$post->categories->isEmpty())
                <span>
                    <i class="fa fa-list"></i> <a href="{{ route('public.single.detail', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                </span>
            @endif
        </div>

        <div class="article-content">
            {!! $post->content !!}
        </div>
        @if (!$post->tags->isEmpty())
            <div class="tags-wrap">
                <span>{{ __('Tags') }}: </span>
                <span>
                @foreach ($post->tags as $tag)
                        <a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a>
                    @endforeach
                </span>
            </div>
        @endif
        <div class="share-post">
            <span class="share-text">Share this post</span>
            <div class="share-post-btn btn-tweet">
                <a class="twitter-share-button" data-count="horizontal" data-lang="en" data-related=" "
                   data-text="{{ $post->name }}"
                   data-url="{{ route('public.single.detail', $post->slug) }}"
                   data-via=" " href="http://twitter.com/share" rel="nofollow"></a>
                <script src="http://platform.twitter.com/widgets.js" type="text/javascript">
                </script>
            </div>
            <div class="share-post-btn btn-like">
                <iframe allowTransparency="true" frameborder="0" scrolling="no"
                        src="http://www.facebook.com/plugins/like.php?href={{ route('public.single.detail', $post->slug) }}&send=false&layout=button_count&show_faces=false&width=90&action=like&font=arial&colorscheme=light&height=32"
                        style="border:none; overflow:hidden; width:90px; height:32px;"></iframe>
            </div>
            <div class="share-post-btn btn-plus">
                <script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>
                <g:plusone count="true" href="{{ route('public.single.detail', $post->slug) }}" size="medium"></g:plusone>
            </div>
        </div>
        <div class="comment-post">
            <h4 class="article-content-subtitle">
                {{ __('Comments') }}
            </h4>
            <div class="facebook-comment">
                @if (is_plugin_active('comment'))
                    <div id="my-comments"></div>
                    {!! render_comment_block('#my-comments', Request::url()) !!}
                @else
                    <div class="fb-comments" data-href="{{ Request::url() }}" data-numposts="5"></div>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="main-box">
    <div class="main-box-header">
        <h2><i class="fa fa-leaf"></i> {{ __('Related posts') }}</h2>
    </div>
    <div class="main-box-content">
        <div class="box-style box-style-4">
            @foreach (get_related_posts($post->slug, 6) as $related_item)
                <div class="media-news">
                    <a href="{{ route('public.single.detail', $related_item->slug) }}" title="{{ $related_item->name }}" class="media-news-img">
                        <img class="img-full img-bg" src="{{ get_object_image($related_item->image) }}" style="background-image: url('{{ get_object_image($related_item->image) }}');" alt="{{ $related_item->name }}">
                    </a>
                    <div class="media-news-body">
                        <p class="common-title">
                            <a href="{{ route('public.single.detail', $related_item->slug) }}" title="{{ $related_item->name }}">
                                {{ $related_item->name }}
                            </a>
                        </p>
                        <p class="common-date">
                            <time datetime="">{{ date_from_database($post->created_at, 'M d, Y') }}</time>
                        </p>
                        <div class="common-summary">
                            {{ $related_item->description }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
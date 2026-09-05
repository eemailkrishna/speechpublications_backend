@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/frontend-beauty.css') }}">

<!-- JSON-LD Article Structured Data -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ addslashes($news->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($news->excerpt ?? $news->description), 300)) }}",
  "image": "{{ $news->featured_image ?? asset('images/logo.png') }}",
  "url": "{{ url()->current() }}",
  "datePublished": "{{ $news->publish_date ? $news->publish_date->toIso8601String() : now()->toIso8601String() }}",
  "dateModified": "{{ $news->updated_at ? $news->updated_at->toIso8601String() : now()->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ addslashes($news->author->full_name ?? 'Admin') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Speech Publications",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url()->current() }}"
  },
  "articleSection": "{{ addslashes($news->category->name ?? 'News') }}"
}
</script>

<!-- JSON-LD BreadcrumbList -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    },
    {
      "@@type": "ListItem",
      "position": 2,
      "name": "News",
      "item": "{{ url('/news') }}"
    },
    {
      "@@type": "ListItem",
      "position": 3,
      "name": "{{ addslashes(Str::limit($news->title, 50)) }}",
      "item": "{{ url()->current() }}"
    }
  ]
}
</script>

<style>
    .news-description {
        line-height: 1.8;
    }
    .news-description img {
        max-width: 100%;
        height: auto;
    }
    .news-description figure.image {
        display: table;
        max-width: 100%;
        margin: 1.5rem auto;
        clear: both;
        text-align: center;
    }
    .news-description figure.image img {
        max-width: 100%;
        height: auto;
        display: block;
        border-radius: 8px;
    }
    .news-description figure.image.image_resized img {
        width: 100%;
    }
    .news-description figure.image.image-style-align-left,
    .news-description figure.image.image-style-align-block-left {
        float: left;
        margin: 1rem 1.5rem 1rem 0;
        text-align: left;
    }
    .news-description figure.image.image-style-align-right,
    .news-description figure.image.image-style-align-block-right {
        float: right;
        margin: 1rem 0 1rem 1.5rem;
        text-align: right;
    }
    .news-description figure.image.image-style-align-center {
        margin-left: auto;
        margin-right: auto;
    }
    .news-description figure.image.image-style-side {
        float: right;
        width: 50%;
        margin: 1rem 0 1rem 1.5rem;
    }
    .news-description figure.image figcaption {
        font-size: 14px;
        color: var(--sp-muted);
        margin-top: 8px;
        caption-side: bottom;
    }
    .news-description img.image-style-inline,
    .news-description img.image-style-align-left,
    .news-description img.image-style-align-right {
        border-radius: 8px;
    }
    .news-description img.image-style-align-left {
        float: left;
        margin-right: 1.5rem;
    }
    .news-description img.image-style-align-right {
        float: right;
        margin-left: 1.5rem;
    }

    /* Featured image sizing */
    .featured-image {
        width: 100%;
        height: 450px;
        border-radius: 10px;
        display: block;
    }

    @media (max-width: 768px) {
        .featured-image {
            height: auto !important;
        }
        .news-title {
            font-size: 20px !important;
        }
        .news-description {
            font-size: 14px !important;
        }
        .news-details-area .post-featured-thumb {
            min-height: 0px !important;
        }
    }
</style>

<!-- Breadcumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-paddingn"
    style="background: var(--sp-card);">
    <div class="container">
        <div class="page-heading">
            <h1>News Details</h1>
            <div class="page-header">
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                    <li>
                        <a href="{{url('/')}}">Home</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('news.index') }}">News</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>
                        {{ $news->title }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- News Details Section Start -->
<section class="news-details fix section-padding">
    <div class="container">
        <div class="news-details-area">
            <div class="row g-5">
                <div class="col-xl-9 col-lg-8">
                    <div class="blog-post-details1">
                            <div class="single-blog-post">
                                <img class="post-featured-thumb bg-cover featured-image" src="{{ $news->featured_image ? $news->featured_image : asset('store/assets/img/news/post-4.jpg') }}" alt="">
                            
                            <div class="post-content">
                                <ul class="post-list d-flex align-items-center" style="gap: 18px; flex-wrap: wrap; margin-bottom: 16px;">
                                    <li style="font-size: 13px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-light fa-user" style="color: var(--sp-maroon);"></i>
                                        By {{ $news->author->full_name ?? 'Admin' }}
                                    </li>
                                    <li style="font-size: 13px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-sharp fa-regular fa-comments" style="color: var(--sp-maroon);"></i>
                                        {{ $news->approved_comments_count ?? $comments->total() }} Comments
                                    </li>
                                    <li style="font-size: 13px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-light fa-tag" style="color: var(--sp-maroon);"></i>
                                        {{ $news->category->name ?? 'News' }}
                                    </li>
                                    <li style="font-size: 13px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-light fa-calendar-days" style="color: var(--sp-maroon);"></i>
                                        {{ $news->publish_date ? $news->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                                    </li>
                                </ul>
                                <h3 class="news-title" style="font-size: 26px; font-weight: 700; color: var(--sp-ink); font-family: var(--font-serif);">{{ $news->title }}</h3>
                                <p style="font-size: 15px; line-height: 1.8; color: var(--sp-muted); margin-bottom: 16px;">
                                    {{ Str::limit(strip_tags($news->excerpt ?? $news->description), 300) }}
                                </p>
                               
                                <div class="news-description">
                                    {!! $news->description !!}
                                </div>
                            </div>
                        </div>
                        <div class="row tag-share-wrap">
                            <div class="col-lg-8 col-12">
                                <div class="tagcloud">
                                    <span class="me-3">Tags:</span>
                                    @if($news->category)
                                    <a href="{{ route('news.category', $news->category->slug) }}">{{ $news->category->name }}</a>
                                    @endif
                                    @if($news->author)
                                    <a href="{{ route('news.author', $news->author->slug) }}">{{ $news->author->full_name }}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mt-3 mt-lg-0 text-lg-end">
                                <div class="social-share">
                                    <span class="me-3">Share:</span>
                                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a>
                                    <a target="_blank" href="https://x.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}"><i class="fab fa-twitter"></i></a>
                                    <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>

                        @if($news->author)
                        <div class="row tag-share-wrap mt-3 mb-5">
                            <div class="col-12">
                                <a href="{{ route('news.author', $news->author->slug) }}" class="theme-btn style-2">View All Articles By {{ $news->author->full_name }} <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                        @endif

                        <div class="comments-area">
                            <div class="comments-heading">
                                <h3 style="font-family: var(--font-serif); color: var(--sp-ink);">{{ $comments->total() }} Comments</h3>
                            </div>
                            @forelse($comments as $comment)
                            <div class="blog-single-comment d-flex gap-4 pt-4 pb-5">
                                <div class="image">
                                    <img src="{{ asset('store/assets/img/news/comment.png') }}" alt="image">
                                </div>
                                <div class="content">
                                    <div class="head d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                        <div class="con">
                                            <h5>{{ $comment->name }}</h5>
                                            <span>{{ $comment->created_at->format('F d, Y \a\t g:i a') }}</span>
                                        </div>
                                    </div>
                                    <p class="mt-30 mb-4">{{ $comment->comment }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="mb-4">No comments yet. Be the first to comment!</p>
                            @endforelse
                            <div class="gridjs-footer">
                                {{ $comments->links() }}
                            </div>
                        </div>

                        <div class="comment-form-wrap pt-5">
                            <h3>Leave a comment</h3>
                            @if(session('success'))
                            <div class="alert alert-success" style="border-radius: 8px; font-size: 14px;">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                            <div class="alert alert-danger" style="border-radius: 8px; font-size: 14px;">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <form action="{{ route('news.comment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->id }}">
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="form-clt">
                                            <span>Your Name*</span>
                                            <input type="text" name="name" id="name" placeholder="Your Name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-clt">
                                            <span>Your Email*</span>
                                            <input type="email" name="email" id="email1" placeholder="Your Email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-clt">
                                            <span>Message*</span>
                                            <textarea name="comment" id="message" placeholder="Write your comment..." required>{{ old('comment') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="theme-btn">
                                            Post Comment <i class="fa-solid fa-arrow-right-long"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    @include('news.partials.sidebar-details')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related News Section Start -->
@if($related->count())
<section class="news-section fix section-padding">
    <div class="container">
        <div class="section-title text-center">
            <h2 class="mb-3">Related News</h2>
        </div>
        <div class="row g-4" id="news-grid">
            @foreach($related as $item)
            @include('news.partials.card', ['item' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('layouts.store-footer')

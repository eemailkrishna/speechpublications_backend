@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/frontend-beauty.css') }}">

<style>
    .news-details-area .post-content img {
        max-width: 100%;
        height: auto;
    }
    .news-details-area .post-content figure.image {
        display: table;
        max-width: 100%;
        margin: 1rem auto;
        clear: both;
    }
    .news-details-area .post-content figure.image img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    .news-details-area .post-content figure.image.image_resized img {
        width: 100%;
    }
    .news-details-area .post-content figure.image.image-style-align-left,
    .news-details-area .post-content figure.image.image-style-align-block-left {
        float: left;
        margin-right: 1.5rem;
    }
    .news-details-area .post-content figure.image.image-style-align-right,
    .news-details-area .post-content figure.image.image-style-align-block-right {
        float: right;
        margin-left: 1.5rem;
    }
    .news-details-area .post-content figure.image.image-style-side {
        float: right;
        width: 50%;
        margin-left: 1.5rem;
    }
    .news-details-area .post-content img.image-style-align-left {
        float: left;
        margin-right: 1.5rem;
    }
    .news-details-area .post-content img.image-style-align-right {
        float: right;
        margin-left: 1.5rem;
    }
</style>

<!-- Breadcumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-paddingn "
    style="background: #0b4cff14;">
    <div class="container">
        <div class="page-heading">
            <h1>{{ isset($category) ? $category->name : 'News' }}</h1>
            <div class="page-header">
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                    <li>
                        <a href="{{url('/')}}">
                            Home
                        </a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>
                        {{ isset($category) ? $category->name : 'News' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- News Section Start -->
<section class="news-section fix section-padding">
    <div class="container">
        <div class="row g-4">

            @if(isset($featured) && $featured)
            <div class="col-9">
                <div class="blog-post-details " >
                    <div class="single-blog-post">
                        <a href="{{ route('news.details', $featured->slug) }}" class="post-featured-thumb bg-cover d-block"
                            >
                        
                        <img src="{{ $featured->featured_image ? $featured->featured_image : asset('store/assets/img/news/post-4.jpg') }}" alt="" style="width: 100%; height: 400px; border-radius:20px">
                        </a>
                        <div class="post-content mt-4">
                            <ul class="post-list d-flex align-items-center">
                                <li>
                                    <i class="fa-light fa-user"></i>
                                    By {{ $featured->author->full_name ?? 'Admin' }}
                                </li>
                                <li>
                                    <i class="fa-light fa-tag"></i>
                                    {{ $featured->category->name ?? 'News' }}
                                </li>
                                <li>
                                    <i class="fa-light fa-calendar-days"></i>
                                    {{ $featured->publish_date ? $featured->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                                </li>
                            </ul>
                            <h3>
                                <a href="{{ route('news.details', $featured->slug) }}">{{ $featured->title }}</a>
                            </h3>
                            <p class="mb-3">{!! Str::limit(strip_tags($featured->description), 260) !!}</p>
                            <a href="{{ route('news.details', $featured->slug) }}" class="theme-btn-2">Read More <i
                                    class="fa-regular fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
                  <div class="col-xl-9 col-lg-8 " style="margin-top: 30px;">
                @if($search)
                <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s">
                    <p>Search results for: <strong>{{ $search }}</strong></p>
                </div>
                @endif
                <div class="row g-4" id="news-grid">
                    @forelse($news as $item)
                    @include('news.partials.card', ['item' => $item])
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No news found.</div>
                    </div>
                    @endforelse
                </div>
                <div class="text-center mt-4" id="load-more-wrap" @if(!isset($total) || $total <= $news->count()) style="display:none;" @endif>
                    <button type="button" id="load-more-btn" class="theme-btn style-2"
                        data-offset="{{ $news->count() }}"
                        data-category="{{ isset($category) ? $category->id : ($categoryId ?? '') }}"
                        data-search="{{ $search ?? '' }}">
                        Load More <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            </div>
            </div>
            @endif
             <div class="col-xl-3 col-lg-3">
                @include('news.partials.sidebar')
            </div>

          

           

        </div>
    </div>
</section>

<style>
    .load-more-loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loadMoreBtn = document.getElementById('load-more-btn');
        const newsGrid = document.getElementById('news-grid');
        const loadMoreWrap = document.getElementById('load-more-wrap');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function () {
                const btn = this;
                const offset = btn.getAttribute('data-offset');
                const categoryId = btn.getAttribute('data-category');
                const search = btn.getAttribute('data-search');

                btn.classList.add('load-more-loading');
                btn.innerHTML = 'Loading...';

                let url = "{{ route('news.load-more') }}" + "?offset=" + offset;
                if (categoryId) url += "&category_id=" + categoryId;
                if (search) url += "&search=" + encodeURIComponent(search);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            newsGrid.insertAdjacentHTML('beforeend', data.html);
                        }
                        btn.setAttribute('data-offset', parseInt(offset) + 9);
                        btn.classList.remove('load-more-loading');
                        btn.innerHTML = 'Load More <i class="fa-solid fa-arrow-right-long"></i>';
                        if (!data.hasMore || !data.html) {
                            loadMoreWrap.style.display = 'none';
                        }
                    })
                    .catch(() => {
                        btn.classList.remove('load-more-loading');
                        btn.innerHTML = 'Load More <i class="fa-solid fa-arrow-right-long"></i>';
                    });
            });
        }
    });
</script>

@include('layouts.store-footer')

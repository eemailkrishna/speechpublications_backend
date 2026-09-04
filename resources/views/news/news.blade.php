@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/frontend-beauty.css') }}">

<!-- JSON-LD ItemList for News -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Speech Publications News",
  "url": "{{ url('/news') }}",
  "numberOfItems": {{ is_object($news) ? $news->count() : 0 }},
  "itemListElement": [
    @if(is_object($news))
    @foreach($news->take(9) as $index => $item)
    {
      "@type": "ListItem",
      "position": {{ $index + 1 }},
      "item": {
        "@type": "NewsArticle",
        "headline": "{{ addslashes($item->title) }}",
        "url": "{{ url('/news/'.$item->slug) }}",
        "image": "{{ $item->featured_image ?? asset('images/logo.png') }}",
        "datePublished": "{{ $item->publish_date ? $item->publish_date->toIso8601String() : now()->toIso8601String() }}",
        "author": {
          "@type": "Person",
          "name": "{{ addslashes($item->author->full_name ?? 'Admin') }}"
        }
      }
    }{{ $loop->last ? '' : ',' }}
    @endforeach
    @endif
  ]
}
</script>

<!-- JSON-LD BreadcrumbList -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "News",
      "item": "{{ url('/news') }}"
    }
  ]
}
</script>

<style>
    .news-details-area .post-content img {
        max-width: 100%;
        height: auto;
    }
    @media (max-width: 768px) {
        .news-img { height: 200px !important; }
        .section-padding { padding: 20px 0 !important; }
    }
</style>

<!-- Breadcumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-paddingn"
    style="background: var(--sp-card);">
    <div class="container">
        <div class="page-heading">
            <h1>{{ isset($category) ? $category->name : 'News' }}</h1>
            <div class="page-header">
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                    <li>
                        <a href="{{url('/')}}">Home</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>{{ isset($category) ? $category->name : 'News' }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Category Chip Rail -->
<div class="sp-chip-rail">
    <div class="container">
        <a href="{{ route('news.index') }}" class="sp-chip {{ !isset($categoryId) || !$categoryId ? 'active' : '' }}">All News</a>
        @foreach($categories as $category)
        <a href="{{ route('news.category', $category->slug) }}" class="sp-chip {{ ($categoryId ?? '') == $category->id ? 'active' : '' }}">
            {{ $category->name }}
        </a>
        @endforeach
    </div>
</div>

<!-- News Section Start -->
<section class="news-section fix section-padding">
    <div class="container">
        <div class="row g-4">

            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">

                @if(isset($featured) && $featured)
                <div class="blog-post-details">
                    <div class="single-blog-post">
                        <a href="{{ route('news.details', $featured->slug) }}" class="post-featured-thumb bg-cover d-block">
                            <img src="{{ $featured->featured_image ? $featured->featured_image : asset('store/assets/img/news/post-4.jpg') }}" alt="" style="width: 100%; height: 450px; border-radius: 10px 10px 0 0;" class="news-img">
                        </a>
                        <div class="post-content" style="padding: 20px;">
                            <ul class="post-list d-flex align-items-center" style="gap: 16px; flex-wrap: wrap; margin-bottom: 12px;">
                                <li style="font-size: 12px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-light fa-user" style="color: var(--sp-maroon); font-size: 12px;"></i>
                                    By {{ $featured->author->full_name ?? 'Admin' }}
                                </li>
                                <li style="font-size: 12px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-light fa-tag" style="color: var(--sp-maroon); font-size: 12px;"></i>
                                    {{ $featured->category->name ?? 'News' }}
                                </li>
                                <li style="font-size: 12px; color: var(--sp-muted); display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-light fa-calendar-days" style="color: var(--sp-maroon); font-size: 12px;"></i>
                                    {{ $featured->publish_date ? $featured->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                                </li>
                            </ul>
                            <h3 style="font-size: 22px; font-weight: 600; line-height: 1.35; margin-bottom: 10px;">
                                <a href="{{ route('news.details', $featured->slug) }}" style="color: #1a1008;">{{ $featured->title }}</a>
                            </h3>
                            <p style="font-size: 14px; line-height: 1.7; color: #5a4d3a; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {!! Str::limit(strip_tags($featured->description), 260) !!}
                            </p>
                            
                             <a href="{{ route('news.details', $featured->slug) }}" class="theme-btn" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px;padding:12px!important">
                                    Read More <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- News grid (always visible) -->
                <div style="margin-top: 28px;">
                    @if($search)
                    <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s" style="margin-bottom: 16px;">
                        <p>Search results for: <strong>{{ $search }}</strong></p>
                    </div>
                    @endif
                    <div class="row g-3" id="news-grid">
                        @forelse($news as $item)
                        @include('news.partials.card', ['item' => $item])
                        @empty
                        <div class="col-12 text-center" style="padding: 60px 20px;">
                            <i class="fas fa-newspaper" style="font-size: 48px; color: var(--sp-muted); opacity: .3; margin-bottom: 16px; display: block;"></i>
                            <p style="color: var(--sp-muted); font-weight: 600; font-size: 16px; margin-bottom: 8px;">No news found.</p>
                            <a href="{{ route('news.index') }}" style="color: var(--sp-maroon); font-weight: 600; font-size: 14px;">Clear filters</a>
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

            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-3">
                @include('news.partials.sidebar')
            </div>

        </div>
    </div>
</section>

<!-- Mobile Filter FAB -->
<button class="sp-filter-fab" id="sp-filter-fab">
    <i class="fas fa-sliders-h"></i> Filter
</button>

<!-- Mobile Filter Drawer -->
<div class="sp-filter-drawer-overlay" id="sp-filter-overlay"></div>
<div class="sp-filter-drawer" id="sp-filter-drawer">
    <div class="sp-filter-drawer-header">
        <h3>Filter</h3>
        <button class="sp-filter-drawer-close" id="sp-filter-close"><i class="fas fa-times"></i></button>
    </div>
    <div style="margin-bottom: 16px;">
        <form action="{{ route('news.index') }}" method="GET">
            <div style="position: relative;">
                <input type="text" name="search" placeholder="Search news..." value="{{ $search ?? '' }}" style="width: 100%; padding: 12px 46px 12px 14px; border: 1px solid var(--sp-line); border-radius: 10px; font-size: 13px; font-family: var(--font-ui); background: #fff;">
                <button type="submit" style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 50%; border: none; background: var(--sp-maroon); color: #fff; cursor: pointer;"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--sp-muted); margin-bottom: 12px; font-family: var(--font-ui);">Categories</div>
    <div style="display: flex; flex-direction: column; gap: 2px;">
        <a href="{{ route('news.index') }}" style="display: block; padding: 8px 12px; border-radius: 6px; font-size: 13.5px; font-weight: 500; color: {{ !isset($categoryId) || !$categoryId ? 'var(--sp-maroon)' : 'var(--sp-ink)' }}; {{ !isset($categoryId) || !$categoryId ? 'font-weight: 700;' : '' }} text-decoration: none;">All News</a>
        @foreach($categories as $category)
        <a href="{{ route('news.category', $category->slug) }}" style="display: block; padding: 8px 12px; border-radius: 6px; font-size: 13.5px; font-weight: 500; color: {{ ($categoryId ?? '') == $category->id ? 'var(--sp-maroon)' : 'var(--sp-ink)' }}; {{ ($categoryId ?? '') == $category->id ? 'font-weight: 700;' : '' }} text-decoration: none;">{{ $category->name }}</a>
        @endforeach
    </div>
</div>

<style>
    .load-more-loading { opacity: 0.6; pointer-events: none; }
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

        // Mobile filter drawer
        var fab = document.getElementById('sp-filter-fab');
        var overlay = document.getElementById('sp-filter-overlay');
        var drawer = document.getElementById('sp-filter-drawer');
        var closeBtn = document.getElementById('sp-filter-close');
        function openDrawer() { overlay.classList.add('open'); drawer.classList.add('open'); }
        function closeDrawer() { overlay.classList.remove('open'); drawer.classList.remove('open'); }
        if (fab) fab.addEventListener('click', openDrawer);
        if (overlay) overlay.addEventListener('click', closeDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    });
</script>

@include('layouts.store-footer')

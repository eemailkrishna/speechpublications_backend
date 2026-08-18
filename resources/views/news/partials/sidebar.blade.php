<div class="main-sidebar">
    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h3>Search</h3>
        </div>
        <div class="search-widget">
            <form action="{{ route('news.index') }}" method="GET">
                <input type="text" name="search" placeholder="Search here" value="{{ $search ?? '' }}">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>

    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h3>Categories</h3>
        </div>
        <div class="news-widget-categories">
            <ul>
                @forelse($categories as $category)
                <li class="{{ ($categoryId ?? '') == $category->id ? 'active' : '' }}">
                    <a href="{{ route('news.category', $category->slug) }}">{{ $category->name }}</a>
                    <span>({{ $category->news_count }})</span>
                </li>
                @empty
                <li><a href="javascript:void(0);">No categories yet</a> <span>(0)</span></li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h3>Recent Post</h3>
        </div>
        <div class="recent-post-area">
            @forelse($recentPosts as $post)
            <div class="recent-items">
                <div class="recent-thumb">
                    <img src="{{ $post->featured_image ? $post->featured_image : asset('store/assets/img/news/pp3.jpg') }}" alt="img">
                </div>
                <div class="recent-content">
                    <ul>
                        <li>
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ $post->publish_date ? $post->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                        </li>
                    </ul>
                    <h6>
                        <a href="{{ route('news.details', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </h6>
                </div>
            </div>
            @empty
            <p>No recent posts.</p>
            @endforelse
        </div>
    </div>

    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h3>Trending</h3>
        </div>
        <div class="recent-post-area">
            @forelse($trendingPosts as $post)
            <div class="recent-items">
                <div class="recent-thumb">
                    <img src="{{ $post->featured_image ? $post->featured_image : asset('store/assets/img/news/pp5.jpg') }}" alt="img">
                </div>
                <div class="recent-content">
                    <ul>
                        <li>
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ $post->publish_date ? $post->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                        </li>
                    </ul>
                    <h6>
                        <a href="{{ route('news.details', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </h6>
                </div>
            </div>
            @empty
            <p>No trending posts.</p>
            @endforelse
        </div>
    </div>
</div>

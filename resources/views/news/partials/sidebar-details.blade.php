<div class="main-sidebar">
    @if($news->author)
    <div class="single-sidebar-widget">
        <div class="author-widget text-center">
            <div class="author-thumb mb-4">
                <img src="{{ $news->author->profile_image ? $news->author->profile_image : asset('store/assets/img/team/details.png') }}" alt="{{ $news->author->full_name }}">
            </div>
            <div class="wid-title">
                <h3>{{ $news->author->full_name }}</h3>
                <span>{{ $news->author->designation ?? 'Technology Writer' }}</span>
            </div>
            <p class="mt-3 mb-4">{{ $news->author->bio ? Str::limit($news->author->bio, 120) : 'John writes about technology, AI, and software trends.' }}</p>
            <a href="{{ route('news.author', $news->author->slug) }}" class="theme-btn style-2">View All Articles</a>
        </div>
    </div>
    @endif

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
            <h3>Trending Articles</h3>
        </div>
        <div class="recent-post-area">
            @forelse($trendingPosts as $post)
            <div class="recent-items">
                <div class="recent-thumb">
                    <img src="{{ $post->featured_image ? $post->featured_image : asset('store/assets/img/news/pp5.jpg') }}" alt="{{ $post->title }}">
                </div>
                <div class="recent-content">
                    <ul>
                        <li>
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ $post->publish_date ? $post->publish_date->format('d M, Y') : now()->format('d M, Y') }}
                        </li>
                    </ul>
                    <h6>
                        <a href="{{ route('news.details', $post->slug) }}">{{ $post->title }}</a>
                    </h6>
                </div>
            </div>
            @empty
            <p>No trending posts.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s" style="margin-bottom: 20px; display: flex;">
    <div class="news-card-items style-2 mt-0" style="width: 100%;">
        <div class="news-image">
            <a href="{{ route('news.details', $item->slug) }}">
                <img src="{{ $item->featured_image ? $item->featured_image : asset('store/assets/img/news/post-1.jpg') }}" alt="{{ $item->title }}">
            </a>
            <a href="{{ route('news.details', $item->slug) }}" class="post-box">
                {{ $item->category->name ?? 'News' }}
            </a>
        </div>
        <div class="news-content">
            <ul>
                <li>
                    <i class="fa-light fa-calendar-days"></i>
                    {{ $item->publish_date ? $item->publish_date->format('M d, Y') : now()->format('M d, Y') }}
                </li>
                <li>
                    <i class="fa-regular fa-user"></i>
                    By {{ $item->author->full_name ?? 'Admin' }}
                </li>
            </ul>
            <h3>
                <a href="{{ route('news.details', $item->slug) }}">{{ $item->title }}</a>
            </h3>
            @if($item->excerpt || $item->description)
            <p class="news-card-excerpt">
                {{ Illuminate\Support\Str::limit(strip_tags($item->excerpt ?? $item->description), 120) }}
            </p>
            @endif
            <div style="margin-top: auto;">
                <a href="{{ route('news.details', $item->slug) }}" class="theme-btn" style="display: inline-flex; align-items: center; gap: 6px;">Read More <i class="fa-solid fa-arrow-right-long"></i></a>
            </div>
        </div>
    </div>
</div>

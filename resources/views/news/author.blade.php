@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/frontend-beauty.css') }}">

<!-- Breadcumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-paddingn"
    style="background: var(--sp-card);">
    <div class="container">
        <div class="page-heading">
            <h1>Author Profile</h1>
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
                        {{ $author->full_name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Team Details Section Start -->
<section class="team-details-section fix section-padding" style="background: var(--sp-parchment);">
    <div class="container">
        <div class="team-details-wrapper" style="background: var(--sp-card); border: 1px solid var(--sp-line); border-radius: 12px; padding: 40px;">
            <div class="team-details-items" style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="details-image wow fadeInUp" data-wow-delay=".3s">
                    <img style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--sp-line);" src="{{ $author->profile_image ? $author->profile_image : asset('store/assets/img/team/details.png') }}" alt="{{ $author->full_name }}">
                </div>
                <div class="details-content wow fadeInUp" data-wow-delay=".5s">
                    <h3 style="font-family: var(--font-serif); font-size: 22px; font-weight: 700; color: var(--sp-ink); margin-bottom: 6px;">{{ $author->full_name }}</h3>
                    <span style="font-size: 13px; color: var(--sp-maroon); font-weight: 500;">{{ $author->designation ?? 'Writer' }}</span>
                    <p style="font-size: 13px; color: var(--sp-muted); margin-top: 4px;">{{ $author->location ?? '' }}</p>
                    @if($author->social_profiles)
                    <div class="social-icon d-flex align-items-center" style="gap: 10px; margin-top: 10px;">
                        @if($author->facebook_url)<a href="{{ $author->facebook_url }}" target="_blank" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--sp-line); display: inline-flex; align-items: center; justify-content: center; color: var(--sp-muted); text-decoration: none; transition: all 0.3s;"><i class="fab fa-facebook-f" style="font-size: 13px;"></i></a>@endif
                        @if($author->twitter_url)<a href="{{ $author->twitter_url }}" target="_blank" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--sp-line); display: inline-flex; align-items: center; justify-content: center; color: var(--sp-muted); text-decoration: none; transition: all 0.3s;"><i class="fab fa-twitter" style="font-size: 13px;"></i></a>@endif
                        @if($author->linkedin_url)<a href="{{ $author->linkedin_url }}" target="_blank" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--sp-line); display: inline-flex; align-items: center; justify-content: center; color: var(--sp-muted); text-decoration: none; transition: all 0.3s;"><i class="fab fa-linkedin-in" style="font-size: 13px;"></i></a>@endif
                        @if($author->instagram_url)<a href="{{ $author->instagram_url }}" target="_blank" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--sp-line); display: inline-flex; align-items: center; justify-content: center; color: var(--sp-muted); text-decoration: none; transition: all 0.3s;"><i class="fab fa-instagram" style="font-size: 13px;"></i></a>@endif
                    </div>
                    @endif
                </div>
            </div>
            @if($author->bio)
            <p class="wow fadeInUp" data-wow-delay=".7s" style="font-size: 14px; line-height: 1.8; color: var(--sp-muted); margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--sp-line);">{{ $author->bio }}</p>
            @endif
            <div class="details-counter-area" style="display: flex; gap: 40px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--sp-line);">
                <div class="counter-items wow fadeInUp" data-wow-delay=".3s">
                    <h2 style="font-family: var(--font-serif); font-size: 28px; font-weight: 700; color: var(--sp-maroon);"><span class="count">{{ $articles->total() }}</span>+</h2>
                    <p style="font-size: 13px; color: var(--sp-muted);">Articles</p>
                </div>
                @if($author->specialization)
                <div class="counter-items wow fadeInUp" data-wow-delay=".5s">
                    <p style="font-size: 12px; color: var(--sp-ink); font-weight: 500; max-width: 300px;">{{ $author->specialization }}</p>
                    <p style="font-size: 13px; color: var(--sp-muted);">Specialization</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Articles Section Start -->
<section class="news-section fix section-padding pt-0">
    <div class="container">
        <div class="section-title text-center">
            <h2 class="mb-3">Articles By {{ $author->full_name }}</h2>
        </div>
        <div class="row g-4" id="news-grid">
            @forelse($articles as $item)
            @include('news.partials.card', ['item' => $item])
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No articles published by this author yet.</div>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</section>

@include('layouts.store-footer')

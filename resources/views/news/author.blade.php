@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/frontend-beauty.css') }}">

<!-- Breadcumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-padding"
    style="background: #0b4cff14;">
    <div class="container">
        <div class="page-heading">
            <h1>Author Profile</h1>
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
<section class="team-details-section fix section-padding">
    <div class="container">
        <div class="team-details-wrapper">
            <div class="team-details-items">
                <div class="details-image wow fadeInUp" data-wow-delay=".3s">
                    <img style="max-width: 182px;
    border-radius: 50%;" src="{{ $author->profile_image ? $author->profile_image : asset('store/assets/img/team/details.png') }}" alt="{{ $author->full_name }}">
                </div>
                <div class="details-content wow fadeInUp" data-wow-delay=".5s">
                    <h3>Author: {{ $author->full_name }}</h3>
                    <span>{{ $author->location ?? 'Location not specified' }}</span>
                    <p class="mt-3 mb-2">{{ $author->designation }}</p>
                    @if($author->social_profiles)
                    <div class="social-icon d-flex align-items-center">
                        @if($author->facebook_url)<a href="{{ $author->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                        @if($author->twitter_url)<a href="{{ $author->twitter_url }}" target="_blank"><i class="fab fa-twitter"></i></a>@endif
                        @if($author->linkedin_url)<a href="{{ $author->linkedin_url }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if($author->instagram_url)<a href="{{ $author->instagram_url }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                    @endif
                </div>
            </div>
            @if($author->bio)
            <p class="wow fadeInUp" data-wow-delay=".7s">{{ $author->bio }}</p>
            @endif
            <div class="details-counter-area">
                <div class="counter-items wow fadeInUp" data-wow-delay=".3s">
                    <h2><span class="count">{{ $articles->total() }}</span>+</h2>
                    <p>Articles</p>
                </div>
                @if($author->specialization)
                <div class="counter-items wow fadeInUp" data-wow-delay=".5s">
                    <p class="mt-3"><strong>{{ $author->specialization }}</strong></p>
                    <p>Specialization</p>
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

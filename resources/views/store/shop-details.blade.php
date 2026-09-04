@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/store-beauty.css') }}">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- JSON-LD Product Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ addslashes($product->name) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($product->description ?? $product->heading ?? ''), 300)) }}",
  "image": "{{ $imageUrl }}",
  "url": "{{ url()->current() }}",
  "brand": {
    "@type": "Brand",
    "name": "Speech Publications"
  },
  "author": {
    "@type": "Person",
    "name": "{{ addslashes($product->author_name ?? 'Speech Publications') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Speech Publications",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "offers": {
    "@type": "Offer",
    "price": "{{ $product->price }}",
    "priceCurrency": "INR",
    "availability": "{{ $product->status == 'launched' ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder' }}",
    "url": "{{ url()->current() }}"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $product->rating ?? 5 }}",
    "bestRating": "5",
    "worstRating": "1"
  }
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
      "name": "Shop",
      "item": "{{ url('/store') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ addslashes(Str::limit($product->name, 50)) }}",
      "item": "{{ url()->current() }}"
    }
  ]
}
</script>

<style>
  .wow { visibility: visible !important; opacity: 1 !important; transform: none !important; }
  @media (max-width: 991px) {
    .footer-section .footer-widget-wrapper { display: none; }
    .footer-section .footer-bottom { padding: 16px 0 !important; }
  }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper" style="background: var(--sp-card); border-bottom: 1px solid var(--sp-line); padding: 18px 0;">
  <div class="container">
    <div class="page-heading">
      <h1 style="color: var(--sp-ink); font-weight: 600; font-size: 24px; font-family: var(--font-serif); margin-bottom: 4px;">{{ $product->name }}</h1>
      <div class="page-header">
        <ul class="breadcrumb-items" style="display: flex; gap: 8px; list-style: none; padding: 0; margin: 0;">
          <li><a href="{{ url('/') }}" style="color: var(--sp-maroon); font-weight: 500; font-size: 13px; text-decoration: none;">Home</a></li>
          <li><i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--sp-muted);"></i></li>
          <li><a href="{{ url('/store') }}" style="color: var(--sp-maroon); font-weight: 500; font-size: 13px; text-decoration: none;">Shop</a></li>
          <li><i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--sp-muted);"></i></li>
          <li style="color: var(--sp-muted); font-size: 13px;">{{ Str::limit($product->name, 50) }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>

@php
  $images = json_decode($product->image, true) ?? [];
  $imageUrl = !empty($images) && isset($images[0])
      ? Storage::disk('s3')->url('product/'.$images[0])
      : asset('images/no-image.png');
@endphp

<!-- Book Details Section -->
<section class="sp-details-section">
  <div class="container">
    <div class="sp-details-wrapper">

      <!-- Gallery -->
      <div class="col-lg-4" >
        <div class="sp-details-gallery">
          <div class="sp-details-main-img" id="sp-main-img-wrap">
            <img id="sp-main-img" src="{{ $imageUrl }}" alt="{{ $product->name }}">
          </div>
          @if(count($images) > 1)
          <div class="sp-details-thumbs">
            @foreach($images as $index => $img)
            <div class="sp-details-thumb {{ $loop->first ? 'active' : '' }}" onclick="spSwapImage('{{ Storage::disk('s3')->url('product/'.$img) }}', this)">
              <img src="{{ Storage::disk('s3')->url('product/'.$img) }}" alt="thumb">
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      <!-- Info -->
      <div class="col-lg-8" style="flex: 1;">
        <div class="sp-details-info">
          <h1 class="sp-details-title">{{ $product->name }}</h1>

          @if($product->heading)
          <p class="sp-details-subtitle">{{ $product->heading }}</p>
          @endif

          @if($product->rating)
          <div class="sp-details-rating">
            @for($i = 1; $i <= 5; $i++)
              @if($i <= $product->rating)
                <i class="fa-solid fa-star"></i>
              @else
                <i class="fa-regular fa-star"></i>
              @endif
            @endfor
            <span>({{ $product->rating }})</span>
          </div>
          @endif

          @if($product->author_name)
          <div class="sp-details-author">
            by <strong>{{ $product->author_name }}</strong>
          </div>
          @endif

          <!-- Price Row -->
          <div class="sp-details-price-row">
            <div class="sp-details-price" id="sp-price-display">₹{{ $product->ebook_price ?? $product->price }}</div>
          </div>

          @if($product->is_ebook == 1)
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              window.spPriceData = {
                ebook: {{ $product->ebook_price ?? $product->price }},
                physical: {{ $product->price }},
                current: {{ $product->ebook_price ?? $product->price }}
              };
            });
          </script>
          <div class="sp-details-type-toggle">
            <label>
              <input type="radio" name="sp_product_type" value="ebook" checked onchange="spToggleType()"> E-Book
            </label>
            <label>
              <input type="radio" name="sp_product_type" value="physical" onchange="spToggleType()"> Physical Book
            </label>
          </div>
          @endif

          @if($product->status != 'coming-soon')
            @if($product->is_ebook == 1)
              <!-- eBook Section -->
              <div id="sp-ebook-section">
                @php
                  $hasPurchased = false;
                  $downloadRoute = null;
                  if(Auth::check()) {
                      $order = \App\Models\Order::where('user_id', Auth::id())
                          ->whereNotNull('razorpay_payment_id')
                          ->whereHas('items', function($q) use ($product) {
                              $q->where('product_id', $product->id);
                          })->with('items')->latest()->first();
                      if($order) {
                          $item = $order->items->firstWhere('product_id', $product->id);
                          if($item) {
                              $hasPurchased = true;
                              $downloadRoute = route('view.ebook', ['orderId' => $order->id, 'itemId' => $item->id]);
                          }
                      }
                  }
                @endphp
                @if($hasPurchased && $downloadRoute)
                  <button type="button" class="sp-details-ebook-btn view-ebook" data-bs-toggle="modal" data-bs-target="#pdfModalStore" onclick="viewPdf('{{ $downloadRoute }}', '{{ addslashes($product->name) }}')">View eBook</button>
                @else
                  <button type="button" class="sp-details-ebook-btn" onclick="spBuyNowEbook({{ $product->id }}, {{ $product->ebook_price ?? $product->price }})">Buy eBook</button>
                @endif
              </div>

              <!-- Physical Section -->
              <div id="sp-physical-section" style="display: none;">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                  @csrf
                  <input type="hidden" name="quantity" id="sp-qty-hidden" value="{{ Auth::user() ? ($cartItems->quantity ?? 1) : ($cartItems[$product->id]['quantity'] ?? 1) }}">
                  <div class="sp-details-qty">
                    <button type="button" onclick="spDecreaseQty()">−</button>
                    <input type="number" id="sp-qty" min="1" max="10" value="{{ Auth::user() ? ($cartItems->quantity ?? 1) : ($cartItems[$product->id]['quantity'] ?? 1) }}" oninput="document.getElementById('sp-qty-hidden').value=this.value">
                    <button type="button" onclick="spIncreaseQty()">+</button>
                  </div>
                  <button type="submit" class="sp-btn-cart" style="border:none; cursor:pointer;">Add to Cart</button>
                  <button type="submit" class="sp-btn-bynow" style="cursor:pointer;">Buy Now</button>
                </form>
              </div>
            @else
              <!-- Physical Only -->
              <div>
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                  @csrf
                  <input type="hidden" name="quantity" id="sp-qty-hidden2" value="{{ Auth::user() ? ($cartItems->quantity ?? 1) : ($cartItems[$product->id]['quantity'] ?? 1) }}">
                  <div class="sp-details-qty">
                    <button type="button" onclick="spDecreaseQty()">−</button>
                    <input type="number" id="sp-qty" min="1" max="10" value="{{ Auth::user() ? ($cartItems->quantity ?? 1) : ($cartItems[$product->id]['quantity'] ?? 1) }}" oninput="document.getElementById('sp-qty-hidden2').value=this.value">
                    <button type="button" onclick="spIncreaseQty()">+</button>
                  </div>
                  <button type="submit" class="sp-btn-cart" style="border:none; cursor:pointer;">Add to Cart</button>
                  <button type="submit" class="sp-btn-bynow" style="cursor:pointer;">Buy Now</button>
                </form>
              </div>
            @endif
          @else
            <button type="button" class="sp-details-coming-soon" disabled>Coming Soon</button>
          @endif

          <!-- Meta Strip -->
          @php
            $metaItems = [];
            if($product->specification) {
              preg_match_all('/<td[^>]*>(.*?)<\/td>/s', $product->specification, $tds);
              $specLabels = ['Language', 'Subject', 'Category', 'Pages', 'Edition', 'Paper Quality', 'Publication Year', 'Contributors', 'Publisher'];
              foreach($tds[1] as $i => $td) {
                $val = strip_tags(trim($td));
                if(in_array($val, $specLabels) && isset($tds[1][$i+1])) {
                  $metaItems[] = strip_tags(trim($tds[1][$i+1]));
                }
              }
            }
          @endphp
          @if(count($metaItems) > 0)
          <div class="sp-details-meta">
            @foreach($metaItems as $mi => $meta)
              <span>{{ $meta }}</span>
              @if($mi < count($metaItems) - 1)
                <span class="sp-meta-sep">·</span>
              @endif
            @endforeach
          </div>
          @endif

        </div>
      </div>

    </div>

    <!-- Tabs -->
    <div class="sp-details-tabs">
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sp-desc" type="button">Description</button>
        </li>
        @if($product->specification)
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sp-additional" type="button">Additional Information</button>
        </li>
        @endif
      </ul>
    </div>
    <div class="sp-details-tab-content tab-content">
      <div id="sp-desc" class="tab-pane fade show active">
        <div style="font-size: 14.5px; line-height: 1.8; color: var(--sp-ink);">
          {!! $product->description !!}
        </div>
      </div>
      @if($product->specification)
      <div id="sp-additional" class="tab-pane fade">
        @php
          preg_match_all('/<tr>(.*?)<\/tr>/s', $product->specification, $rows);
        @endphp
        <table class="sp-details-spec-table">
          <tbody>
            @foreach($rows[0] as $row)
              {!! $row !!}
            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>

  </div>
</section>

<!-- Comments Section -->
<section class="sp-details-section" style="padding: 40px 0 20px;">
  <div class="container">
    <div class="sp-details-tabs">
      <div class="comments-heading">
        <h3 style="font-family: var(--font-serif); color: var(--sp-ink); font-size: 20px; margin-bottom: 20px;">{{ $commentsCount }} Comments</h3>
      </div>

      @forelse($comments as $comment)
      <div class="blog-single-comment d-flex gap-4 pt-4 pb-4" style="border-bottom: 1px solid var(--sp-line);">
        <div class="image" style="flex-shrink: 0;">
          <img src="{{ asset('store/assets/img/news/comment.png') }}" alt="avatar" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
        </div>
        <div class="content" style="flex: 1;">
          <div class="head d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <div class="con">
              <h5 style="font-size: 15px; font-weight: 600; color: var(--sp-ink); margin-bottom: 2px;">{{ $comment->name }}</h5>
              <span style="font-size: 12px; color: var(--sp-muted);">{{ $comment->created_at->format('F d, Y \a\t g:i a') }}</span>
            </div>
            @if($comment->rating)
            <div style="display: flex; gap: 2px;">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= $comment->rating)
                  <i class="fa-solid fa-star" style="font-size: 12px; color: var(--sp-marigold);"></i>
                @else
                  <i class="fa-regular fa-star" style="font-size: 12px; color: var(--sp-marigold);"></i>
                @endif
              @endfor
            </div>
            @endif
          </div>
          <p class="mt-3 mb-0" style="font-size: 14px; line-height: 1.7; color: var(--sp-ink);">{{ $comment->comment }}</p>
        </div>
      </div>
      @empty
      <p class="mb-4" style="color: var(--sp-muted); font-size: 14px;">No comments yet. Be the first to comment!</p>
      @endforelse

      <div class="gridjs-footer" style="margin-top: 20px;">
        {{ $comments->links() }}
      </div>
    </div>

    <!-- Comment Form -->
    <div class="comment-form-wrap pt-5" style="max-width: 700px;">
      <h3 style="font-family: var(--font-serif); color: var(--sp-ink); font-size: 20px; margin-bottom: 20px;">Leave a comment</h3>
      @if(session('success'))
      <div style="padding: 10px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 13px; color: #166534; margin-bottom: 16px;">{{ session('success') }}</div>
      @endif
      @if($errors->any())
      <div style="padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; font-size: 13px; color: #991b1b; margin-bottom: 16px;">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <form action="{{ route('product.comment') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="form-clt">
              <span style="font-size: 13px; font-weight: 500; color: var(--sp-ink); display: block; margin-bottom: 6px;">Your Name*</span>
              <input type="text" name="name" placeholder="Your Name" value="{{ old('name', auth()->user()->name ?? '') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--sp-line); border-radius: 8px; font-size: 14px; font-family: var(--font-ui);">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-clt">
              <span style="font-size: 13px; font-weight: 500; color: var(--sp-ink); display: block; margin-bottom: 6px;">Your Email*</span>
              <input type="email" name="email" placeholder="Your Email" value="{{ old('email', auth()->user()->email ?? '') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--sp-line); border-radius: 8px; font-size: 14px; font-family: var(--font-ui);">
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-clt">
              <span style="font-size: 13px; font-weight: 500; color: var(--sp-ink); display: block; margin-bottom: 6px;">Rating*</span>
              <div id="sp-rating-input" style="display: flex; gap: 4px;">
                @for($i = 1; $i <= 5; $i++)
                <i class="fa-regular fa-star" style="font-size: 20px; color: var(--sp-marigold); cursor: pointer;" data-rating="{{ $i }}" onclick="spSetRating({{ $i }})"></i>
                @endfor
              </div>
              <input type="hidden" name="rating" id="sp-rating-value" value="5">
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-clt">
              <span style="font-size: 13px; font-weight: 500; color: var(--sp-ink); display: block; margin-bottom: 6px;">Message*</span>
              <textarea name="comment" placeholder="Write your comment..." required rows="4" style="width: 100%; padding: 10px 14px; border: 1px solid var(--sp-line); border-radius: 8px; font-size: 14px; font-family: var(--font-ui); resize: vertical;">{{ old('comment') }}</textarea>
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
</section>

<!-- Related Products -->
@if(count($relatedProduct) > 0)
<section class="sp-details-related">
  <div class="container">
    <h2>Related Products</h2>
    <div class="row g-3">
      @foreach($relatedProduct as $singleProduct)
        @php
          $rImages = json_decode($singleProduct->image, true) ?? [];
          $rImageUrl = !empty($rImages) && isset($rImages[0])
              ? Storage::disk('s3')->url('product/'.$rImages[0])
              : asset('images/no-image.png');
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6" style="display: flex; margin-bottom: 20px;">
          <div class="shop-box-items" style="width: 100%;">
            <div class="book-thumb center">
              <a href="{{ url('/book-details/'.$singleProduct->slug) }}">
                <img src="{{ $rImageUrl }}" alt="{{ $singleProduct->name }}">
              </a>
              @if($singleProduct->category)
              <span class="sp-ribbon">{{ $singleProduct->category->name }}</span>
              @endif
              @if($singleProduct->status == 'coming-soon')
              <div class="sp-coming-soon-overlay"><span>Coming Soon</span></div>
              @endif
              @if($singleProduct->is_ebook == 1 && $singleProduct->status != 'coming-soon')
              <span class="badge badge-success" style="position:absolute; left:12px; bottom:12px;">E-Book</span>
              @endif
            </div>
            <div class="shop-content">
              <h3 class="product-title-css">
                <a href="{{ url('/book-details/'.$singleProduct->slug) }}">{{ $singleProduct->name }}</a>
              </h3>
              @if($singleProduct->author_name)
              <p class="product-title-css" style="color:var(--sp-muted); font-size:12px; margin:0 0 6px;">{{ $singleProduct->author_name }}</p>
              @endif
              <ul class="price-list1">
                <li>₹{{ $singleProduct->price }}/-</li>
              </ul>
              <div class="shop-button" style="padding-bottom: 0;">
                @if($singleProduct->status != 'coming-soon')
                  @if($singleProduct->is_ebook == 1)
                    <button type="button" onclick="spBuyNowEbook({{ $singleProduct->id }}, {{ $singleProduct->ebook_price ?? $singleProduct->price }})" class="theme-btn">Buy eBook</button>
                  @else
                    <form action="{{ route('cart.add', $singleProduct->id) }}" method="POST" style="display:inline;">
                      @csrf
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="theme-btn">Add To Cart</button>
                    </form>
                  @endif
                @else
                  <button type="button" class="theme-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Coming Soon</button>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- Mobile Sticky Buy Bar -->
@if($product->status != 'coming-soon')
<div class="sp-details-mobile-buy" id="sp-mobile-buy">
  <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display: flex; width: 100%; gap: 8px;">
    @csrf
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="sp-btn-cart">Add to Cart</button>
    <button type="submit" class="sp-btn-bynow">Buy Now</button>
  </form>
</div>
@endif

<!-- PDF Modal -->
<div class="modal fade" id="pdfModalStore" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pdfModalStoreLabel">View eBook</h5>
        <div class="btn-group me-2" role="group">
          <button type="button" class="btn btn-sm btn-secondary" id="fullscreenBtn">Fullscreen</button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height: 80vh; padding: 0;">
        <div id="pdfContainer" style="width:100%;height:100%;">
          <iframe id="pdfIframeStore" src="" width="100%" height="100%" style="border: none;" oncontextmenu="return false;"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Gallery swap
function spSwapImage(src, thumb) {
  document.getElementById('sp-main-img').src = src;
  document.querySelectorAll('.sp-details-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
}

// Toggle ebook/physical
function spToggleType() {
  var type = document.querySelector('input[name="sp_product_type"]:checked').value;
  var ebookSection = document.getElementById('sp-ebook-section');
  var physicalSection = document.getElementById('sp-physical-section');
  var priceDisplay = document.getElementById('sp-price-display');

  if (type === 'ebook') {
    if (ebookSection) ebookSection.style.display = '';
    if (physicalSection) physicalSection.style.display = 'none';
    if (priceDisplay && window.spPriceData) {
      priceDisplay.textContent = '₹' + window.spPriceData.ebook;
    }
  } else {
    if (ebookSection) ebookSection.style.display = 'none';
    if (physicalSection) physicalSection.style.display = '';
    if (priceDisplay && window.spPriceData) {
      priceDisplay.textContent = '₹' + window.spPriceData.physical;
    }
  }
}

// Qty
function spDecreaseQty() {
  var input = document.getElementById('sp-qty');
  var val = parseInt(input.value);
  if (val > 1) {
    input.value = val - 1;
    var h1 = document.getElementById('sp-qty-hidden');
    var h2 = document.getElementById('sp-qty-hidden2');
    if (h1) h1.value = input.value;
    if (h2) h2.value = input.value;
  }
}

function spIncreaseQty() {
  var input = document.getElementById('sp-qty');
  var val = parseInt(input.value);
  if (val < 10) {
    input.value = val + 1;
    var h1 = document.getElementById('sp-qty-hidden');
    var h2 = document.getElementById('sp-qty-hidden2');
    if (h1) h1.value = input.value;
    if (h2) h2.value = input.value;
  }
}

// Buy ebook
function spBuyNowEbook(productId, price) {
  @if(Auth::check())
    var finalPrice = (window.spPriceData && window.spPriceData.ebook) ? window.spPriceData.ebook : price;
    var quantity = 1;
    var qtyInput = document.getElementById('sp-qty');
    if (qtyInput) {
      quantity = parseInt(qtyInput.value);
      if (isNaN(quantity) || quantity <= 0) quantity = 1;
    }
    var total = finalPrice * quantity;
    var platformFee = 7;
    var finalAmount = total + platformFee;

    var token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;

    fetch('{{ route("checkout.razorpay.create") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        first_name: '{{ Auth::user()->name }}',
        last_name: 'eBook Purchase',
        country: 'India',
        address: 'Digital Product',
        city: 'Online',
        phone: '{{ Auth::user()->phone ?? "" }}',
        email: '{{ Auth::user()->email }}',
        shipping_method: 'free',
        product_id: productId,
        quantity: quantity,
        price: finalPrice,
        is_ebook_direct: true
      })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.error) { alert('Error: ' + data.error); return; }
      var options = {
        key: data.razorpay_key_id,
        amount: data.amount,
        currency: 'INR',
        order_id: data.razorpay_order_id,
        name: 'Speech Publications',
        description: 'eBook Purchase',
        image: '{{ asset("images/logo.png") }}',
        handler: function(response) {
          spVerifyEbookPayment(response, data.order_id);
        },
        prefill: {
          name: '{{ Auth::user()->name }}',
          email: '{{ Auth::user()->email }}',
          contact: '{{ Auth::user()->phone ?? "" }}'
        },
        theme: { color: '#7c2a2a' }
      };
      var rzp = new Razorpay(options);
      rzp.on('payment.failed', function(response) { alert('Payment failed: ' + response.error.reason); });
      rzp.open();
    })
    .catch(function(error) { console.error(error); alert('An error occurred. Please try again.'); });
  @else
    window.location.href = '{{ route("login") }}';
  @endif
}

function spVerifyEbookPayment(response, orderId) {
  var token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
  fetch('{{ route("checkout.razorpay.verify") }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': token,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      razorpay_order_id: response.razorpay_order_id,
      razorpay_payment_id: response.razorpay_payment_id,
      razorpay_signature: response.razorpay_signature,
      order_id: orderId
    })
  })
  .then(function(response) { return response.json(); })
  .then(function(data) {
    if (data.success) window.location.href = data.redirect;
    else alert('Payment verification failed: ' + data.error);
  })
  .catch(function(error) { console.error(error); alert('An error occurred during payment verification.'); });
}

// PDF Modal
function viewPdf(url, title) {
  var label = document.getElementById('pdfModalStoreLabel');
  var iframe = document.getElementById('pdfIframeStore');
  if (label) label.innerText = title;
  if (iframe) iframe.src = url + '#toolbar=0&navpanes=0&scrollbar=0';
}

// Rating
function spSetRating(val) {
  document.getElementById('sp-rating-value').value = val;
  document.querySelectorAll('#sp-rating-input i').forEach(function(star) {
    var v = parseInt(star.getAttribute('data-rating'));
    star.className = v <= val ? 'fa-solid fa-star' : 'fa-regular fa-star';
  });
}

document.addEventListener('DOMContentLoaded', function() {
  var fullscreenBtn = document.getElementById('fullscreenBtn');
  var pdfContainer = document.getElementById('pdfContainer');
  var pdfIframe = document.getElementById('pdfIframeStore');

  if (fullscreenBtn) {
    fullscreenBtn.addEventListener('click', function() {
      var el = pdfContainer || pdfIframe;
      if (!el) return;
      if (!document.fullscreenElement) {
        if (el.requestFullscreen) el.requestFullscreen();
        else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
        else if (el.msRequestFullscreen) el.msRequestFullscreen();
      } else {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
        else if (document.msExitFullscreen) document.msExitFullscreen();
      }
    });
  }
});
</script>

@include('layouts.store-footer')

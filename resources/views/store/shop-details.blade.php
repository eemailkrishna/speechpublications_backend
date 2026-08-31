  @include('layouts.store-header')
  
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<style>
    .product-title-css {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (min-width: 768px) {
        .product-title {
            font-size: 31px !important;
        }
    }

    @media (max-width: 767px) {
        .product-title {
            font-size: 22px !important;
        }
        .shop-details-content .star span {
            font-size: 11px !important;
        }
        .shop-details-content p {
            font-size: 13px !important;
        }
        .shop-details-content .title-wrapper h5 {
            font-size: 17px !important;
        }
        .price-list h3 {
            font-size: 26px !important;
        }
        .single-tab .nav .nav-link h6 {
            font-size: 15px !important;
        }
        .description-items p {
            font-size: 13px !important;
        }
        .shop-details-content .star a {
            font-size: 13px !important;
        }
    }

    @media (max-width: 575px) {
        .theme-btn {
            padding: 18px 30px;
            font-size: 11px !important;
        }
        .shop-details-wrapper .shop-details-content .cart-wrapper .quantity-basket .qty {
            padding: 0px 30px !important;
        }
    }

    @media (max-width: 991px) {
        .section-padding {
            padding: 27px 0 !important;
        }
    }
</style>
    <!-- Breadcumb Section Start -->
    <div class="breadcrumb-wrapper bg-cover section-padding"
        style="background-image: url(assets/img/hero/breadcrumb-bg.jpg);">
        <div class="container">
            <div class="page-heading">
                <h1>Shop Details</h1>
                <div class="page-header">
                    <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                        <li>
                            <a href="index.html">
                                Home
                            </a>
                        </li>
                        <li>
                            <i class="fa-solid fa-chevron-right"></i>
                        </li>
                        <li>
                            Book Details
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Details Section Start -->
    <section class="shop-details-section fix section-padding">
        <div class="container">
            <div class="shop-details-wrapper">
                <div class="row g-4">
                    <div class="col-lg-5">
                        @php
                            
                                                $images = json_decode($product->image, true) ?? [];
                                                $imageUrl = !empty($images) && isset($images[0])
                                                    ? Storage::disk('s3')->url('product/'.$images[0])
                                                    : asset('images/no-image.png'); // fallback image
                                            @endphp
                   
                        
                        <div class="shop-details-image">
                            <div class="tab-content">
                        
                                @foreach($images as $index => $img)
                                <div id="thumb{{ $index+1 }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}">
                                    <div class="shop-details-thumb">
                                        <img style="max-width:300px" src="{{ Storage::disk('s3')->url('product/'.$img) }}" alt="img">
                                    </div>
                                </div>
                                @endforeach
                        
                            </div>
                        
                            <ul class="nav">
                                @foreach($images as $index => $img)
                                <li class="nav-item">
                                    <a href="#thumb{{ $index+1 }}" data-bs-toggle="tab" class="nav-link {{ $loop->first ? 'active' : '' }}">
                                        <img style="width:50px" src="{{Storage::disk('s3')->url('product/'.$img)}}" alt="img">
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="col-lg-7">
                        <div class="shop-details-content">
                            <div  class="title-wrapper1">
                                <h2 class="product-title">{{@$product->name}}</h2>
                              
                            </div>
                            <div class="star">
                                <a href="#"> <i class="fas fa-star"></i></a>
                                <a href="#"><i class="fas fa-star"></i></a>
                                <a href="#"> <i class="fas fa-star"></i></a>
                                <a href="#"><i class="fas fa-star"></i></a>
                                <a href="#"><i class="fa-regular fa-star"></i></a>
                                <span>( Customer Reviews)</span>
                            </div>
                            <p>
                                {{$product->heading}}
                            </p>
                            <div class="price-list">
                                <h3 id="priceDisplay">₹{{$product->ebook_price ?? $product->price}}/-</h3>
                            </div>
                            @if($product->is_ebook == 1)
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var ebookPrice = {{ $product->ebook_price ?? $product->price }};
                                        var physicalPrice = {{ $product->price }};
                                        window.priceData = {
                                            ebook: ebookPrice,
                                            physical: physicalPrice,
                                            current: ebookPrice
                                        };
                                        // Initialize price display
                                        document.getElementById('priceDisplay').textContent = '₹' + ebookPrice + '/-';
                                    });
                                </script>
                            @endif
                          
                            <div class="cart-wrapper">
                                @if($product->is_ebook == 1)
                                    <div class="product-type-options1 mb-4 d-flex gap-3">
                                        <label class="form-check-label">
                                            <input type="radio" name="product_type" value="ebook" checked onchange="toggleProductType({{ $product->id }})" class="form-check-input"> Ebook
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" name="product_type" value="physical" onchange="toggleProductType({{ $product->id }})" class="form-check-input"> Physical Book
                                        </label>
                                    </div>
                                @endif
</div>
<div class="cart-wrapper">
                                   
                                @if($product->status != 'coming-soon')
                                    @if($product->is_ebook == 1)
                                        <!-- eBook Direct Checkout -->
                                        
                                        <div id="ebookSection" class="ebook-section">
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
                                                            // Use raw PDF streaming route so iframe loads PDF directly
                                                            $downloadRoute = route('view.ebook', ['orderId' => $order->id, 'itemId' => $item->id]);
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if($hasPurchased && $downloadRoute)
                                                <button type="button" class="theme-btn w-100" style="background-color:#007bff;" data-bs-toggle="modal" data-bs-target="#pdfModalStore" onclick="viewPdf('{{ $downloadRoute }}', '{{ addslashes($product->name) }}')">📖 View eBook</button>
                                            @else
                                                <button type="button" onclick="buyNowEbook({{ $product->id }}, {{ $product->ebook_price ?? $product->price }})" class="theme-btn w-100" style="background-color: #28a745;">
                                                    💳 Buy  eBook
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Physical Book Section -->
                                        <div id="physicalSection" class="physical-section d-none">
                                            <div class="d-flex gap-3 align-items-center">
                                                <div class="quantity-basket">
                                                    <p class="qty d-flex align-items-center gap-2 mb-0">
                                                        <button onclick="decreaseQty({{ $product->id }})" class="qtyminus" aria-hidden="true">−</button>
                                                        @if(Auth::user())
                                                            <input type="number" name="qty" id="qty2" min="1" max="10" value="{{ $cartItems->quantity??1 }}" class="form-control" style="max-width: 80px;">
                                                        @else
                                                            <input type="number" name="qty" id="qty2" min="1" max="10" value="{{ $cartItems[$product->id]['quantity'] ?? 1 }}" class="form-control" style="max-width: 80px;">
                                                        @endif
                                                        <button onclick="increaseQty({{ $product->id }})" class="qtyplus" aria-hidden="true">+</button>
                                                    </p>
                                                </div>
                                                <a onclick="event.preventDefault(); addToCart({{ $product->id }}, true);" href="{{url('/cart')}}" class="theme-btn flex-grow-1">go to cart</a>
                                            </div>
                                        </div> 
                                    @else
                                        <!-- Regular Product Cart (No eBook option) -->
                                        <div id="physicalSection" class="physical-section">
                                            <div class="d-flex gap-3 align-items-center">
                                                <div class="quantity-basket">
                                                    <p class="qty d-flex align-items-center gap-2 mb-0">
                                                        <button onclick="decreaseQty({{ $product->id }})" class="qtyminus" aria-hidden="true">−</button>
                                                        @if(Auth::user())
                                                            <input type="number" name="qty" id="qty2" min="1" max="10" value="{{ $cartItems->quantity??1 }}" class="form-control" style="max-width: 80px;">
                                                        @else
                                                            <input type="number" name="qty" id="qty2" min="1" max="10" value="{{ $cartItems[$product->id]['quantity'] ?? 1 }}" class="form-control" style="max-width: 80px;">
                                                        @endif
                                                        <button onclick="increaseQty({{ $product->id }})" class="qtyplus" aria-hidden="true">+</button>
                                                    </p>
                                                </div>
                                                <a onclick="event.preventDefault(); addToCart({{ $product->id }}, true);" href="{{url('/cart')}}" class="theme-btn flex-grow-1">go to cart</a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <button type="button" class="theme-btn w-100" disabled style="opacity: 0.6; cursor: not-allowed;">Coming Soon - Not Available</button>
                                @endif
                            </div>
                          
                        </div>
                    </div>
                </div>
                <div class="single-tab section-padding pb-0">
                    <ul class="nav mb-5" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#description" data-bs-toggle="tab" class="nav-link ps-0 active"
                                aria-selected="true" role="tab">
                                <h6>Description</h6>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#additional" data-bs-toggle="tab" class="nav-link" aria-selected="false"
                                tabindex="-1" role="tab">
                                <h6>Additional Information </h6>
                            </a>
                        </li>
                      
                    </ul>
                    <div class="tab-content">
                        <div id="description" class="tab-pane fade show active" role="tabpanel">
                            <div class="description-items">
                                <p>
                                   {!! $product->description !!}
                                </p>
                            </div>
                        </div>
                        <div id="additional" class="tab-pane fade" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                   @php
                                   
                                    preg_match_all('/<tr>(.*?)<\/tr>/s', $product->specification, $rows);
                                @endphp
                                
                                <tbody>
                                @foreach($rows[0] as $row)
                                    {!! $row !!}
                                @endforeach
                                </tbody>

                                </table>
                            </div>
                        </div>
                        <div id="review" class="tab-pane fade" role="tabpanel">
                            <div class="review-items">
                                <div class="review-wrap-area d-flex gap-4">
                                    <div class="review-thumb">
                                        <img src="assets/img/shop-details/review.png" alt="img">
                                    </div>
                                    <div class="review-content">
                                        <div
                                            class="head-area d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                            <div class="cont">
                                                <h5><a href="news-details.html">Leslie Alexander</a></h5>
                                                <span>February 10, 2024 at 2:37 pm</span>
                                            </div>
                                            <div class="star">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-regular fa-star"></i>
                                            </div>
                                        </div>
                                        <p class="mt-30 mb-4">
                                            Neque porro est qui dolorem ipsum quia quaed inventor veritatis et quasi
                                            architecto var sed efficitur turpis gilla sed sit amet finibus eros. Lorem
                                            Ipsum is <br> simply dummy
                                        </p>
                                    </div>
                                </div>
                                <div class="review-title mt-5 py-15 mb-30">
                                    <h4>Your Rating*</h4>
                                    <div class="rate-now d-flex align-items-center">
                                        <p>Your Rating*</p>
                                        <div class="star">
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-form">
                                    <form action="#" id="contact-form2" method="POST">
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-clt">
                                                    <span>Your Name*</span>
                                                    <input type="text" name="name" id="name" placeholder="Your Name">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-clt">
                                                    <span>Your Email*</span>
                                                    <input type="text" name="email" id="email" placeholder="Your Email">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 wow fadeInUp animated" data-wow-delay=".8">
                                                <div class="form-clt">
                                                    <span>Message*</span>
                                                    <textarea name="message" id="message"
                                                        placeholder="Write Message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 wow fadeInUp animated" data-wow-delay=".9">
                                                <div class="form-check d-flex gap-2 from-customradio">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="flexRadioDefault" id="flexRadioDefault12">
                                                    <label class="form-check-label" for="flexRadioDefault12">
                                                        i accept your terms & conditions
                                                    </label>
                                                </div>
                                                <button type="submit" class="theme-btn style-2">
                                                    Submit now
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- PDF Modal (Store view) -->
<div class="modal fade" id="pdfModalStore" tabindex="-1" aria-labelledby="pdfModalStoreLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalStoreLabel">View eBook</h5>
                <div class="btn-group me-2" role="group" aria-label="PDF actions">
                    <button type="button" class="btn btn-sm btn-secondary" id="fullscreenBtn" title="Fullscreen">Fullscreen</button>
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
function viewPdf(url, title) {
        var label = document.getElementById('pdfModalStoreLabel');
        var iframe = document.getElementById('pdfIframeStore');
        if(label) label.innerText = title;
        if(iframe) iframe.src = url + '#toolbar=0&navpanes=0&scrollbar=0';
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fullscreenBtn = document.getElementById('fullscreenBtn');
    var openNewTabBtn = document.getElementById('openNewTabBtn');
    var pdfContainer = document.getElementById('pdfContainer');
    var pdfIframe = document.getElementById('pdfIframeStore');

    if (openNewTabBtn) {
        openNewTabBtn.addEventListener('click', function() {
            if (pdfIframe && pdfIframe.src) {
                window.open(pdfIframe.src, '_blank');
            }
        });
    }

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

    <!-- Top Ratting Book Section Start -->
    <section class="top-ratting-book-section section-padding pt-0 fix">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-3 wow fadeInUp" data-wow-delay=".3s">Related Products</h2>
                
            </div>
            <div class="swiper book-slider">
                <div class="swiper-wrapper">
                   
                    @foreach($relatedProduct as $singleProduct)
                      @php
                                                $images = json_decode($singleProduct->image, true) ?? [];
                                                $imageUrl = !empty($images) && isset($images[0])
                                                    ? Storage::disk('s3')->url('product/'.$images[0])
                                                    : asset('images/no-image.png'); // fallback image
                                            @endphp
                    <div class="swiper-slide">
                        <div class="shop-box-items style-2">
                            <div class="book-thumb center">
                                <a href="{{url('/book-details/'.$singleProduct->slug)}}"><img src="{{ $imageUrl }}" alt="img"></a>
                    
                                <ul class="shop-icon d-grid justify-content-center align-items-center">
                                    <li>
                                        <a href="{{url('/cart')}}"><i class="far fa-heart"></i></a>
                                    </li>
                                </ul>
                                           </div>
                            <div class="shop-content">
                                <h5> {{$singleProduct->author_name}} </h5>
                                <h3><a class="product-title-css" href="{{url('/book-details/'.$singleProduct->slug)}}">{{$singleProduct->name}}</a></h3>
                                <ul class="price-list">
                                    <li>₹{{$singleProduct->price}}/-</li>
                                    <!--<li>-->
                                    <!--    <del>$39.99</del>-->
                                    <!--</li>-->
                                </ul>
                                <ul class="author-post">
                                    <li class="authot-list">
                                        <!-- <span class="thumb">
                                            <img src="{{url('public/store/assets/img/testimonial/client-1.png')}}" alt="img">
                                        </span> -->
                                        <span class="content">{{$singleProduct->author}}</span>
                                    </li>

                                    <!--<li class="star">-->
                                    <!--    <i class="fa-solid fa-star"></i>-->
                                    <!--    <i class="fa-solid fa-star"></i>-->
                                    <!--    <i class="fa-solid fa-star"></i>-->
                                    <!--    <i class="fa-solid fa-star"></i>-->
                                    <!--    <i class="fa-regular fa-star"></i>-->
                                    <!--</li>-->
                                </ul>
                            </div>
                                
                            <div class="shop-button">
                                @if($singleProduct->status != 'coming-soon')
                                    @if($singleProduct->is_ebook == 1)
                                        <button type="button" onclick="buyNowEbook({{ $singleProduct->id }}, {{ $singleProduct->price }})" class="theme-btn" style="background-color: #28a745; width: 100%;">
                                            💳 Buy eBook
                                        </button>
                                    @else
                                        <form action="{{ route('cart.add', $singleProduct->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="theme-btn">Add To Cart</button>
                                        </form>
                                    @endif
                                @else
                                <button type="button" class="theme-btn" disabled style="opacity: 0.6; cursor: not-allowed; width: 100%;">Coming Soon</button>
                                @endif

                            </div>
                        </div>
                    </div>
                    
                    @endforeach
                 
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle between ebook and physical book
        function toggleProductType(productId) {
            var selectedType = document.querySelector('input[name="product_type"]:checked').value;
            var ebookSection = document.getElementById('ebookSection');
            var physicalSection = document.getElementById('physicalSection');
            var priceDisplay = document.getElementById('priceDisplay');

            if (selectedType === 'ebook') {
                // Show ebook section, hide physical section
                if (ebookSection) ebookSection.classList.remove('d-none');
                if (physicalSection) physicalSection.classList.add('d-none');
                // Update price to ebook price
                if (priceDisplay && window.priceData && window.priceData.ebook) {
                    window.priceData.current = window.priceData.ebook;
                    priceDisplay.textContent = '₹' + window.priceData.ebook + '/-';
                }
            } else if (selectedType === 'physical') {
                // Show physical section, hide ebook section
                if (ebookSection) ebookSection.classList.add('d-none');
                if (physicalSection) physicalSection.classList.remove('d-none');
                // Update price to physical price
                if (priceDisplay && window.priceData && window.priceData.physical) {
                    window.priceData.current = window.priceData.physical;
                    priceDisplay.textContent = '₹' + window.priceData.physical + '/-';
                }
            }
        }

        function decreaseQty(productId) {
            var qtyInput = document.getElementById('qty2');
            var currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
            }
            addToCart(productId);
        }

        function increaseQty(productId) {
            var qtyInput = document.getElementById('qty2');
            var currentQty = parseInt(qtyInput.value);
            qtyInput.value = currentQty + 1;
            addToCart(productId);
        }

        function buyNowEbook(productId, price) {
            @if(Auth::check())
                // Use ebook price from window.priceData if available
                var finalPrice = (window.priceData && window.priceData.ebook) ? window.priceData.ebook : price;
                
                var quantity = parseInt(document.getElementById('qty2').value);
                if (isNaN(quantity) || quantity <= 0) {
                    quantity = 1;
                }
                
                var total = finalPrice * quantity;
                var platformFee = 7;
                var finalAmount = total + platformFee;

                // Create direct order for eBook
                const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                    document.querySelector('input[name="_token"]')?.value;

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
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        console.error(data.error);
                        return;
                    }

                    // Open Razorpay
                    var options = {
                        key: data.razorpay_key_id,
                        amount: data.amount,
                        currency: 'INR',
                        order_id: data.razorpay_order_id,
                        name: 'Speech Publications',
                        description: 'eBook Purchase',
                        image: '{{ asset("images/logo.png") }}',
                        handler: function(response) {
                            verifyEbookPayment(response, data.order_id);
                        },
                        prefill: {
                            name: '{{ Auth::user()->name }}',
                            email: '{{ Auth::user()->email }}',
                            contact: '{{ Auth::user()->phone ?? "" }}'
                        },
                        theme: {
                            color: '#667eea'
                        }
                    };

                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function(response) {
                        alert('Payment failed: ' + response.error.reason);
                    });
                    rzp.open();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            @else
                // Redirect to login if not authenticated
                window.location.href = '{{ route("login") }}';
            @endif
        }

        function verifyEbookPayment(response, orderId) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value;

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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('Payment verification failed: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during payment verification.');
            });
        }

        function addToCart(productId, redirect = false) {
            var qtyInput = document.getElementById('qty2');
            var quantity = parseInt(qtyInput.value);
            if (isNaN(quantity) || quantity <= 0) {
                 quantity = 1;
                 qtyInput.value = 1;
            }
            const token = document.querySelector('meta[name="csrf-token"]')?.content ||
        document.querySelector('input[name="_token"]')?.value;

            fetch(`/cart/update/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (redirect) {
                window.location.href = "{{ url('/cart') }}";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // showNotification('Error updating cart', 'error');
        })
        .finally(() => {
     
        });
        }
    </script>

    @include('layouts.store-footer')
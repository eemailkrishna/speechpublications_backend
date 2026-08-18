@include('layouts.store-header')

<!-- Breadcrumb Section Start -->
<div class="breadcrumb-wrapper bg-cover section-padding"
    style="background-image: url({{ asset('/store/assets/img/hero/breadcrumb-bg.jpg') }});">
    <div class="container">
        <div class="page-heading">
            <h1>Shopping Cart</h1>
            <div class="page-header">
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                    <li>
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>
                        Shopping Cart
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Cart Section Start -->
<section class="cart-section fix section-padding">
    <div class="container">
        @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table-wrapper">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th style="min-width: 160px;">Quantity</th>
                                    <th style="min-width: 160px;">Book Type</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                @php
                                $product = $item instanceof \App\Models\Cart ? $item->product : null;
                                $itemId = $item instanceof \App\Models\Cart ? $item->id : $item['id'];
                                $productId = $item instanceof \App\Models\Cart ? $item->product_id : $item['id'];
                                $itemName = $product ? $product->name : $item['name'];
                                $itemPrice = $product ? $product->price : $item['price'];
                                $itemImage = $product ? json_decode($product->image, true)[0] ?? null : $item['image'];
                                $itemQuantity = $item instanceof \App\Models\Cart ? $item->quantity : $item['quantity'];
                                @endphp
                                <tr style="vertical-align: middle;">
                                    <td>
                                        <div class="cart-product-info">
                                            @if($itemImage)
                                            <img src="{{ Storage::disk('s3')->url('product/'.$itemImage) }}"
                                                alt="product" width="60" height="60" style="border-radius: 4px;">
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <span class="cart-product-name">{{ $itemName }}</span>
                                    </td>
                                    <td>
                                        <span class="cart-price">₹{{ number_format($itemPrice, 2) }}</span>
                                    </td>
                                    <td style="min-width: 210px;">




                                        <span class="quantity-basket">
                                            <span class="qty" style="align-items: center;
    border: 1px solid #E5E5E5;
    padding: 11px 30px;
    border-radius: 100px;
    line-height: 1;
    justify-content: space-between;">
                                                <form action="{{ route('cart.update', $productId) }}" method="POST"
                                                    class="quantity-form d-inline update-qty-form">
                                                    @csrf
                                                    <button type="button" class="qtyminus"
                                                        onclick="decreaseQtyAjax(this)">−</button>
                                                    <input type="number" style="color:black;text-align: center;
    border-radius: 0;
    border: none;
    outline: none;" name="quantity" class="qty-input" data-item-id="{{ $productId }}" data-price="{{ $itemPrice }}"
                                                        value="{{ $itemQuantity }}" min="1" max="100">
                                                    <button type="button" class="qtyplus"
                                                        onclick="increaseQtyAjax(this)">+</button>
                                                </form>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cart-book-type">
                                            @if($product && $product->is_ebook == 1)
                                            E-Book
                                            @else
                                            Physical Book
                                            @endif
                                        </span>
                                    <td>
                                        <span class="subtotal-price" data-price="{{ $itemPrice }}"
                                            data-qty="{{ $itemQuantity }}">₹{{ number_format($itemPrice * $itemQuantity, 2) }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $itemId) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="remove-icon"
                                                onclick="return confirm('Remove item?')">
                                                <img src="{{ asset('/store/assets/img/icon/icon-9.svg') }}"
                                                    alt="remove">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="cart-wrapper-footer mt-4">
                    <a href="{{ url('/store') }}" class="theme-btn">
                        Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="theme-btn" style="background-color: #dc3545;"
                            onclick="return confirm('Clear entire cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="table-responsive cart-total">
                    <table class="table style-2">
                        <thead>
                            <tr>
                                <th>Cart Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="d-flex gap-5 align-items-center justify-content-between">
                                        <span class="sub-title">Subtotal:</span>
                                        <span class="sub-price">₹{{ number_format($total, 2) }}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-flex gap-5 align-items-center  justify-content-between">
                                        <span class="sub-title">Shipping:</span>
                                        <span class="sub-text">
                                            ₹15
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-flex gap-5 align-items-center  justify-content-between">
                                        <span class="sub-title">Platform Fee:</span>
                                        <span class="sub-text">
                                            ₹7
                                        </span>
                                    </span>
                                </td>
                            </tr>
                           
                            <tr>
                                <td>
                                    <span class="d-flex gap-5 align-items-center  justify-content-between">
                                        <span class="sub-title">Total: </span>
                                        <span class="sub-price sub-price-total">₹{{ number_format($total+15+7, 2) }}</span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{url('/checkout')}}" class="theme-btn style-2">Proceed to checkout</a>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12 text-center">
                <div class="empty-cart" style="padding: 60px 20px; background: #f9f9f9; border-radius: 8px;">
                    <i class="fas fa-shopping-cart" style="font-size: 80px; color: #ddd;"></i>
                    <h3 style="color: #333; margin-top: 20px;">Your Cart is Empty</h3>
                    <p style="color: #666; margin-bottom: 20px;">Start shopping to add items to your cart</p>
                    <a href="{{ url('/store') }}" class="theme-btn" style="margin-top: 20px;">Continue Shopping</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<script>
// AJAX update - no page reload
function decreaseQtyAjax(btn) {
    const input = btn.nextElementSibling;
    let value = parseInt(input.value) - 1;
    if (value < 1) value = 1;
    input.value = value;
    updateCartAjax(input);
}

function increaseQtyAjax(btn) {
    const input = btn.previousElementSibling;
    let value = parseInt(input.value) + 1;
    if (value > 100) value = 100;
    input.value = value;
    updateCartAjax(input);
}

// AJAX function to update without page reload
function updateCartAjax(input) {
    const itemId = input.dataset.itemId;
    const quantity = input.value;
    const price = parseFloat(input.dataset.price);
    const form = input.closest('form');

    // Add loading state
    form.classList.add('updating');

    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.content ||
        document.querySelector('input[name="_token"]')?.value;

    // Send AJAX request
    fetch(`{{ url('/cart/update') }}/${itemId}`, {
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
            if (data.success) {
                // Update item subtotal
                const subtotalSpan = form.closest('tr').querySelector('.subtotal-price');
                if (subtotalSpan) {
                    const newSubtotal = price * quantity;
                    subtotalSpan.textContent = '₹' + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                // Update total price
                updateTotalPrice();

                // Show success toast
                showNotification('Quantity updated!', 'success');
            } else {
                showNotification('Error updating cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating cart', 'error');
        })
        .finally(() => {
            form.classList.remove('updating');
        });
}

// Calculate and update total price
function updateTotalPrice() {
    let total = 0;

    document.querySelectorAll('.subtotal-price').forEach(span => {
        const text = span.textContent.replace('₹', '').replace(/,/g, '');
        total += parseFloat(text) || 0;
    });

    // Update total in summary
    const totalSpans = document.querySelectorAll('.sub-price-total');
    totalSpans.forEach(span => {
        span.textContent = '₹' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });

    // Update subtotal in summary
    const subtotalSpans = document.querySelectorAll('.sub-price');
    if (subtotalSpans.length > 0) {
        subtotalSpans[0].textContent = '₹' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}

// Show notification toast
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.cart-section .container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}

// Auto-submit when manually typing quantity
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');
    qtyInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 100) this.value = 100;
            updateCartAjax(this);
        });
    });
});
</script>

@include('layouts.store-footer')
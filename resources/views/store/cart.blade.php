@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/store-beauty.css') }}">

<style>
  .wow { visibility: visible !important; opacity: 1 !important; transform: none !important; }
  @keyframes spSlideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper" style="background: var(--sp-card); border-bottom: 1px solid var(--sp-line); padding: 18px 0;">
  <div class="container">
    <div class="page-heading">
      <h1 style="color: var(--sp-ink); font-weight: 600; font-size: 24px; font-family: var(--font-serif); margin-bottom: 4px;">Shopping Cart</h1>
      <div class="page-header">
        <ul class="breadcrumb-items" style="display: flex; gap: 8px; list-style: none; padding: 0; margin: 0;">
          <li><a href="{{ url('/') }}" style="color: var(--sp-maroon); font-weight: 500; font-size: 13px; text-decoration: none;">Home</a></li>
          <li><i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--sp-muted);"></i></li>
          <li style="color: var(--sp-muted); font-size: 13px;">Shopping Cart</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Cart Section -->
<section class="cart-section fix section-padding" style="padding: 24px 0 40px;">
  <div class="container">
    @if($message = Session::get('success'))
    <div id="sp-cart-toast" style="position: fixed; top: 80px; right: 20px; z-index: 9999; display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; box-shadow: 0 4px 16px rgba(22, 163, 74, .15); font-family: var(--font-ui); animation: spSlideIn .3s ease; max-width: 300px;">
      <i class="fas fa-check-circle" style="font-size: 14px; color: #16a34a; flex-shrink: 0;"></i>
      <span style="font-size: 12px; font-weight: 600; color: #166534; flex: 1;">{{ $message }}</span>
      <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #16a34a; font-size: 12px; padding: 0; flex-shrink: 0;">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <script>setTimeout(function(){ var t=document.getElementById('sp-cart-toast'); if(t) t.remove(); }, 3000);</script>
    @endif

    @if($message = Session::get('error'))
    <div id="sp-cart-toast" style="position: fixed; top: 80px; right: 20px; z-index: 9999; display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; box-shadow: 0 4px 16px rgba(220, 38, 38, .15); font-family: var(--font-ui); animation: spSlideIn .3s ease; max-width: 300px;">
      <i class="fas fa-exclamation-circle" style="font-size: 14px; color: #dc2626; flex-shrink: 0;"></i>
      <span style="font-size: 12px; font-weight: 600; color: #991b1b; flex: 1;">{{ $message }}</span>
      <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #dc2626; font-size: 12px; padding: 0; flex-shrink: 0;">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <script>setTimeout(function(){ var t=document.getElementById('sp-cart-toast'); if(t) t.remove(); }, 3000);</script>
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
                <tr>
                  <td>
                    <div class="cart-product-info">
                      @if($itemImage)
                      <img src="{{ Storage::disk('s3')->url('product/'.$itemImage) }}" alt="product">
                      @endif
                    </div>
                  </td>
                  <td>
                    <span class="cart-product-name">{{ $itemName }}</span>
                  </td>
                  <td>
                    <span class="cart-price">₹{{ number_format($itemPrice, 2) }}</span>
                  </td>
                  <td style="min-width: 160px;">
                    <span class="quantity-basket">
                      <span class="qty">
                        <form action="{{ route('cart.update', $productId) }}" method="POST" class="quantity-form d-inline update-qty-form">
                          @csrf
                          <button type="button" class="qtyminus" onclick="decreaseQtyAjax(this)">−</button>
                          <input type="number" name="quantity" class="qty-input" data-item-id="{{ $productId }}" data-price="{{ $itemPrice }}" value="{{ $itemQuantity }}" min="1" max="100">
                          <button type="button" class="qtyplus" onclick="increaseQtyAjax(this)">+</button>
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
                  </td>
                  <td>
                    <span class="subtotal-price" data-price="{{ $itemPrice }}" data-qty="{{ $itemQuantity }}">₹{{ number_format($itemPrice * $itemQuantity, 2) }}</span>
                  </td>
                  <td>
                    <form action="{{ route('cart.remove', $itemId) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="remove-icon" onclick="return confirm('Remove item?')">
                        <i class="fas fa-trash-alt" style="font-size: 14px; color: var(--sp-muted);"></i>
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
          <a href="{{ url('/store') }}" class="theme-btn">Continue Shopping</a>
          <form action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="theme-btn" style="background-color: #dc3545;" onclick="return confirm('Clear entire cart?')">Clear Cart</button>
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
                  <span class="d-flex gap-5 align-items-center justify-content-between">
                    <span class="sub-title">Shipping:</span>
                    <span class="sub-text">₹15</span>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="d-flex gap-5 align-items-center justify-content-between">
                    <span class="sub-title">Platform Fee:</span>
                    <span class="sub-text">₹7</span>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="d-flex gap-5 align-items-center justify-content-between">
                    <span class="sub-title">Total:</span>
                    <span class="sub-price sub-price-total">₹{{ number_format($total+15+7, 2) }}</span>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
          <a href="{{ url('/checkout') }}" class="theme-btn style-2">Proceed to checkout</a>
        </div>
      </div>
    </div>
    @else
    <div class="row">
      <div class="col-12 text-center">
        <div class="empty-cart" style="padding: 60px 20px;">
          <i class="fas fa-shopping-cart" style="font-size: 60px; color: var(--sp-line); margin-bottom: 16px; display: block;"></i>
          <h3 style="color: var(--sp-ink); font-family: var(--font-serif); font-weight: 600; margin-bottom: 8px;">Your Cart is Empty</h3>
          <p style="color: var(--sp-muted); font-size: 14px; margin-bottom: 20px; font-family: var(--font-ui);">Start shopping to add items to your cart</p>
          <a href="{{ url('/store') }}" class="theme-btn" style="background: var(--sp-maroon); color: #fff; padding: 12px 28px; border-radius: 100px; font-size: 13px; font-weight: 700; text-decoration: none; font-family: var(--font-ui); text-transform: uppercase; letter-spacing: .3px;">Continue Shopping</a>
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

// Show notification toast (small, top-right)
function showNotification(message, type = 'success') {
    const existing = document.getElementById('sp-cart-toast');
    if (existing) existing.remove();

    const isSuccess = type === 'success';
    const bg = isSuccess ? '#f0fdf4' : '#fef2f2';
    const border = isSuccess ? '#bbf7d0' : '#fecaca';
    const icon = isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle';
    const iconColor = isSuccess ? '#16a34a' : '#dc2626';
    const textColor = isSuccess ? '#166534' : '#991b1b';
    const closeColor = isSuccess ? '#86efac' : '#fca5a5';

    const toast = document.createElement('div');
    toast.id = 'sp-cart-toast';
    toast.style.cssText = 'position:fixed;top:80px;right:20px;z-index:9999;display:flex;align-items:center;gap:8px;padding:8px 14px;background:' + bg + ';border:1px solid ' + border + ';border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.1);font-family:var(--font-ui);animation:spSlideIn .3s ease;max-width:280px;';
    toast.innerHTML = '<i class="fas ' + icon + '" style="font-size:16px;color:' + iconColor + ';flex-shrink:0;"></i>' +
        '<span style="font-size:13px;font-weight:600;color:' + textColor + ';flex:1;">' + message + '</span>' +
        '<button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:' + closeColor + ';font-size:14px;padding:0;flex-shrink:0;"><i class="fas fa-times"></i></button>';

    document.body.appendChild(toast);
    setTimeout(function(){ if(toast.parentElement) toast.remove(); }, 3000);
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

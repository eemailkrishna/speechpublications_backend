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
      <h1 style="color: var(--sp-ink); font-weight: 600; font-size: 24px; font-family: var(--font-serif); margin-bottom: 4px;">Checkout</h1>
      <div class="page-header">
        <ul class="breadcrumb-items" style="display: flex; gap: 8px; list-style: none; padding: 0; margin: 0;">
          <li><a href="{{ url('/') }}" style="color: var(--sp-maroon); font-weight: 500; font-size: 13px; text-decoration: none;">Home</a></li>
          <li><i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--sp-muted);"></i></li>
          <li style="color: var(--sp-muted); font-size: 13px;">Checkout</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Checkout Section -->
<section style="padding: 24px 0 40px;">
  <div class="container">
    <div class="row">

      <!-- Billing & Shipping Form -->
      <div class="col-lg-8">
        <div class="sp-checkout-card mb-4">
          <div class="card-header">
            <h5>Billing Details</h5>
          </div>
          <div class="card-body">
            <form id="checkoutForm">
              @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name" class="form-label">First Name *</label>
                  <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                    id="first_name" name="first_name" value="{{ auth()->user()->name ?? '' }}" required>
                  <small class="text-danger d-none" id="first_name_error"></small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="last_name" class="form-label">Last Name *</label>
                  <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                    id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                  <small class="text-danger d-none" id="last_name_error"></small>
                </div>
              </div>

              <div class="mb-3 hidden">
                <label for="country" class="form-label">Country *</label>
                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country"
                  name="country" value="{{ old('country', 'India') }}" readonly>
                <small class="text-danger d-none" id="country_error"></small>
              </div>

              <div class="mb-3">
                <label for="address" class="form-label">Street Address *</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                  name="address" placeholder="House number and street name and pincode" value="{{ old('address') }}" required>
                <small class="text-danger d-none" id="address_error"></small>
              </div>

              <div class="mb-3">
                <label for="city" class="form-label">City *</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                  name="city" value="{{ old('city') }}" placeholder="Enter city name" required>
                <small class="text-danger d-none" id="city_error"></small>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Phone Number *</label>
                  <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    name="phone" value="{{ auth()->user()->phone_number ?? old('phone') }}" placeholder="Enter phone number" required>
                  <small class="text-danger d-none" id="phone_error"></small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email Address *</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ auth()->user()->email ?? old('email') }}" readonly required>
                  <small class="text-danger d-none" id="email_error"></small>
                </div>
              </div>

              <div class="mb-4">
                <label for="order_notes" class="form-label">Order Notes (Optional)</label>
                <textarea class="form-control" id="order_notes" name="order_notes" rows="3"
                  placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_notes') }}</textarea>
              </div>

              <input type="hidden" name="shipping_method" value="local">

              <!-- Payment Method -->
              <div class="sp-checkout-card mb-4" style="border: 1px solid var(--sp-line);">
                <div class="card-header">
                  <h6>Payment Method</h6>
                </div>
                <div class="card-body">
                  <div class="form-check">
                    <input class="form-check-input payment-option" type="radio" name="payment_method"
                      id="payment_razorpay" value="razorpay">
                    <label class="form-check-label" for="payment_razorpay">
                      <strong>Razorpay</strong> — Pay securely with Credit/Debit/UPI
                    </label>
                  </div>
                </div>
              </div>

              <button type="submit" class="sp-checkout-submit" id="submitBtn">
                <span id="submitBtnText">Place Order</span>
                <span id="submitBtnSpinner" class="spinner-border spinner-border-sm ms-2 d-none"
                  role="status" aria-hidden="true"></span>
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Order Summary Sidebar -->
      <div class="col-lg-4">
        <div class="sp-checkout-summary">
          <div class="card-header">
            <h5>Order Summary</h5>
          </div>
          <div class="card-body">
            <div id="orderItemsContainer" class="mb-4" style="max-height: 300px; overflow-y: auto;">
              @foreach($cartItems as $item)
              <div class="sp-checkout-item" data-product-id="{{ $item->product_id }}">
                <div class="flex-grow-1">
                  <div class="sp-checkout-item-name">{{ $item->product->name ?? 'Product' }}</div>
                  <div class="sp-checkout-item-qty">Qty: <span class="item-qty">{{ $item->quantity }}</span></div>
                </div>
                <div class="sp-checkout-item-price">₹{{ number_format($item->quantity * $item->price, 2) }}</div>
              </div>
              @endforeach
            </div>

            <div style="border-top: 1px solid var(--sp-line); padding-top: 16px;">
              <div class="d-flex justify-content-between mb-2" style="font-family: var(--font-ui); font-size: 14px;">
                <span style="color: var(--sp-muted);">Subtotal:</span>
                <strong id="subtotalAmount" style="color: var(--sp-ink);">₹{{ number_format($subtotal, 2) }}</strong>
              </div>
              <div class="d-flex justify-content-between mb-2" style="font-family: var(--font-ui); font-size: 14px;">
                <span style="color: var(--sp-muted);">Shipping fee:</span>
                <strong id="shippingAmount" style="color: var(--sp-ink);">₹15.00</strong>
              </div>
              <div class="d-flex justify-content-between mb-3" style="font-family: var(--font-ui); font-size: 14px;">
                <span style="color: var(--sp-muted);">Platform fee:</span>
                <strong id="platformAmount" style="color: var(--sp-ink);">₹7.00</strong>
              </div>
              <div class="d-flex justify-content-between" style="border-top: 1px solid var(--sp-line); padding-top: 12px; font-family: var(--font-ui);">
                <span style="font-size: 16px; font-weight: 700; color: var(--sp-ink);">Total:</span>
                <strong id="totalAmount" class="sp-checkout-total">₹{{ number_format($subtotal + 15.00 + 7.00, 2) }}</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Trust Badges -->
        <div class="sp-checkout-trust">
          <div class="sp-checkout-trust-icon">🔒</div>
          <div class="sp-checkout-trust-title">Secure Checkout</div>
          <div class="sp-checkout-trust-text">Your payment information is encrypted and secure.</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const subtotal = {{ $subtotal }};
const shippingCost = 15.00;
const platformFee = 7.00;

document.addEventListener('DOMContentLoaded', function() {
    updateTotal();
    autoFillNameFields();
    setCountryDefault();
    document.getElementById('checkoutForm').addEventListener('submit', handleCheckoutSubmit);
});

function setCountryDefault() {
    const countryField = document.getElementById('country');
    if (countryField) {
        countryField.value = 'India';
    }
}

function autoFillNameFields() {
    const fullName = document.getElementById('first_name').value.trim();
    if (fullName) {
        const nameParts = fullName.split(' ');
        if (nameParts.length > 0) {
            document.getElementById('first_name').value = nameParts[0];
        }
        if (nameParts.length > 1) {
            const lastName = nameParts.slice(1).join(' ');
            document.getElementById('last_name').value = lastName;
        }
    }
}

function updateTotal() {
    const total = subtotal + shippingCost + platformFee;
    document.getElementById('shippingAmount').textContent = '₹' + shippingCost.toFixed(2);
    document.getElementById('platformAmount').textContent = '₹' + platformFee.toFixed(2);
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);
}

function handleCheckoutSubmit(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const paymentMethod = formData.get('payment_method');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnSpinner = document.getElementById('submitBtnSpinner');

    submitBtn.disabled = true;
    submitBtnSpinner.classList.remove('d-none');

    if (paymentMethod === 'cod') {
        processCheckoutCOD(formData, submitBtn, submitBtnText, submitBtnSpinner);
    } else if (paymentMethod === 'razorpay') {
        processRazorpayCheckout(formData, submitBtn, submitBtnText, submitBtnSpinner);
    }
}

function processCheckoutCOD(formData, submitBtn, submitBtnText, submitBtnSpinner) {
    fetch('/checkout/process-cod', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, data: data })))
        .then(({ status, data }) => {
            if (status === 200 && data.success) {
                showNotification('Order placed successfully!', 'success');
                setTimeout(() => { window.location.href = data.redirect; }, 1500);
            } else if (status === 422) {
                const errors = data.errors || {};
                let errorMsg = 'Please fix the following errors:\n';
                for (let field in errors) { errorMsg += '- ' + errors[field][0] + '\n'; }
                showNotification(errorMsg, 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            } else {
                showNotification(data.error || data.message || 'Failed to process order', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please check the console for details.', 'danger');
            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
        });
}

function processRazorpayCheckout(formData, submitBtn, submitBtnText, submitBtnSpinner) {
    fetch('/checkout/create-razorpay-order', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json().then(data => ({ status: response.status, data: data })))
        .then(({ status, data }) => {
            if (status === 200 && data.success) {
                const options = {
                    key: data.razorpay_key_id,
                    amount: data.amount,
                    currency: 'INR',
                    name: 'Speech Publications',
                    description: `Order #${data.order_id}`,
                    order_id: data.razorpay_order_id,
                    handler: function(response) {
                        verifyRazorpayPayment(response, data.order_id, submitBtn, submitBtnText, submitBtnSpinner);
                    },
                    prefill: {
                        name: data.user_name,
                        email: data.user_email,
                        contact: data.user_phone,
                    },
                    theme: { color: '#7c2a2a' },
                    modal: {
                        ondismiss: function() {
                            showNotification('Payment cancelled', 'warning');
                            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
                        }
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } else if (status === 422) {
                const errors = data.errors || {};
                let errorMsg = 'Please fix the following errors:\n';
                for (let field in errors) { errorMsg += '- ' + errors[field][0] + '\n'; }
                showNotification(errorMsg, 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            } else {
                showNotification(data.error || data.message || 'Failed to create payment order', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please check the console for details.', 'danger');
            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
        });
}

function verifyRazorpayPayment(response, orderId, submitBtn, submitBtnText, submitBtnSpinner) {
    fetch('/checkout/verify-razorpay-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature,
                order_id: orderId,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Payment successful! Redirecting...', 'success');
                setTimeout(() => { window.location.href = data.redirect; }, 1500);
            } else {
                showNotification(data.error || 'Payment verification failed', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            showNotification('Payment verification failed. Please contact support.', 'danger');
            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
        });
}

function resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner) {
    submitBtn.disabled = false;
    submitBtnSpinner.classList.add('d-none');
}

function showNotification(message, type = 'info') {
    const icons = { success: 'fa-check-circle', danger: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle' };
    const alertId = 'alert-' + Date.now();
    const alertHTML = `
        <div id="${alertId}" class="sp-checkout-toast ${type}" style="animation: spSlideIn .3s ease;">
            <i class="fas ${icons[type] || icons.info} toast-icon"></i>
            <span class="toast-msg">${message}</span>
            <button type="button" class="toast-close" onclick="document.getElementById('${alertId}').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alertHTML);
    setTimeout(() => { const el = document.getElementById(alertId); if (el) el.remove(); }, 5000);
}
</script>
@include('layouts.store-footer')

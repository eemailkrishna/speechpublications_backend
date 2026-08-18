@include('layouts.store-header')

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Billing & Shipping Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Billing Details</h5>
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
                                name="address" placeholder="House number and street name and pincode" value="{{ old('address') }}"
                                required>
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

                        <!-- Hidden shipping method -->
                        <input type="hidden" name="shipping_method" value="local">

                        <!-- Payment Method -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Payment Method</h6>
                            </div>
                            <div class="card-body">
                                <!-- <div class="form-check mb-3">
                                    <input class="form-check-input payment-option" type="radio" name="payment_method"
                                        id="payment_cod" value="cod" checked>
                                    <label class="form-check-label" for="payment_cod">
                                        <strong>Cash on Delivery</strong> - Pay when you receive your order
                                    </label>
                                </div> -->
                                <div class="form-check">
                                    <input class="form-check-input payment-option" type="radio" name="payment_method"
                                        id="payment_razorpay" value="razorpay">
                                    <label class="form-check-label" for="payment_razorpay">
                                        <strong>Razorpay</strong> - Pay securely with Razorpay (Credit/Debit/UPI)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
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
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div id="orderItemsContainer" class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom"
                            data-product-id="{{ $item->product_id }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name ?? 'Product' }}</h6>
                                <small class="text-muted">
                                    Qty: <span class="item-qty">{{ $item->quantity }}</span>
                                </small>
                            </div>
                            <div class="text-end">
                                <strong
                                    class="item-subtotal">₹{{ number_format($item->quantity * $item->price, 2) }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotalAmount">₹{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping fee:</span>
                            <strong id="shippingAmount">₹15.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Platform fee:</span>
                            <strong id="platformAmount">₹7.00</strong>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3" style="font-size: 1.25rem;">
                            <span>Total:</span>
                            <strong id="totalAmount">₹{{ number_format($subtotal + 15.00 + 7.00, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="mt-4">
                <div class="alert alert-info text-center">
                    <div class="mb-2">🔒 <strong>Secure Checkout</strong></div>
                    <small>Your payment information is encrypted and secure.</small>
                </div>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <div style="width: 40px; height: 25px; background: #ddd; border-radius: 4px;" title="SSL Secure">
                    </div>
                    <div style="width: 40px; height: 25px; background: #ddd; border-radius: 4px;" title="Razorpay">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const subtotal = {{ $subtotal }};
const shippingCost = 15.00;
const platformFee = 7.00;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize totals
    updateTotal();

    // Auto-fill first and last name from full name
    autoFillNameFields();

    // Set country field default value
    setCountryDefault();

    // Form submission
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
        
        // Set first name
        if (nameParts.length > 0) {
            document.getElementById('first_name').value = nameParts[0];
        }
        
        // Set last name (combine all parts after first name)
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

    // Show loading state
    submitBtn.disabled = true;
    submitBtnSpinner.classList.remove('d-none');

    if (paymentMethod === 'cod') {
        // Process Cash on Delivery
        processCheckoutCOD(formData, submitBtn, submitBtnText, submitBtnSpinner);
    } else if (paymentMethod === 'razorpay') {
        // Process Razorpay
        processRazorpayCheckout(formData, submitBtn, submitBtnText, submitBtnSpinner);
    }
}

function processCheckoutCOD(formData, submitBtn, submitBtnText, submitBtnSpinner) {
    fetch('/checkout/process-cod', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json().then(data => ({
                status: response.status,
                data: data
            }));
        })
        .then(({
            status,
            data
        }) => {
            console.log('Response data:', data);
            if (status === 200 && data.success) {
                showNotification('Order placed successfully!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else if (status === 422) {
                // Validation errors
                const errors = data.errors || {};
                let errorMsg = 'Please fix the following errors:\n';
                for (let field in errors) {
                    errorMsg += '- ' + errors[field][0] + '\n';
                }
                showNotification(errorMsg, 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            } else {
                showNotification(data.error || data.message || 'Failed to process order', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please check the console for details.', 'danger');
            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
        });
}

function processRazorpayCheckout(formData, submitBtn, submitBtnText, submitBtnSpinner) {
    fetch('/checkout/create-razorpay-order', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            console.log('Razorpay order response status:', response.status);
            return response.json().then(data => ({
                status: response.status,
                data: data
            }));
        })
        .then(({
            status,
            data
        }) => {
            console.log('Razorpay order data:', data);
            if (status === 200 && data.success) {
                // Open Razorpay checkout
                const options = {
                    key: data.razorpay_key_id,
                    amount: data.amount,
                    currency: 'INR',
                    name: 'Your Store',
                    description: `Order #${data.order_id}`,
                    order_id: data.razorpay_order_id,
                    handler: function(response) {
                        verifyRazorpayPayment(response, data.order_id, submitBtn, submitBtnText,
                            submitBtnSpinner);
                    },
                    prefill: {
                        name: data.user_name,
                        email: data.user_email,
                        contact: data.user_phone,
                    },
                    theme: {
                        color: '#0d6efd',
                    },
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
                // Validation errors
                const errors = data.errors || {};
                let errorMsg = 'Please fix the following errors:\n';
                for (let field in errors) {
                    errorMsg += '- ' + errors[field][0] + '\n';
                }
                showNotification(errorMsg, 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            } else {
                showNotification(data.error || data.message || 'Failed to create payment order', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            console.error('Error:', error);
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
                showNotification('Payment successful! Redirecting to order confirmation...', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showNotification(data.error || 'Payment verification failed', 'danger');
                resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Payment verification failed. Please contact support.', 'danger');
            resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner);
        });
}

function resetSubmitBtn(submitBtn, submitBtnText, submitBtnSpinner) {
    submitBtn.disabled = false;
    submitBtnSpinner.classList.add('d-none');
}

function showNotification(message, type = 'info') {
    const alertId = 'alert-' + Date.now();
    const alertHTML = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show position-fixed" role="alert" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alertHTML);

    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Debug logging
console.log('Checkout page loaded');
console.log('Razorpay Checkout Script loaded:', typeof Razorpay !== 'undefined');
</script>
@include('layouts.store-footer')
@include('layouts.store-header')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Success Message -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">✓ Order Confirmed!</h4>
                <p>Thank you for your order. Your order has been received and is being processed.</p>
                <hr>
                <p class="mb-0">Order ID: <strong>#{{ $order->id }}</strong></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Order Summary Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Order Number</h6>
                            <p class="h5">#{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Order Date</h6>
                            <p class="h5">{{ $order->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Status</h6>
                            <p>
                                <span class="badge bg-{{ $order->getStatusBadgeColor() }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Payment Method</h6>
                            <p class="h6">
                                @if($order->payment_method === 'cod')
                                <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                                @elseif($order->payment_method === 'razorpay')
                                <i class="fas fa-credit-card"></i> Razorpay Payment
                                @if($order->razorpay_payment_id)
                                <br><small class="text-success">✓ Payment Confirmed</small>
                                @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing & Shipping Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Billing Address</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                            @if($order->company)
                            <p class="mb-1">{{ $order->company }}</p>
                            @endif
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}, {{ $order->country }}</p>
                            <p class="mb-1">Phone: {{ $order->phone }}</p>
                            <p class="mb-0">Email: {{ $order->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Shipping & Delivery</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1">
                                <strong>Shipping Method:</strong>
                                @if($order->shipping_method === 'free')
                                Free Shipping (5-7 working days)
                                @elseif($order->shipping_method === 'local')
                                Local Delivery (2-3 days) - ₹15
                                @elseif($order->shipping_method === 'flat_rate')
                                Flat Rate (3-4 days) - ₹10
                                @endif
                            </p>
                            <p class="mb-1"><strong>Tracking:</strong> Coming soon</p>
                            <p class="mb-0 text-muted"><small>You will receive tracking information via email.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                </td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-muted">Subtotal:</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <p>₹{{ number_format($order->subtotal, 2) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-muted">Shipping:</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <p>₹{{ number_format($order->shipping_cost, 2) }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Total:</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <h4 class="text-primary">₹{{ number_format($order->total, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->order_notes)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Notes</h6>
                </div>
                <div class="card-body">
                    <p>{{ $order->order_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mb-4">
                <a href="/shop" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="/" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>

            <!-- Support Message -->
            <div class="alert alert-info">
                <h6 class="alert-heading">Need Help?</h6>
                <p class="mb-0">
                    If you have any questions about your order, please <a href="#" class="alert-link">contact our
                        support team</a>
                    or email us at <strong>speechpublications@gmail.com</strong>
                </p>
            </div>
        </div>
    </div>
</div>
@include('layouts.store-footer')
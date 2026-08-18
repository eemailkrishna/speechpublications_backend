<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Received</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 750px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px 20px;
        }

        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
        }

        .alert-important {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .order-header {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
        }

        .order-header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .order-header .order-id {
            font-size: 18px;
            font-weight: 600;
            color: #e74c3c;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #f9f9f9;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            font-size: 13px;
            color: #666;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            margin-top: 20px;
        }

        .summary-table tr td:first-child {
            text-align: right;
            font-weight: 500;
            padding-right: 20px;
        }

        .summary-table tr:last-child td {
            border-top: 2px solid #ddd;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #e74c3c;
        }

        .customer-info {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .info-block {
            flex: 1;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            font-size: 14px;
        }

        .info-block h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
        }

        .info-block p {
            margin: 3px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 5px 0;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-confirmed {
            background-color: #28a745;
            color: #fff;
        }

        .action-links {
            margin: 25px 0;
            text-align: center;
        }

        .action-button {
            display: inline-block;
            background-color: #e74c3c;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            margin: 0 5px;
        }

        .action-button:hover {
            opacity: 0.9;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            border: none;
            border-top: 2px solid #eee;
            margin: 20px 0;
        }

        .important-note {
            background-color: #f8d7da;
            border-left: 4px solid #f5c6cb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .important-note p {
            margin: 0;
            color: #721c24;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>New Order Received! 📦</h1>
            <p>Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alert -->
            <div class="alert alert-important">
                <strong>⚡ New Order:</strong> A new order has been placed. Please review and process it accordingly.
            </div>

            <!-- Order Details -->
            <div class="order-header">
                <p class="order-id">Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Status:</strong> <span class="status-badge status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>

            <!-- Order Items Section -->
            <div class="section-title">Order Items</div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-right"><strong>₹{{ number_format($item->subtotal, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Order Summary -->
            <table class="summary-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping Cost:</td>
                    <td class="text-right">
                        @if($order->shipping_cost == 0)
                            Free
                        @else
                            ₹{{ number_format($order->shipping_cost, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Platform Fee:</td>
                    <td class="text-right">₹7.00</td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>

            <hr class="divider">

            <!-- Customer & Shipping Information -->
            <div class="section-title">Customer & Shipping Information</div>
            <div class="customer-info">
                <div class="info-block">
                    <h4>Customer Details</h4>
                    <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                    <p><strong>Company:</strong> {{ $order->company ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a></p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                </div>

                <div class="info-block">
                    <h4>Shipping Address</h4>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city }}, {{ $order->country }}</p>
                    <p style="margin-top: 10px;"><strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }}</p>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->order_notes)
            <div class="section-title">Order Notes</div>
            <div style="background-color: #f0f7ff; padding: 15px; border-radius: 4px; border-left: 4px solid #17a2b8;">
                <p style="margin: 0; font-size: 14px; color: #004085;">{{ $order->order_notes }}</p>
            </div>
            @endif

            <!-- Important Notes for Admin -->
            @if($order->payment_method === 'cod')
            <div class="important-note">
                <p><strong>💳 Cash on Delivery:</strong> This order is set for COD. Payment will be collected upon delivery. Please ensure the customer is available.</p>
            </div>
            @endif

            <!-- Action Links -->
            

            <!-- Quick Stats -->
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #333;">Quick Stats</h4>
                <p style="margin: 8px 0; font-size: 14px;">
                    <strong>Total Items:</strong> {{ $order->items->sum('quantity') }} items
                </p>
                <p style="margin: 8px 0; font-size: 14px;">
                    <strong>Order Value:</strong> ₹{{ number_format($order->total, 2) }}
                </p>
                <p style="margin: 8px 0; font-size: 14px;">
                    <strong>User Account:</strong> {{ $order->user->name ?? 'Guest' }} (ID: {{ $order->user_id }})
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Speech Publications Admin</strong></p>
            <p>&copy; {{ date('Y') }} Speech Publications. All rights reserved.</p>
            <p style="font-size: 11px; margin-top: 10px;">This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

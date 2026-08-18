<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Shipped</title>
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
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

        .order-card {
            background-color: #f9f9f9;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .order-card p {
            margin: 8px 0;
            font-size: 14px;
        }

        .order-card strong {
            color: #28a745;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }

        .tracking-info {
            background-color: #e8f5e9;
            border: 1px solid #81c784;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .tracking-info p {
            margin: 8px 0;
            font-size: 14px;
        }

        .tracking-link {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            margin-top: 10px;
        }

        .tracking-link:hover {
            opacity: 0.9;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .items-table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            font-size: 13px;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }

        .footer a {
            color: #28a745;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Your Order is on the Way! 🚚</h1>
            <p>Track your shipment in real-time</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi <strong>{{ $order->first_name }}</strong>,</p>
            <p>Great news! Your order has been shipped. You can now track your package using the information below.</p>

            <!-- Order Card -->
            <div class="order-card">
                <p>Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Status:</strong> Shipped ✓</p>
                <p><strong>Shipped On:</strong> {{ now()->format('F j, Y') }}</p>
            </div>

            <!-- Items Ordered -->
            <div class="section-title">Items Shipped</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tracking Information -->
            <div class="section-title">Tracking Information</div>
            <div class="tracking-info">
                <p><strong>Tracking Number:</strong> {{ $tracking_number ?? 'TBA' }}</p>
                <p><strong>Carrier:</strong> {{ $carrier ?? 'Standard Shipping' }}</p>
                <p><strong>Estimated Delivery:</strong> {{ $estimated_delivery ?? '5-7 Business Days' }}</p>
                <p style="margin-top: 15px; font-size: 13px;">Your package will be delivered to:</p>
                <p style="margin: 5px 0;">{{ $order->address }}<br>{{ $order->city }}, {{ $order->country }}</p>
            </div>

            <!-- Shipping Address -->
            <div class="section-title">Delivery Address</div>
            <p style="font-size: 14px; line-height: 1.8;">
                <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->country }}<br>
                <strong>Phone:</strong> {{ $order->phone }}
            </p>

            <!-- Info Box -->
            <div style="background-color: #f0f7ff; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #0d6efd;">
                <p style="margin: 0; font-size: 14px; color: #004085;">
                    <strong>📦 What's next?</strong><br>
                    Your package is on its way! You'll receive another notification when it's out for delivery.
                </p>
            </div>

            <!-- Support -->
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <p style="margin: 0; font-size: 14px; color: #856404;">
                    <strong>Need help?</strong> <a href="mailto:support@speechpublications.com">Contact our support team</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Speech Publications. All rights reserved.</p>
            <p style="font-size: 11px; margin-top: 10px;">This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>

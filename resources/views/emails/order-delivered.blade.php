<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Delivered</title>
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

        .success-alert {
            background-color: #e8f5e9;
            border: 2px solid #28a745;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #2e7d32;
            text-align: center;
        }

        .success-alert .check {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .delivery-card {
            background-color: #f9f9f9;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .delivery-card p {
            margin: 8px 0;
            font-size: 14px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
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

        .rating-box {
            background-color: #f0f7ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }

        .rating-box p {
            margin: 8px 0;
            font-size: 14px;
        }

        .cta-button {
            display: inline-block;
            background-color: #0d6efd;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
        }

        .cta-button:hover {
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
            <h1>Order Delivered! 📦✓</h1>
            <p>Your package has arrived</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi <strong>{{ $order->first_name }}</strong>,</p>
            <p>Excellent news! Your order has been successfully delivered. We hope you're happy with your purchase!</p>

            <!-- Success Alert -->
            <div class="success-alert">
                <div class="check">✓</div>
                <strong>Delivered Successfully</strong>
                <p style="margin: 8px 0 0 0;">Your package was delivered on {{ now()->format('F j, Y') }}</p>
            </div>

            <!-- Delivery Details -->
            <div class="section-title">Delivery Summary</div>
            <div class="delivery-card">
                <p>Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Delivery Date:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Delivered To:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->country }}</p>
                <p><strong>Order Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
            </div>

            <!-- Items Delivered -->
            <div class="section-title">Items Delivered</div>
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

            <!-- Rating & Review -->
            <div class="section-title">Share Your Feedback</div>
            <div class="rating-box">
                <p><strong>We'd love to hear from you!</strong></p>
                <p style="font-size: 13px; color: #666; margin: 10px 0;">Your feedback helps us improve our products and services. Please take a moment to rate your experience.</p>
                <a href="https://speechpublications.com/orders/{{ $order->id }}/review" class="cta-button">Rate & Review Order</a>
            </div>

            <!-- What's Next -->
            <div style="background-color: #f0f7ff; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #0d6efd;">
                <h4 style="margin-top: 0; color: #0d6efd;">What's Next?</h4>
                <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                    <li>Verify your order contents</li>
                    <li>Rate and review your purchase</li>
                    <li>Share your experience with others</li>
                </ul>
            </div>

            <!-- Related Products -->
            <div style="background-color: #fff9e6; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <p style="margin: 0; font-size: 14px; color: #856404;">
                    <strong>👍 Thank you!</strong> Thank you for shopping with us. Would you like to explore more products? <a href="https://speechpublications.com" style="color: #856404; font-weight: bold;">Visit our store</a>
                </p>
            </div>

            <!-- Support -->
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #6c757d;">
                <p style="margin: 0; font-size: 14px; color: #495057;">
                    <strong>Issues with your order?</strong> If there are any problems with your delivery or products, please <a href="mailto:support@speechpublications.com">contact our support team</a> within 48 hours.
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

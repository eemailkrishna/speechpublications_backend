<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .greeting {
            margin-bottom: 25px;
        }

        .greeting p {
            margin: 0;
            font-size: 16px;
        }

        .order-number {
            background-color: #f0f0f0;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .order-number p {
            margin: 5px 0;
            font-size: 14px;
        }

        .order-number .number {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .order-items {
            margin: 20px 0;
        }

        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-items table thead {
            background-color: #f9f9f9;
        }

        .order-items table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            font-size: 13px;
            color: #666;
        }

        .order-items table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .order-items table tr:last-child td {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
        }

        .item-quantity {
            text-align: center;
        }

        .item-price {
            text-align: right;
        }

        .order-summary {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-row.total {
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
            padding-top: 10px;
            border-top: 2px solid #ddd;
            margin-top: 15px;
        }

        .shipping-info {
            background-color: #f0f7ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .shipping-info h4 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-size: 14px;
        }

        .shipping-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            background-color: #ffc107;
            color: #333;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin: 10px 0;
        }

        .status-badge.pending {
            background-color: #ffc107;
        }

        .status-badge.confirmed {
            background-color: #28a745;
            color: #fff;
        }

        .cta-section {
            margin: 30px 0;
            text-align: center;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
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

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .note-section {
            background-color: #e8f4f8;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .note-section p {
            margin: 0;
            font-size: 14px;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Order Confirmed! 🎉</h1>
            <p>Thank you for your purchase</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Hi <strong>{{ $order->first_name }}</strong>,</p>
                <p>Your order has been successfully placed. We're excited to serve you!</p>
            </div>

            <!-- Order Number -->
            <div class="order-number">
                <p>Order Number:</p>
                <p class="number">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p style="font-size: 12px; color: #666; margin-top: 5px;">Order Date: {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>

            <!-- Order Status -->
            <div>
                <p>Status: <span class="status-badge {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></p>
            </div>

            <!-- Order Items -->
            <div class="section-title">Order Items</div>
            <div class="order-items">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align: center;">Quantity</th>
                            <th style="text-align: right;">Price</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="item-name">{{ $item->product->name ?? 'Product' }}</td>
                            <td class="item-quantity">{{ $item->quantity }}</td>
                            <td class="item-price">₹{{ number_format($item->price, 2) }}</td>
                            <td class="item-price">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping Cost:</span>
                    <span>
                        @if($order->shipping_cost == 0)
                            Free
                        @else
                            ₹{{ number_format($order->shipping_cost, 2) }}
                        @endif
                    </span>
                </div>
                <div class="summary-row">
                    <span>Platform Fee:</span>
                    <span>₹7.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="section-title">Shipping Address</div>
            <div class="shipping-info">
                <p>
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->city }}, {{ $order->country }}<br>
                    <strong>Phone:</strong> {{ $order->phone }}
                </p>
            </div>

            <!-- Order Notes -->
            @if($order->order_notes)
            <div class="section-title">Order Notes</div>
            <div class="note-section">
                <p>{{ $order->order_notes }}</p>
            </div>
            @endif

            <!-- Payment Method -->
            <div class="section-title">Payment Method</div>
            <p style="margin: 0; font-size: 14px;">
                <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong>
            </p>
            @if($order->status === 'pending' && $order->payment_method === 'cod')
            <p style="margin: 10px 0 0 0; font-size: 13px; color: #666;">
                Payment will be collected upon delivery.
            </p>
            @endif

            <!-- CTA Section -->
            <!-- <div class="cta-section">
                <a href="{{ route('order.confirmation', $order->id) }}" class="cta-button">View Your Order</a>
            </div> -->

            <!-- What's Next -->
            <div style="background-color: #f0f7ff; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #667eea;">What's Next?</h4>
                <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                    <li>We'll process your order within 24 hours</li>
                    <li>You'll receive a shipment notification with tracking details</li>
                    <li>Track your package in real-time</li>
                </ul>
            </div>

            @php
                $hasEbooks = false;
                $ebookItems = [];
                foreach($order->items as $item) {
                    if($item->product->is_ebook == 1) {
                        $hasEbooks = true;
                        $pdfFiles = json_decode($item->product->ebook_pdf, true);
                        if(!empty($pdfFiles) && isset($pdfFiles[0])) {
                            $ebookItems[] = [
                                'product_name' => $item->product->name,
                                'author_name' => $item->product->author_name,
                                'download_url' => route('read.ebook', ['orderId' => $order->id, 'itemId' => $item->id]),
                            ];
                        }
                    }
                }
            @endphp

            @if($hasEbooks)
            <!-- eBook Download Section -->
            <div style="background-color: #d4edda; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #28a745;">
                <h4 style="margin-top: 0; color: #155724; font-size: 16px;">📖 Your eBooks are Ready to Read</h4>
                <p style="margin: 10px 0; font-size: 14px; color: #155724;">
                    Click the read button below to access your eBook(s):
                </p>

                @foreach($ebookItems as $ebook)
                <div style="background-color: #fff; padding: 12px; margin: 10px 0; border-radius: 4px; border: 1px solid #28a745;">
                    <p style="margin: 0 0 8px 0; font-weight: 600; color: #155724;">{{ $ebook['product_name'] }}</p>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">Author: {{ $ebook['author_name'] ?? 'N/A' }}</p>
                    <a href="{{ $ebook['download_url'] }}" style="display: inline-block; background-color: #28a745; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">
                        📖 Read eBook
                    </a>
                </div>
                @endforeach

                <p style="margin: 15px 0 0 0; font-size: 13px; color: #155724;">
                    💡 <strong>Tip:</strong> You can read your eBooks anytime from your order history!
                </p>
            </div>
            @endif

            <!-- Support -->
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <p style="margin: 0; font-size: 14px; color: #856404;">
                    <strong>Need help?</strong> Contact our support team at <a href="mailto:support@speechpublications.com" style="color: #ffc107;">support@speechpublications.com</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Speech Publications. All rights reserved.</p>
            <p>
                <a href="https://speechpublications.com">Visit our website</a> | 
                <a href="https://speechpublications.com/about">About Us</a>
            </p>
            <p style="font-size: 11px; margin-top: 10px;">This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

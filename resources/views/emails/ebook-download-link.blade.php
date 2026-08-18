@component('mail::message')
    # Your eBook Links

    Dear {{ $order->first_name }} {{ $order->last_name }},

    Thank you for your purchase! Your payment has been verified successfully.

    Below are your eBook access links:

    @foreach($ebookItems as $item)
        <div style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
            <h3 style="margin: 0 0 10px 0; color: #333;">{{ $item['product_name'] }}</h3>
            <p style="margin: 5px 0; color: #666;">Author: {{ $item['author_name'] ?? 'N/A' }}</p>
            <p style="margin: 10px 0;">
                @component('mail::button', ['url' => $item['download_url'], 'color' => 'primary'])
                    Read eBook
                @endcomponent
            </p>
            <p style="margin: 5px 0; font-size: 12px; color: #999;">Expires in 30 days</p>
        </div>
    @endforeach

    **Order Details:**
    - Order ID: #{{ $order->id }}
    - Order Date: {{ $order->created_at->format('d M Y') }}
    - Total Amount: ₹{{ number_format($order->total, 2) }}

    **Important Notes:**
    - Access links will expire in 30 days
    - You can re-read your eBooks anytime from your order history
    - Keep your access link safe and don't share it publicly

    If you have any questions, please contact our support team at support@speechpublications.com

    Best regards,
    Speech Publications Team

    @component('mail::subcopy')
        This is an automated email. Please do not reply to this email.
    @endcomponent
@endcomponent

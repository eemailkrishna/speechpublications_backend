<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\EbookDownloadLink;

class CheckoutController extends Controller
{
    // Display checkout page
    public function checkout()
    {
        $user = auth()->user();

         $sessionCart = session()->get('cart', []);

    if (!empty($sessionCart)) {

        foreach ($sessionCart as $item) {

            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $item['id'])
                ->first();

            if ($cart) {
                // 🔁 Already in DB → update quantity
                $cart->quantity += $item['quantity'];
                $cart->save();
            } else {
                // ➕ Not in DB → insert
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        // ✅ Clear session cart after merge
        session()->forget('cart');
    }
       
        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty. Add items before checkout.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shippingMethods = [
            'free' => 0,
            'local' => 15,
            'flat_rate' => 10,
        ];

        return view('store.checkout', [
            'user' => $user,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingMethods' => 'free',
        ]);
    }

    // Process checkout with COD
    public function processCheckoutCOD(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'shipping_method' => 'required|in:free,local,flat_rate',
            'payment_method' => 'required|in:cod',
            'order_notes' => 'nullable|string|max:500',
        ]);

        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shippingCosts = [
            'free' => 0,
            'local' => 15,
            'flat_rate' => 10,
        ];

        $shippingCost = $shippingCosts[$validated['shipping_method']] ?? 0;
        $platformFee = 7;
        $total = $subtotal + $shippingCost + $platformFee;

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'company' => $validated['company'],
            'country' => $validated['country'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'order_notes' => $validated['order_notes'],
            'payment_method' => 'cod',
            'shipping_method' => $validated['shipping_method'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'subtotal' => $cartItem->quantity * $cartItem->price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
            'redirect' => route('order.confirmation', $order->id),
        ]);
    }

    // Create Razorpay order
    public function createRazorpayOrder(Request $request)
    {
        $user = auth()->user();

        // For direct eBook purchase, phone can be optional
        $phoneRequired = !($request->filled('is_ebook_direct') && $request->is_ebook_direct);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => ($phoneRequired ? 'required' : 'nullable') . '|string|max:20',
            'email' => 'required|email|max:255',
            'shipping_method' => 'required|in:free,local,flat_rate',
            'order_notes' => 'nullable|string|max:500',
            'product_id' => 'nullable|integer',
            'quantity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'is_ebook_direct' => 'nullable|boolean',
        ]);

        // Get phone from auth user if not provided
        if (empty($validated['phone'])) {
            $validated['phone'] = $user->phone ?? '+91 0000000000';
        }

        // Ensure order_notes exists
        if (!isset($validated['order_notes'])) {
            $validated['order_notes'] = null;
        }

        $cartItems = [];
        $subtotal = 0;
        $shippingCost = 0;

        // Check if it's a direct eBook purchase
        if ($request->filled('is_ebook_direct') && $request->is_ebook_direct && $request->filled('product_id')) {
            // Direct eBook checkout - no shipping needed
            $product = \App\Models\Product::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;
            
            // SECURITY: Always use price from database, never trust frontend price
            // This prevents hackers from changing price in the request
            $itemPrice = $product->ebook_price ?? $product->price;
            $subtotal = $itemPrice * $quantity;
            $shippingCost = 0; // Free shipping for digital products
            
            // Create temp cart item for processing
            $cartItems = collect([[
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => $quantity,
                'price' => $itemPrice,
            ]]);
        } else {
            // Regular cart checkout
            $cartItems = Cart::where('user_id', $user->id)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $shippingCosts = [
                'free' => 0,
                'local' => 15,
                'flat_rate' => 10, 
            ];

            $shippingCost = $shippingCosts[$validated['shipping_method']] ?? 0;
        }

        $platformFee = 7;
        $total = $subtotal + $shippingCost + $platformFee;

        // Create Razorpay API instance
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        try {
            // Create Razorpay order
            $razorpayOrder = $api->order->create([
                'amount' => (int) ($total * 100), // Amount in paise
                'currency' => 'INR',
                'receipt' => 'order_' . time(),
                'notes' => [
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ],
            ]);

            // Create DB order record
            $order = Order::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'country' => $validated['country'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'order_notes' => $validated['order_notes'],
                'payment_method' => 'razorpay',
                'shipping_method' => $validated['shipping_method'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'] ?? $cartItem->product_id,
                    'quantity' => $cartItem['quantity'] ?? $cartItem->quantity,
                    'price' => $cartItem['price'] ?? $cartItem->price,
                    'subtotal' => ($cartItem['quantity'] ?? $cartItem->quantity) * ($cartItem['price'] ?? $cartItem->price),
                ]);
            }

            return response()->json([
                'success' => true,
                'razorpay_key_id' => config('services.razorpay.key_id'),
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $razorpayOrder->amount,
                'order_id' => $order->id,
                'user_email' => $validated['email'],
                'user_phone' => $validated['phone'],
                'user_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create payment order: ' . $e->getMessage()], 500);
        }
    }

    // Verify and process Razorpay payment
    public function verifyRazorpayPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'order_id' => 'required|integer',
        ]);

        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        try {
            // Verify payment
            $attributes = [
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ];

            // This will throw exception if signature is invalid
            $api->utility->verifyPaymentSignature($attributes);

            // Update order with payment details
            $order = Order::with('items.product')->findOrFail($validated['order_id']);
            $order->update([
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'status' => 'confirmed',
            ]);

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            // Send eBook download links if order contains eBooks
            $this->sendEbookDownloadEmail($order);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Order confirmed.',
                'order_id' => $order->id,
                'redirect' => route('order.confirmation', $order->id),
            ]);
        } catch (\Exception $e) {
            // Update order status to cancelled
            $order = Order::findOrFail($validated['order_id']);
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'error' => 'Payment verification failed: ' . $e->getMessage(),
            ], 400);
        }
    }

   

    // Order confirmation page
    public function orderConfirmation($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        
     
        if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        $emailSent = $this->sendOrderEmail($order);
        
        \Log::info("Order confirmation page - Email sent: " . ($emailSent ? 'YES' : 'NO'));

        return view('store.order-confirmation', ['order' => $order, 'emailSent' => $emailSent]);
    }

     public function sendOrderEmail($order)
    {
      
    
        try {
            $adminEmail = config('mail.from.address') ?? 'admin@speechpublications.com';

            // for user 
           
            Mail::send('emails.order-confirmation-user', ['order' => $order], function ($message) use ($order) {
                $message->to($order->email)
                    ->subject('Your Order has been Received - Speech Publications')
                    ->from('speechpublications@gmail.com', 'Speech Publications');
            });



            Mail::send('emails.order-confirmation-admin', ['order' => $order], function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('New Order Received - Speech Publications')
                    ->from('speechpublications@gmail.com', 'Speech Publications');
            });

           
            // for admin
            
            
            
            \Log::info("Order confirmation emails sent for order: " . $order->id);
            return true;
        } catch (\Exception $e) {
             dd("Mail Error: " . $e->getMessage());
            \Log::error("Failed to send order confirmation email: " . $e->getMessage());
            return false;
        }
    }

    public function sendEbookDownloadEmail($order)
    {
        try {
            // Get eBook items from the order
            $ebookItems = [];

            foreach ($order->items as $item) {
                $product = $item->product;

                // Check if product is an eBook and has PDF
                if ($product->is_ebook == 1) {
                    $pdfFiles = json_decode($product->ebook_pdf, true);

                    if (!empty($pdfFiles) && isset($pdfFiles[0])) {
                        // Generate download URL
                        $downloadUrl = route('read.ebook', [
                            'orderId' => $order->id,
                            'itemId' => $item->id,
                        ]);

                        $ebookItems[] = [
                            'product_name' => $product->name,
                            'author_name' => $product->author_name,
                            'download_url' => $downloadUrl,
                        ];
                    }
                }
            }

            // Send email only if there are eBooks
            if (!empty($ebookItems)) {
                Mail::send(new EbookDownloadLink($order, $ebookItems));
                \Log::info("eBook download email sent for order: " . $order->id);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error("Failed to send eBook download email: " . $e->getMessage());
            return false;
        }
    }

   
}
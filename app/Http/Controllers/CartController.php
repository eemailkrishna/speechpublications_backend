<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // For guest users, use session-based cart
        if (!$user) {
            return $this->addToSessionCart($product, $request->quantity ?? 1);
        }

        // For authenticated users, add to database
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $request->quantity ?? 1);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->price,
            ]);
        }

        return redirect('/cart')->with('success', 'Product added to cart!');
    }

    /**
     * Add product to session cart for guests
     */
    private function addToSessionCart($product, $quantity = 1)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => json_decode($product->image, true)[0] ?? null,
                'slug' => $product->slug,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
        return redirect('/cart')->with('success', 'Product added to cart!');
    }

    /**
     * View cart
     */
    public function viewCart()
    {
        $user = Auth::user();
        
        if ($user) {
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        } else {
            $cartItems = $this->getSessionCartItems();
        }

        $total = 0;
        
        foreach ($cartItems as $item) {
            if ($user) {
                $total += $item->price * $item->quantity;
            } else {
                $total += $item['price'] * $item['quantity'];
            }
        }

        return view('store.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Get session cart items formatted
     */
    private function getSessionCartItems()
    {
        return session()->get('cart', []);
    }

    /**
     * Update cart quantity
     */
    public function updateCart(Request $request, $productId)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $quantity = (int) $request->quantity;
    $user = Auth::user();

    // ✅ AUTH USER (DB CART)
    if ($user) {

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            // 🔁 Already in cart → UPDATE
            $cart->update([
                'quantity' => $quantity
            ]);
        } else {
            // ➕ Not in cart → ADD
            $product = Product::findOrFail($productId);

            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

    }
    // ✅ GUEST USER (SESSION CART)
    else {

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // 🔁 Update
            $cart[$productId]['quantity'] = $quantity;
        } else {
            // ➕ Add
            $product = Product::findOrFail($productId);

            $cart[$productId] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'image' => json_decode($product->image, true)[0] ?? null,
                'slug' => $product->slug,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
    }

    // ✅ JSON response (AJAX)
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'quantity' => $quantity
        ]);
    }

    return redirect('/cart')->with('success', 'Cart updated successfully');
}


    /**
     * Remove item from cart
     */
    public function removeFromCart($cartId)
    {
        $user = Auth::user();

        if ($user) {
            Cart::where('id', $cartId)->where('user_id', $user->id)->delete();
        } else {
            $sessionCart = session()->get('cart', []);
            unset($sessionCart[$cartId]);
            session()->put('cart', $sessionCart);
        }

        return redirect('/cart')->with('success', 'Item removed from cart!');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        $user = Auth::user();

        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart');
        }

        return redirect('/cart')->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count
     */
    public function getCartCount()
    {
        $user = Auth::user();

        if ($user) {
            $count = Cart::where('user_id', $user->id)->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $count = array_reduce($cart, fn($carry, $item) => $carry + $item['quantity'], 0);
        }

        return response()->json(['count' => $count]);
    }
}

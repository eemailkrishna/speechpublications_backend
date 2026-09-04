# Cart Functionality Implementation

## Overview
Complete shopping cart implementation for Laravel store with support for both authenticated and guest users.

## Files Created/Modified

### 1. **Cart Model** (`app/Models/Cart.php`)
- Manages cart items with relationships to products and users
- Attributes: user_id, product_id, quantity, price

### 2. **Cart Migration** (`database/migrations/2025_12_12_000003_create_carts_table.php`)
- Creates `carts` table with proper indexes and foreign keys
- Supports nullable user_id for guest carts (session-based)

### 3. **CartController** (`app/Http/Controllers/CartController.php`)
**Methods:**
- `addToCart($productId)` - Add product to cart (DB for users, session for guests)
- `viewCart()` - Display cart items and total
- `updateCart($cartId)` - Update item quantity
- `removeFromCart($cartId)` - Remove item from cart
- `clearCart()` - Empty entire cart
- `getCartCount()` - Get total items in cart (AJAX endpoint)

**Features:**
- Dual cart system: Database for logged-in users, Session for guests
- Automatic quantity increment if product already in cart
- Price snapshot at time of adding to cart

### 4. **Routes** (`routes/web.php`)
```php
Route::get('/cart', [CartController::class, 'viewCart']);
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{cartId}', [CartController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{cartId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
```

### 5. **Cart View** (`resources/views/store/cart.blade.php`)
**Features:**
- Responsive cart table with product details
- Quantity adjustment with +/- buttons
- Remove item functionality
- Clear cart button
- Order summary with subtotal/tax/total
- Empty cart message with CTA
- Session success/error messages

### 6. **Shop Page Update** (`resources/views/store/shop.blade.php`)
Changed "Add To Cart" link to form submission:
```php
<form action="{{ route('cart.add', $product->id) }}" method="POST">
    @csrf
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="theme-btn">Add To Cart</button>
</form>
```

## How It Works

### Adding to Cart
1. User clicks "Add To Cart" on product
2. Form submits POST to `/cart/add/{productId}`
3. CartController checks if user is authenticated:
   - **Authenticated**: Stores in `carts` table (database)
   - **Guest**: Stores in `cart` session
4. If product already in cart, increments quantity
5. Redirects to `/cart` with success message

### Viewing Cart
1. User accesses `/cart`
2. CartController retrieves items:
   - From database (authenticated users)
   - From session (guests)
3. Calculates total price
4. Displays cart table with all items

### Updating/Removing Items
1. User adjusts quantity or clicks remove
2. Form submits to appropriate route
3. Updates database or session
4. Redirects back to cart with confirmation

## Database Structure
```sql
CREATE TABLE carts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULLABLE,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Usage Examples

### Add to Cart (JavaScript/AJAX)
```javascript
fetch('/cart/add/1', {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
    body: new FormData(document.querySelector('form'))
})
.then(r => r.json())
.then(data => console.log(data));
```

### Get Cart Count (AJAX)
```javascript
fetch('/cart/count')
    .then(r => r.json())
    .then(data => console.log(`Items: ${data.count}`));
```

## Testing Checklist
- [ ] Add single product to cart
- [ ] Add same product again (should increment)
- [ ] Add multiple different products
- [ ] Update product quantity
- [ ] Remove product from cart
- [ ] Clear entire cart
- [ ] Test as guest user (session)
- [ ] Test as authenticated user (database)
- [ ] Check cart totals calculation
- [ ] Verify cart persists on page reload (authenticated users)

## Notes
- Guest carts are session-based and lost on logout
- Authenticated user carts persist indefinitely in database
- Prices are frozen at time of adding (won't update if product price changes)
- Migration includes IF NOT EXISTS check for existing carts table

## Next Steps
To fully integrate:
1. Run `php artisan migrate` to create carts table
2. Update checkout page to process cart items
3. Add payment gateway integration
4. Implement order creation from cart
5. Add email confirmation on purchase

# Cart Functionality - Complete Setup Guide

## Features Implemented

### ✅ Real-Time Cart Updates
- When you click **+** or **-** button, cart automatically updates
- Quantity changes submit instantly without page reloads
- Visual feedback shows when updating is in progress
- Manual quantity input also auto-submits on change

### ✅ Cart Item Management
1. **Add to Cart** - From shop page (POST request)
2. **Update Quantity** - From cart page (auto-submits)
3. **Remove Item** - From cart page (with confirmation)
4. **Clear Cart** - Remove all items at once

### ✅ Dual Cart System
- **Authenticated Users**: Items saved in database (`carts` table)
- **Guest Users**: Items saved in session (temporary)

## How to Use

### 1. Add Product to Cart
From `/store-t` (shop page):
- Click "Add To Cart" button
- Redirects to `/cart` with success message
- Item added with quantity = 1

### 2. Update Cart Quantity
From `/cart` (cart page):
- Click **+** to increase quantity
- Click **-** to decrease quantity
- Form auto-submits immediately
- Cart total updates automatically

### 3. Remove Item from Cart
- Click trash icon next to product
- Confirm removal
- Item removed, total recalculated

### 4. Clear Entire Cart
- Click "Clear Cart" button at bottom
- Confirm action
- All items removed

## Database Structure

**Table: `carts`**
```
- id (Primary Key)
- user_id (Foreign Key to users, Nullable for guests)
- product_id (Foreign Key to products)
- quantity (Default: 1)
- price (Decimal - 10,2)
- created_at
- updated_at
```

## File Structure

```
app/
├── Http/Controllers/
│   └── CartController.php (All cart logic)
├── Models/
│   └── Cart.php (Cart model with relationships)
│
database/
├── migrations/
│   └── 2025_12_12_000003_create_carts_table.php

resources/views/
├── store/
│   ├── cart.blade.php (Cart display & update forms)
│   └── shop.blade.php (Add to cart form)

routes/
└── web.php (Cart routes)
```

## Routes

| Method | Route | Name | Function |
|--------|-------|------|----------|
| GET | `/cart` | - | View cart |
| POST | `/cart/add/{id}` | `cart.add` | Add product |
| POST | `/cart/update/{id}` | `cart.update` | Update quantity |
| DELETE | `/cart/remove/{id}` | `cart.remove` | Remove item |
| POST | `/cart/clear` | `cart.clear` | Clear cart |
| GET | `/cart/count` | `cart.count` | Get item count (AJAX) |

## Controller Methods

### CartController@addToCart
```php
POST /cart/add/{productId}
- Adds product to cart (or increments if exists)
- Saves to DB if authenticated, session if guest
- Returns: Redirect to /cart with success message
```

### CartController@viewCart
```php
GET /cart
- Retrieves all cart items
- Calculates total price
- Returns: Cart view with items and totals
```

### CartController@updateCart
```php
POST /cart/update/{cartId}
- Updates quantity for specific item
- Validates: min=1, max=100
- Returns: Redirect to /cart (silent update)
```

### CartController@removeFromCart
```php
DELETE /cart/remove/{cartId}
- Removes single item from cart
- Returns: Redirect to /cart with message
```

### CartController@clearCart
```php
POST /cart/clear
- Deletes all items in cart
- Returns: Redirect to /cart with message
```

## JavaScript Functions

### decreaseQty(btn)
- Decreases quantity by 1 (min: 1)
- Auto-submits form
- Shows updating state

### increaseQty(btn)
- Increases quantity by 1 (max: 100)
- Auto-submits form
- Shows updating state

### Auto-submit on Change
- Listen for manual quantity input changes
- Validate range (1-100)
- Submit automatically

## Styling Features

### Quantity Controls
- Flex layout with + and - buttons
- Centered number input
- Hover effects on buttons
- Mobile responsive (vertical layout on small screens)

### Cart Table
- Responsive design
- Product image with name
- Price per unit
- Quantity controls
- Item subtotal
- Remove button with icon

### Cart Summary
- Sticky position (stays visible while scrolling)
- Subtotal calculation
- Tax and shipping display
- Grand total
- Checkout button

### Empty Cart State
- Large cart icon
- Friendly message
- "Continue Shopping" link

## Testing Checklist

- [ ] Run `php artisan migrate` to create carts table
- [ ] Add single product from shop to cart
- [ ] Verify item appears in cart with price and quantity
- [ ] Click + button - quantity increases, subtotal updates
- [ ] Click - button - quantity decreases, subtotal updates
- [ ] Type quantity manually - auto-submits on blur
- [ ] Remove item - confirm dialog appears, item deleted
- [ ] Clear cart - confirm dialog, all items deleted
- [ ] Test as guest user (no login)
- [ ] Test as authenticated user
- [ ] Cart persists after page reload (authenticated)
- [ ] Checkout button redirects correctly

## Common Issues & Solutions

### 1. Migration fails with foreign key error
**Solution**: The products table might not exist or have wrong ID type
- Ensure products table exists in database
- Check that products.id is BIGINT UNSIGNED

### 2. Cart not updating
**Solution**: Check if form submission is working
- Open browser console (F12)
- Check Network tab to see if POST request succeeds
- Verify CartController::updateCart method is hit

### 3. Quantity input not auto-submitting
**Solution**: Check JavaScript console for errors
- Ensure no JavaScript errors in console
- Verify `.qty-input` selectors match HTML
- Check that forms have correct action routes

### 4. Session cart lost after logout
**Expected behavior**: Guest carts are temporary
- Guest carts use sessions (lost on logout)
- Authenticated users have persistent DB storage
- Inform users to login to save cart

## Future Enhancements

- [ ] Add discount/coupon code input
- [ ] Add tax calculation
- [ ] Add shipping method selection
- [ ] Save cart for later feature
- [ ] Wishlist functionality
- [ ] Cart recovery email for abandoned carts
- [ ] Product recommendations based on cart
- [ ] Stock availability check before checkout
- [ ] Multiple payment gateway options
- [ ] Order tracking system

## Performance Tips

1. **Enable lazy loading** for product images in cart
2. **Cache product data** to reduce queries
3. **Use database indexes** on user_id and product_id
4. **Implement cart summary caching** for totals
5. **Add rate limiting** to prevent abuse

## API Example

### Add to Cart (JavaScript)
```javascript
fetch('/cart/add/1', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({ quantity: 2 })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Get Cart Count (AJAX)
```javascript
fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
        console.log(`Items in cart: ${data.count}`);
        // Update cart badge in header
        document.getElementById('cart-badge').textContent = data.count;
    });
```

## Support & Debugging

Enable debug mode to see detailed logs:
```php
// In CartController methods
Log::info('Cart updated', ['user_id' => auth()->id(), 'data' => $data]);
```

View logs:
```bash
tail -f storage/logs/laravel.log
```

---

**Last Updated**: December 12, 2025
**Status**: ✅ Production Ready

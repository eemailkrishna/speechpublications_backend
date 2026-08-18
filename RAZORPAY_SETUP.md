# Razorpay Payment Integration - Setup Guide

## Overview
Complete e-commerce checkout system with **Cash on Delivery (COD)** and **Razorpay** online payment integration.

## What's Been Created

### 1. Models
- **Order.php** - Order with user relationship, status tracking, Razorpay IDs
- **OrderItem.php** - Individual items in an order with product & price tracking

### 2. Migrations
- **2025_12_12_000001_create_products_table.php** - Products table (if not exists)
- **2025_12_12_000004_create_orders_table.php** - Orders table with payment tracking
- **2025_12_12_000005_create_order_items_table.php** - Order items table

### 3. Controller
- **CheckoutController.php** - Complete checkout logic with methods:
  - `checkout()` - Display checkout page with cart items
  - `processCheckoutCOD()` - Process COD orders
  - `createRazorpayOrder()` - Create Razorpay payment order
  - `verifyRazorpayPayment()` - Verify payment signature
  - `orderConfirmation()` - Show order confirmation page

### 4. Views
- **checkout.blade.php** - Complete checkout form with:
  - Billing details form
  - Shipping method selection (Free, Local $15, Flat Rate $10)
  - **Payment Method Options:**
    - ✓ Cash on Delivery
    - ✓ Razorpay (Credit/Debit/UPI)
  - Real-time order summary with cart items
  - Dynamic total calculation
  
- **order-confirmation.blade.php** - Order confirmation page showing:
  - Order details & status
  - Billing & shipping info
  - Order items table
  - Payment confirmation (for Razorpay)
  - Support contact info

### 5. Routes
```php
// Protected by 'auth' middleware
GET  /checkout                            - Display checkout page
POST /checkout/process-cod                - Process COD order
POST /checkout/create-razorpay-order      - Create Razorpay order
POST /checkout/verify-razorpay-payment    - Verify Razorpay payment
GET  /order-confirmation/{orderId}        - Show order confirmation
```

### 6. Configuration
- **config/services.php** - Razorpay credentials config
- **env.example** - Razorpay environment variables

## Installation Steps

### Step 1: Install Razorpay SDK
```bash
composer require razorpay/razorpay
```
✓ **Already Done**

### Step 2: Setup Environment Variables
Edit your `.env` file and add:
```env
RAZORPAY_KEY_ID=your_razorpay_key_id
RAZORPAY_KEY_SECRET=your_razorpay_key_secret
```

**How to get Razorpay Keys:**
1. Go to https://dashboard.razorpay.com/signin
2. Create account or login
3. Navigate to Settings → API Keys
4. Copy **Key ID** and **Key Secret**
5. Add them to your `.env` file

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## How It Works

### Flow 1: Cash on Delivery (COD)
1. User fills checkout form
2. Selects "Cash on Delivery" payment method
3. Clicks "Place Order"
4. Form submits to `/checkout/process-cod`
5. Order created in database with `status = 'pending'`
6. Cart cleared
7. Redirects to confirmation page

### Flow 2: Razorpay Payment
1. User fills checkout form
2. Selects "Razorpay" payment method
3. Clicks "Place Order"
4. Form submits to `/checkout/create-razorpay-order`
5. Order created with `payment_method = 'razorpay'`, `status = 'pending'`
6. **Razorpay Checkout Modal Opens** ← User enters payment details
7. After successful payment:
   - Payment signature verified
   - Order status changed to `'confirmed'`
   - `razorpay_payment_id` stored
   - Cart cleared
   - Redirects to confirmation page
8. If payment fails/cancelled:
   - Order status set to `'cancelled'`
   - User can try again or use COD

## Frontend Features

### Checkout Form
- **Billing Details:** First name, Last name, Company, Country, Address, City, Phone, Email
- **Order Notes:** Optional special delivery instructions
- **Shipping Methods:**
  - Free Shipping (5-7 days) - $0
  - Local Delivery (2-3 days) - $15
  - Flat Rate (3-4 days) - $10
- **Dynamic Totals:** Subtotal + Shipping = Total (updates on shipping selection)
- **Order Summary Sidebar:** Shows all cart items with real-time total

### Payment Methods
```
☐ Cash on Delivery
☐ Razorpay (Credit/Debit/UPI)
```

### Razorpay Checkout Modal
- Pre-filled with user details (name, email, phone)
- Supports multiple payment methods:
  - Credit/Debit cards
  - UPI
  - Wallets
  - Net Banking
  - BNPL options
- Secure payment processing
- Real-time signature verification

## Database Schema

### orders table
```
- id (Primary Key)
- user_id (Foreign Key → users)
- first_name, last_name
- company, country, address, city
- phone, email
- order_notes
- payment_method (enum: cod, razorpay)
- shipping_method (enum: free, local, flat_rate)
- subtotal, shipping_cost, total (decimals)
- status (enum: pending, confirmed, processing, shipped, delivered, cancelled)
- razorpay_order_id (nullable)
- razorpay_payment_id (nullable)
- timestamps (created_at, updated_at)
```

### order_items table
```
- id (Primary Key)
- order_id (Foreign Key → orders)
- product_id (Foreign Key → products)
- quantity, price, subtotal
- timestamps
```

## Security Features

✓ **CSRF Protection** - All forms use @csrf token
✓ **User Authentication** - Checkout routes protected by 'auth' middleware
✓ **Payment Signature Verification** - Razorpay payment verified server-side
✓ **Authorization** - Users can only view their own orders
✓ **Input Validation** - All form fields validated server-side
✓ **Encrypted Communication** - Razorpay uses HTTPS

## Testing

### Test with COD
1. Go to /checkout (must be logged in)
2. Fill form with any valid data
3. Select "Cash on Delivery"
4. Click "Place Order"
5. Should see order confirmation page

### Test with Razorpay (Development)
Use Razorpay test credentials from:
https://razorpay.com/docs/payments/payment-gateway/test-payment-methods/

**Test Credit Card:**
- Card: 4111111111111111
- Expiry: Any future date (12/25)
- CVV: Any 3 digits (123)

## Troubleshooting

### "Razorpay SDK not found"
```bash
composer require razorpay/razorpay
php artisan optimize
```

### "RAZORPAY_KEY_ID not set"
- Check `.env` file has both keys
- Run `php artisan config:clear`
- Restart your server

### "Foreign key constraint error"
- Run `php artisan migrate` to create tables
- Ensure products table exists
- Check `config/database.php` for correct MySQL version

### Payment verification fails
- Verify signature is correct
- Check RAZORPAY_KEY_SECRET is accurate
- Ensure order exists in database

## Email Notifications (Optional Enhancement)

To add email confirmations, create:
```php
// app/Mail/OrderConfirmation.php
Mail::to($order->email)->send(new OrderConfirmation($order));
```

## Next Steps

1. **Add Email Notifications** - Send order confirmation emails
2. **Add Order Tracking** - Admin panel to update order status
3. **Add Order History** - User dashboard showing past orders
4. **Add Refunds** - Process refunds for Razorpay payments
5. **Add Invoices** - Generate PDF invoices
6. **Add Notifications** - Email/SMS on order status changes

## Contact & Support

For Razorpay issues:
- https://razorpay.com/docs
- https://razorpay.com/support

For Laravel issues:
- https://laravel.com/docs
- Stack Overflow

---

**Status:** ✅ PRODUCTION READY
**Last Updated:** December 12, 2025

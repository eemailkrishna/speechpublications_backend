# .env Configuration for Razorpay

## Step 1: Get Your Razorpay Keys

### For Testing (Development):
1. Go to https://dashboard.razorpay.com/signin
2. Create account or login
3. Email will be verified
4. You're automatically in **Test Mode**
5. Go to **Settings → API Keys**
6. Copy **Key ID** and **Key Secret**

### For Live (Production):
1. Complete KYC verification on Razorpay dashboard
2. Switch from **Test Mode** to **Live Mode**
3. Get live **Key ID** and **Key Secret**
4. Use these in production `.env`

---

## Step 2: Add to Your `.env` File

```env
# Razorpay Payment Gateway
RAZORPAY_KEY_ID=rzp_test_your_key_id_here
RAZORPAY_KEY_SECRET=rzp_test_your_key_secret_here
```

**Example:**
```env
RAZORPAY_KEY_ID=rzp_test_1234567890abcd
RAZORPAY_KEY_SECRET=rxjEhGfY5SvB9kL2mN3oP4qR5sT6uV7wX
```

---

## Step 3: Verify It Works

Run this command:
```bash
php artisan tinker
```

Then type:
```php
>>> config('services.razorpay')
=> [
     "key_id" => "rzp_test_1234567890abcd",
     "key_secret" => "rxjEhGfY5SvB9kL2mN3oP4qR5sT6uV7wX",
   ]
```

If you see your keys, everything is configured correctly! ✅

---

## Payment Testing

### Test Card Numbers (Development Only)

**✓ Successful Payment:**
- Card: `4111 1111 1111 1111`
- Expiry: Any future date (e.g., 12/25)
- CVV: Any 3 digits (e.g., 123)

**✗ Payment Failed:**
- Card: `4000 0000 0000 0002`

### Test UPI (Development Only)
- UPI ID: `success@razorpay`

---

## Switching Between Test & Live

### Development (.env)
```env
RAZORPAY_KEY_ID=rzp_test_xxxxxxxxx
RAZORPAY_KEY_SECRET=test_secret_xxxxxxxxx
```

### Production (.env)
```env
RAZORPAY_KEY_ID=rzp_live_xxxxxxxxx
RAZORPAY_KEY_SECRET=live_secret_xxxxxxxxx
```

**⚠️ WARNING:** Never commit `.env` to git! Add to `.gitignore`

---

## Troubleshooting

### "RAZORPAY_KEY_ID not configured"
```bash
# Clear config cache
php artisan config:clear
php artisan cache:clear

# Restart server
php artisan serve
```

### "Payment Failed - Invalid Key"
- Check `.env` has correct keys
- Ensure no extra spaces
- Verify keys are for same mode (test/live)
- Run `php artisan config:clear`

### "Razorpay SDK not found"
```bash
composer require razorpay/razorpay
php artisan optimize
```

### Test Mode vs Live Mode
- **Test Mode:** Cards/UPI work with test credentials only
- **Live Mode:** Real payments accepted, requires KYC
- You can freely switch during development

---

## Security Best Practices

✅ **DO:**
- Store keys in `.env` file
- Use different keys for test vs production
- Regenerate keys if accidentally exposed
- Use HTTPS in production
- Never commit `.env` to git

❌ **DON'T:**
- Hardcode keys in PHP files
- Share `.env` file
- Use production keys for testing
- Log sensitive payment data
- Store payment details in database

---

## Getting Help

**Razorpay Documentation:**
- Dashboard: https://dashboard.razorpay.com
- Docs: https://razorpay.com/docs
- API Reference: https://razorpay.com/docs/api

**Check these if payment fails:**
1. Keys are correct and not expired
2. `.env` file is loaded (run `config:clear`)
3. Mode is correct (test for development)
4. HTTPS is enabled (production only)
5. Server can reach Razorpay API (check firewall)

---

## Common Issues & Fixes

| Issue | Solution |
|-------|----------|
| Keys not loading | `php artisan config:clear` |
| Wrong mode (test/live) | Check .env RAZORPAY_KEY_ID starts with `rzp_test_` or `rzp_live_` |
| Card declined | Use test card `4111111111111111` in test mode |
| Payment timeout | Check internet connection, Razorpay server status |
| Signature verification fails | Verify RAZORPAY_KEY_SECRET is exactly correct |

---

**Last Updated:** December 12, 2025
**Framework:** Laravel 12
**PHP:** >= 8.2
**Status:** Ready for Production

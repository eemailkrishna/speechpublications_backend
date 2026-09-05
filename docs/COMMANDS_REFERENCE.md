# 🚀 Quick Setup Commands

## One-Time Setup (Run These in Order)

### 1. Install Composer Package
```bash
composer require razorpay/razorpay
```

### 2. Get Razorpay Credentials
Visit: https://dashboard.razorpay.com/signin
- Copy Key ID
- Copy Key Secret

### 3. Add to .env File
```bash
# Open .env and add:
RAZORPAY_KEY_ID=your_key_id_here
RAZORPAY_KEY_SECRET=your_key_secret_here
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

### 5. Clear Configuration Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 6. Optimize (Optional)
```bash
php artisan optimize
```

---

## Testing Commands

### Test Everything is Working
```bash
# Check config loaded
php artisan tinker
>>> config('services.razorpay')

# Check routes registered
php artisan route:list | grep checkout

# Check migrations
php artisan migrate:status
```

### Test Checkout Page
```
1. Login to your app
2. Add items to cart
3. Go to: http://localhost:8000/checkout
4. Select COD → Place Order → See confirmation
```

### Test Razorpay Payment
```
1. Go to /checkout
2. Select Razorpay
3. Use test card: 4111111111111111
4. Expiry: 12/25
5. CVV: 123
6. Complete payment → Order confirmed
```

---

## Production Deployment

### Before Going Live
```bash
# 1. Update .env with live Razorpay keys
RAZORPAY_KEY_ID=rzp_live_xxxxx
RAZORPAY_KEY_SECRET=xxxxx

# 2. Run migrations on production
php artisan migrate --force

# 3. Clear caches
php artisan config:clear
php artisan cache:clear

# 4. Optimize for production
php artisan optimize:clear
php artisan optimize

# 5. Enable debug=false in .env
APP_DEBUG=false

# 6. Test payment flow
```

### GitHub Actions Deployment
```bash
# Just push to main branch
git add .
git commit -m "Add Razorpay payment integration"
git push origin main

# GitHub Actions will:
# - Run tests
# - Build assets
# - Deploy to Hostinger
# - Run migrations
```

---

## Troubleshooting Commands

### If Keys Not Loading
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### Check Laravel Version
```bash
php artisan --version
```

### Check PHP Version
```bash
php --version
```

### View Configuration
```bash
php artisan tinker
>>> config('services.razorpay')
>>> config('app.url')
```

### Check Routes
```bash
php artisan route:list | grep -i checkout
```

### View Migrations
```bash
php artisan migrate:status
```

### Rollback Migrations (if needed)
```bash
php artisan migrate:rollback
```

---

## Docker Commands (If Using Docker)

```bash
# Build image
docker-compose build

# Run migrations
docker-compose exec app php artisan migrate

# Clear cache
docker-compose exec app php artisan config:clear

# Access tinker
docker-compose exec app php artisan tinker
```

---

## Common Issues & Fixes

### Issue: "Class not found: Razorpay\Api\Api"
```bash
composer require razorpay/razorpay
php artisan optimize
```

### Issue: "RAZORPAY_KEY_ID not defined"
```bash
# Check .env file
cat .env | grep RAZORPAY

# Clear cache
php artisan config:clear
```

### Issue: "Class Order not found"
```bash
# Check migrations ran
php artisan migrate:status

# If not, run:
php artisan migrate
```

### Issue: "Foreign key constraint error"
```bash
# Products table might not exist
# Run all migrations:
php artisan migrate
```

---

## Useful Artisan Commands

```bash
# Generate API documentation
php artisan route:list

# Test email (if configured)
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@test.com'); })

# Clear everything
php artisan optimize:clear

# Create test user
php artisan tinker
>>> User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('password')])

# Check migrations
php artisan migrate:status

# Fresh database (⚠️ Deletes all data)
php artisan migrate:fresh
```

---

## Git Workflow

### First Time Setup
```bash
cd /Users/krishna/Downloads/_public_html

# Check status
git status

# Add all changes
git add .

# Commit
git commit -m "Add Razorpay payment integration with COD and online payment"

# Push
git push origin main
```

### After Updates
```bash
# Check what changed
git status

# Add specific files
git add app/Models/Order.php
git add app/Http/Controllers/CheckoutController.php

# Or add all
git add .

# Commit
git commit -m "Add checkout system"

# Push
git push origin main
```

---

## Testing Checklist

```
☐ Razorpay SDK installed (composer check)
☐ .env has RAZORPAY keys
☐ Migrations run successfully
☐ /checkout route accessible (when logged in)
☐ Cart items show in checkout
☐ Shipping cost updates correctly
☐ COD order creation works
☐ Razorpay modal opens
☐ Test payment successful
☐ Order confirmation page shows
☐ Order in database with correct data
☐ Cart cleared after order
☐ Email notification sent (optional)
☐ Order confirmation page accessible
☐ Can see payment status
```

---

## Environment Variables Needed

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=

# Mail (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Razorpay
RAZORPAY_KEY_ID=your_key_id
RAZORPAY_KEY_SECRET=your_key_secret

# App
APP_NAME=YourStore
APP_URL=http://localhost:8000
APP_DEBUG=true (development only)
```

---

## Useful URLs

| Purpose | URL |
|---------|-----|
| Razorpay Dashboard | https://dashboard.razorpay.com |
| Get API Keys | https://dashboard.razorpay.com/settings/api-keys |
| Test Cards | https://razorpay.com/docs/payments/payment-gateway/test-payment-methods |
| Razorpay Docs | https://razorpay.com/docs |
| Laravel Docs | https://laravel.com/docs |
| Your Checkout | http://localhost:8000/checkout |

---

## Performance Tips

```bash
# Optimize autoloader
composer dump-autoload -o

# Clear and cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize class loading
php artisan optimize

# Use query optimization
# - Load relationships with eager loading
# - Use indexes on foreign keys
```

---

## Monitoring Commands

```bash
# Check active processes
php artisan queue:work

# Monitor logs
tail -f storage/logs/laravel.log

# Database queries
php artisan tinker
>>> DB::enableQueryLog(); // Enable before queries
>>> DB::getQueryLog(); // View after queries

# Check migrations
php artisan migrate:status
```

---

## Backup & Restore

```bash
# Backup database
mysqldump -u root -p your_db_name > backup.sql

# Restore database
mysql -u root -p your_db_name < backup.sql

# Backup .env
cp .env .env.backup

# Backup uploads
tar -czf uploads_backup.tar.gz public/uploads/
```

---

## Quick Reference

| Task | Command |
|------|---------|
| Install package | `composer require razorpay/razorpay` |
| Run migrations | `php artisan migrate` |
| Clear cache | `php artisan config:clear` |
| Test config | `php artisan tinker` |
| View routes | `php artisan route:list` |
| Create user | `php artisan tinker` → User::create(...) |
| Backup DB | `mysqldump -u root -p db > backup.sql` |
| Deploy | `git push origin main` |

---

**All commands ready to copy-paste!** ✅

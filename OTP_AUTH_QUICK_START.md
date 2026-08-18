# Complete OTP Authentication System - Quick Start

## Overview
Complete OTP-based authentication system with both Login and Registration, matching your API implementation.

## Quick Links
- **OTP Login:** http://yourdomain.com/otp-login
- **OTP Register:** http://yourdomain.com/otp-register

## Two-Pronged Authentication System

### 🔐 OTP Login
**For existing users**
- 2-Step process
- Email → OTP → Auto-login
- [Detailed Guide: OTP_LOGIN_SETUP.md]

### 📝 OTP Registration
**For new users**
- 3-Step process
- Email → OTP → Profile → Auto-login
- [Detailed Guide: OTP_REGISTRATION_SETUP.md]

## File Structure

```
app/Http/Controllers/Auth/
├── OtpAuthController.php           # Login controller
└── OtpRegistrationController.php   # Registration controller

app/Models/
└── Otp.php                         # OTP model

resources/views/
├── auth/
│   ├── otp-login.blade.php         # Login page
│   └── otp-register.blade.php      # Registration (3-step)
├── emails/
│   └── otp.blade.php               # Email template
└── layouts/
    └── app.blade.php               # Base layout

routes/
└── auth.php                        # All OTP routes

database/migrations/
└── 2024_02_16_000000_create_otps_table.php
```

## Setup Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Configure email in `.env`
- [ ] Set OTP_EXPIRY_MINUTES=5
- [ ] Set OTP_MAX_ATTEMPTS=5
- [ ] Test OTP Login at /otp-login
- [ ] Test OTP Register at /otp-register

## Routes Overview

```
GET  /otp-login                    # Login page
POST /otp/send                     # Send login OTP
POST /otp/verify                   # Verify login OTP

GET  /otp-register                 # Registration page
POST /otp/register/send            # Send registration OTP
POST /otp/register/verify          # Verify registration OTP
POST /otp/register/complete        # Complete profile & create account
```

## Key Features

### ✅ Security
- CSRF protection on all forms
- OTP expiration (5 minutes)
- Rate limiting (5 max attempts)
- Bcrypt password hashing
- Email uniqueness validation
- Temporary token for profile completion

### ✅ User Experience
- Real-time OTP verification
- Auto-verify on 6th digit
- Countdown timer
- Responsive design
- Mobile friendly
- Clear error messages
- Auto-dismiss alerts

### ✅ Performance
- Indexed database queries
- Minimal API calls
- Client-side validation
- Cache-based temp tokens

## API Endpoints

### Login Flow
```
1. POST /otp/send
   { "email": "user@example.com" }
   → OTP sent to email

2. POST /otp/verify
   { "email": "user@example.com", "otp": "123456" }
   → User auto-logged in
```

### Registration Flow
```
1. POST /otp/register/send
   { "email": "user@example.com" }
   → OTP sent to email

2. POST /otp/register/verify
   { "email": "user@example.com", "otp": "123456" }
   → Temp token returned

3. POST /otp/register/complete
   { "temp_token": "xxx", "name": "John", "password": "xxx", "password_confirmation": "xxx" }
   → Account created & user logged in
```

## Environment Setup

```env
# Email Configuration
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Speech Publications"

# OTP Settings
OTP_EXPIRY_MINUTES=5
OTP_MAX_ATTEMPTS=5

# Debug Mode (for testing)
APP_DEBUG=true
```

## Navigation Integration

The navbar automatically shows:
- **Not Authenticated:**
  - OTP Login
  - OTP Register
  - Standard Login
  - Standard Register

- **Authenticated:**
  - Dashboard
  - User Menu
  - Logout

## Database Schema

### otps table
```sql
- id (PK)
- email (indexed, unique per active OTP)
- phone_number (indexed, optional)
- country_code (optional)
- otp (6 digits)
- expires_at (datetime, indexed)
- is_verified (boolean)
- attempts (counter for failed attempts)
- created_at / updated_at
```

## Error Responses

### Login/Registration Errors
```json
{
    "success": false,
    "message": "Descriptive error message"
}
```

### Examples
- "Email already registered. Please login instead."
- "Incorrect OTP. Please try again."
- "OTP has expired. Please request a new OTP."
- "Too many failed attempts. Please request a new OTP."
- "Please enter a valid email address"

## Testing with Debug Mode

When `APP_DEBUG=true`, OTP codes are logged:

```bash
# View logs
tail -f storage/logs/laravel.log

# Search for OTP
grep "OTP for" storage/logs/laravel.log
```

Example log entry:
```
[2024-02-16 10:30:45] local.INFO: Registration OTP for user@example.com: 123456
```

## Customization Guide

### Change OTP Expiry Time
Edit `.env`:
```env
OTP_EXPIRY_MINUTES=10  # 10 minutes instead of 5
```

### Change Max Attempts
Edit `.env`:
```env
OTP_MAX_ATTEMPTS=3     # 3 attempts instead of 5
```

### Customize Email Template
Edit `resources/views/emails/otp.blade.php`

### Change Password Requirements
Edit `app/Http/Controllers/Auth/OtpRegistrationController.php`:
```php
'password' => 'required|string|min:12|confirmed', // 12 chars instead of 8
```

### Customize Colors/Styling
Edit:
- `resources/views/auth/otp-login.blade.php`
- `resources/views/auth/otp-register.blade.php`
- `resources/views/layouts/app.blade.php`

## Comparison: Login vs Registration

| Feature | Login | Registration |
|---------|-------|--------------|
| Steps | 2 | 3 |
| Email Input | ✓ | ✓ |
| OTP Verification | ✓ | ✓ |
| Profile Setup | ✗ | ✓ (Name + Password) |
| Auto-login | ✓ | ✓ |
| Email Validation | Check existing | Check unique |
| New User Creation | Auto | On profile completion |

## Common Issues & Solutions

### OTP Not Sending
**Problem:** User doesn't receive OTP email
**Solution:**
1. Check MAIL_FROM_ADDRESS in .env
2. Verify email configuration (MAIL_HOST, MAIL_PORT, etc.)
3. Check logs: `grep "OTP" storage/logs/laravel.log`
4. Test with different email service (Mailtrap, Gmail, etc.)

### Always Shows "OTP Expired"
**Problem:** OTP shows as expired immediately
**Solution:**
1. Verify server time is correct
2. Check OTP_EXPIRY_MINUTES setting
3. Verify database timestamp format

### User Not Created After OTP
**Problem:** User doesn't get created after verification
**Solution:**
1. Check users table exists
2. Verify is_verified column exists
3. Review logs for errors
4. Check temp_token is valid

### Password Validation Issues
**Problem:** Password keeps showing as invalid
**Solution:**
1. Minimum 8 characters required
2. Both password fields must match exactly
3. Check for spaces or special characters
4. Clear browser cache and try again

## Production Deployment

### Before Going Live

1. **Email Service**
   - [ ] Switch from Mailtrap to production SMTP
   - [ ] Update MAIL_FROM_ADDRESS
   - [ ] Test email delivery

2. **Security**
   - [ ] Set APP_DEBUG=false
   - [ ] Enable HTTPS
   - [ ] Set secure cookie flags
   - [ ] Configure CORS if needed

3. **Database**
   - [ ] Run migrations on production
   - [ ] Verify indexes on otps table
   - [ ] Set up automated backups

4. **Monitoring**
   - [ ] Set up error logging service
   - [ ] Monitor OTP send/verify rates
   - [ ] Track failed login attempts

## Additional Resources

- **Laravel Auth Documentation:** https://laravel.com/docs/authentication
- **Mail Configuration:** https://laravel.com/docs/mail
- **Cache Configuration:** https://laravel.com/docs/cache

---

**Version:** 1.0  
**Created:** February 16, 2024  
**Status:** Production Ready  
**API Compatibility:** Matches API v1.0

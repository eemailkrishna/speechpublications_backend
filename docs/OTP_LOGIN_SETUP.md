# OTP Login Implementation Guide

## Overview
This implementation provides a complete OTP (One-Time Password) based authentication system similar to the API, but for your web frontend. Users can now login by entering their email and receiving an OTP code.

## Features
- ✅ Email-based OTP login
- ✅ Real-time OTP verification (auto-verify on 6th digit)
- ✅ OTP expiration (5 minutes)
- ✅ Rate limiting (max 5 attempts per OTP)
- ✅ Resend OTP functionality
- ✅ Beautiful responsive UI with Bootstrap 5
- ✅ Auto-login after successful OTP verification
- ✅ Support for new user registration on first OTP verification

## Setup Instructions

### 1. Run Migrations
Run the database migration to create the `otps` table:

```bash
php artisan migrate
```

### 2. Configure Email Settings
Make sure your `.env` file has email configuration:

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@speechpublications.com
MAIL_FROM_NAME="Speech Publications"

# OTP Configuration
OTP_EXPIRY_MINUTES=5
OTP_MAX_ATTEMPTS=5
```

### 3. Access OTP Login Page
Visit the OTP login page at:
```
http://yourdomain.com/otp-login
```

Or use the navigation link in the navbar under "OTP Login" when not authenticated.

## How It Works

### User Flow:

1. **Enter Email**
   - User enters their email address
   - Clicks "Send OTP"

2. **OTP Sent**
   - System generates a 6-digit OTP
   - OTP is sent via email
   - User sees OTP input field and 5-minute countdown timer

3. **Enter OTP**
   - User enters the OTP from their email
   - OTP is verified in real-time as they type
   - When 6 digits are entered, system auto-verifies

4. **Login Success**
   - If OTP is correct, user is logged in automatically
   - Redirected to dashboard
   - New users are created automatically

5. **Resend Option**
   - If OTP expires, user can request a new OTP
   - Timer shows countdown for expiration

## API Endpoints

### 1. Send OTP
**Endpoint:** `POST /otp/send`

**Request:**
```json
{
    "email": "user@example.com"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP sent successfully to your email",
    "expires_in": 300
}
```

### 2. Verify OTP
**Endpoint:** `POST /otp/verify`

**Request:**
```json
{
    "email": "user@example.com",
    "otp": "123456"
}
```

**Response (Success - Existing User):**
```json
{
    "success": true,
    "message": "Login successful",
    "redirect": "/dashboard"
}
```

**Response (Success - New User):**
```json
{
    "success": true,
    "message": "New user created and logged in",
    "redirect": "/dashboard"
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Incorrect OTP. Please try again."
}
```

## File Structure

```
resources/views/
├── auth/
│   └── otp-login.blade.php       # OTP Login page
└── emails/
    └── otp.blade.php             # OTP Email template
└── layouts/
    └── app.blade.php             # Base layout

app/Http/Controllers/Auth/
└── OtpAuthController.php         # OTP Authentication controller

app/Models/
└── Otp.php                       # OTP Model

database/migrations/
└── 2024_02_16_000000_create_otps_table.php

routes/
└── auth.php                      # Updated with OTP routes
```

## Routes

All OTP routes use the `guest` middleware, so they're only accessible to unauthenticated users:

- `GET /otp-login` - View OTP login page
- `POST /otp/send` - Send OTP to email
- `POST /otp/verify` - Verify OTP and login

## Security Features

1. **OTP Expiration**: OTP expires after 5 minutes
2. **Attempt Limiting**: Maximum 5 failed attempts per OTP
3. **Rate Limiting**: Prevents spam of OTP requests
4. **CSRF Protection**: All routes protected with CSRF tokens
5. **Email Verification**: OTP sent only to verified email
6. **Secure Token Generation**: Uses cryptographically secure random generation

## Frontend Features

### Email Input Section
- Email validation
- Real-time feedback
- Clear error messages

### OTP Input Section
- 6-digit numeric input only
- Visible countdown timer (mm:ss format)
- Real-time verification
- Auto-verification on 6th digit
- Resend OTP option after expiration
- Back button to change email

### User Experience
- Smooth transitions between sections
- Responsive design for mobile/tablet/desktop
- Auto-focus on OTP input when section appears
- Auto-dismiss alerts after 5 seconds
- Disabled buttons during API calls with loading spinners

## Customization

### Change OTP Expiry Time
In `.env`:
```env
OTP_EXPIRY_MINUTES=5
```

### Change OTP Max Attempts
In `.env`:
```env
OTP_MAX_ATTEMPTS=5
```

### Customize Email Template
Edit `resources/views/emails/otp.blade.php`

### Customize UI
Edit `resources/views/auth/otp-login.blade.php`

## Troubleshooting

### OTP Not Sending
- Check email configuration in `.env`
- Check mail logs: `storage/logs/laravel.log`
- Verify `MAIL_FROM_ADDRESS` is configured

### OTP Always Expired
- Check server time synchronization
- Verify `OTP_EXPIRY_MINUTES` configuration
- Check database `expires_at` column values

### User Not Created
- Verify users table exists and is properly configured
- Check `is_verified` column exists in users table
- Review application logs for errors

### CSRF Token Error
- Make sure `<meta name="csrf-token">` tag exists in layout
- Verify `X-CSRF-TOKEN` header is sent with AJAX requests

## Testing

### Manual Testing
1. Navigate to `/otp-login`
2. Enter a test email
3. Click "Send OTP"
4. Check email for OTP (or logs if in debug mode)
5. Enter OTP
6. Verify auto-login and redirect to dashboard

### Debug Mode
When `APP_DEBUG=true`, OTP codes are logged to:
```
storage/logs/laravel.log
```

Look for: `OTP for email@example.com: 123456`

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review Laravel logs in `storage/logs/laravel.log`
3. Check browser console for JavaScript errors
4. Verify database tables were created with `php artisan migrate`

---

**Version:** 1.0  
**Last Updated:** February 16, 2024

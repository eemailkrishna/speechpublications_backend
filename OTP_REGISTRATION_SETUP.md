# OTP Registration Implementation Guide

## Overview
This document covers the OTP-based registration system for your web application. Users can now register using their email with OTP verification, exactly like in your API implementation.

## Features
- ✅ Email-based OTP registration
- ✅ 3-Step Registration Process:
  1. Email verification with OTP
  2. OTP code verification
  3. Profile completion (Name + Password)
- ✅ Real-time OTP verification (auto-verify on 6th digit)
- ✅ OTP expiration (5 minutes)
- ✅ Rate limiting (max 5 attempts per OTP)
- ✅ Password confirmation validation
- ✅ Beautiful responsive UI with Bootstrap 5
- ✅ Auto-login after successful registration
- ✅ Duplicate email prevention

## Registration Flow

### Step 1: Email Verification
- User enters their email address
- System checks if email already exists
- OTP generated and sent to email
- User receives 5-minute countdown timer

### Step 2: OTP Verification
- User enters 6-digit OTP from email
- Real-time validation as digits are entered
- Auto-verification on 6th digit
- Option to resend OTP if expired

### Step 3: Profile Completion
- User enters full name (min 2 characters)
- User sets password (min 8 characters)
- Confirm password matching validation
- Account created and user auto-logged in

## Setup Instructions

### 1. Database Migration
No additional migrations needed beyond OTP table (already created for login)

### 2. Configuration
Ensure `.env` has email settings:

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

### 3. Access Registration Page
Navigate to:
```
http://yourdomain.com/otp-register
```

Or click "OTP Register" in the navbar when not authenticated.

## API Endpoints

### 1. Send OTP
**Endpoint:** `POST /otp/register/send`

**Request:**
```json
{
    "email": "newuser@example.com"
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

**Response (Error - Email exists):**
```json
{
    "success": false,
    "message": "Email already registered. Please login instead."
}
```

### 2. Verify OTP
**Endpoint:** `POST /otp/register/verify`

**Request:**
```json
{
    "email": "newuser@example.com",
    "otp": "123456"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP verified. Please complete your profile",
    "temp_token": "abcd1234..."
}
```

### 3. Complete Profile
**Endpoint:** `POST /otp/register/complete`

**Request:**
```json
{
    "temp_token": "abcd1234...",
    "name": "John Doe",
    "password": "SecurePassword123",
    "password_confirmation": "SecurePassword123"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Account created successfully",
    "redirect": "/dashboard"
}
```

## File Structure

```
resources/views/auth/
├── otp-register.blade.php       # 3-step registration form

app/Http/Controllers/Auth/
└── OtpRegistrationController.php # Registration controller

routes/
└── auth.php                      # Updated with registration routes
```

## Routes

All registration routes use the `guest` middleware:

- `GET /otp-register` - Registration page
- `POST /otp/register/send` - Send OTP
- `POST /otp/register/verify` - Verify OTP
- `POST /otp/register/complete` - Create account

## Security Features

1. **Email Uniqueness**: Prevents duplicate registrations
2. **OTP Expiration**: 5-minute expiry window
3. **Attempt Limiting**: Maximum 5 failed OTP attempts
4. **Temporary Token**: Secures profile completion step
5. **Password Hashing**: bcrypt encryption for passwords
6. **CSRF Protection**: All routes protected
7. **Real-time Validation**: Client-side input validation

## Frontend Features

### Step 1: Email Input
- Email format validation
- Unique email check
- Loading spinner during API call
- Error feedback

### Step 2: OTP Input
- 6-digit numeric only
- 5-minute countdown timer
- Real-time verification
- Auto-verification on 6th digit
- Resend OTP option
- Back button to change email

### Step 3: Profile Completion
- Full name validation (min 2 chars)
- Password strength indicator
- Real-time password matching
- Confirm password validation
- Loading spinner during account creation
- Back button to change OTP

### User Experience
- Clear step indicators
- Smooth transitions between steps
- Auto-focus on input fields
- Auto-dismiss alerts (5 seconds)
- Responsive mobile design
- Disabled buttons during API calls

## Customization

### Change OTP Expiry
In `.env`:
```env
OTP_EXPIRY_MINUTES=5
```

### Change Max Attempts
In `.env`:
```env
OTP_MAX_ATTEMPTS=5
```

### Modify Email Template
Edit `resources/views/emails/otp.blade.php`

### Customize UI
Edit `resources/views/auth/otp-register.blade.php`

### Adjust Password Requirements
Edit validation rules in `OtpRegistrationController.php`:
```php
'password' => 'required|string|min:8|confirmed',
```

## Validation Rules

### Email
- Required
- Valid email format
- Must be unique in users table

### Name
- Required
- String type
- Minimum 2 characters
- Maximum 50 characters

### Password
- Required
- String type
- Minimum 8 characters
- Must match confirmation

## Differences from API

| Aspect | API | Web |
|--------|-----|-----|
| Phone Support | Yes | Email only |
| Username | Required | Not required |
| DOB/Gender | Optional | Not required |
| Profile Photo | Optional | Not required |
| Temp Token | UUID | Random 40-char string |
| Auto-login | No | Yes |
| Redirect | Manual | Automatic |

## Error Handling

### Email Already Registered
```
"Email already registered. Please login instead."
```

### Invalid OTP
```
"Incorrect OTP. Please try again."
```

### OTP Expired
```
"OTP has expired. Please request a new OTP."
```

### Too Many Attempts
```
"Too many failed attempts. Please request a new OTP."
```

### Passwords Don't Match
```
"Passwords do not match"
```

## Testing Checklist

- [ ] Navigate to `/otp-register`
- [ ] Enter non-existent email → Receive OTP
- [ ] Enter invalid OTP → Error message
- [ ] Enter correct OTP → Move to step 3
- [ ] Enter mismatched passwords → Error
- [ ] Enter valid credentials → Account created
- [ ] Verify auto-login and redirect
- [ ] Try registering with existing email → Error
- [ ] Test OTP expiry (5 min countdown)
- [ ] Test resend OTP
- [ ] Test back buttons work correctly
- [ ] Test on mobile device

## Troubleshooting

### Email Not Sending
- Check `.env` email configuration
- Verify `MAIL_FROM_ADDRESS` is set
- Check `storage/logs/laravel.log`

### OTP Always Expired
- Verify server time is correct
- Check database `expires_at` values
- Check `OTP_EXPIRY_MINUTES` setting

### User Not Created
- Check users table exists
- Verify `is_verified` column present
- Check application logs

### Temp Token Issues
- Verify Cache configuration
- Check `otp/register/complete` endpoint
- Review error response

### Password Validation Issues
- Check minimum length (8 chars)
- Verify confirmation matches exactly
- Check for special character issues

## Development Notes

### Debug Mode
When `APP_DEBUG=true`, OTP codes appear in:
```
storage/logs/laravel.log
```

Search for: `Registration OTP for`

### Session Handling
- Temp token stored in Cache (1 hour expiry)
- Auto-login uses Laravel Auth
- Session regenerated after login

### Email Template
If customizing email:
- Keep {$otp} variable
- Maintain security warnings
- Include expiration info

## Support

For issues:
1. Check logs: `storage/logs/laravel.log`
2. Verify email configuration
3. Check database migrations ran
4. Review browser console for JavaScript errors
5. Test API endpoints with Postman

---

**Version:** 1.0  
**Last Updated:** February 16, 2024  
**Status:** Production Ready

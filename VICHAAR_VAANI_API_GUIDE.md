# Vichaar Vaani API - Complete Implementation Guide

This directory contains the complete Laravel API implementation for Vichaar Vaani social media app.

## Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set:
- `DB_*` variables for your database
- `JWT_SECRET` for token signing
- `TWILIO_*` for SMS (optional)
- `AWS_*` for S3 storage (optional)

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Start the API Server
```bash
php artisan serve
```

API will be available at: `http://localhost:8000/api`

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php          # APIs 1-3, 26-27
│   │   ├── UserController.php          # APIs 4-5
│   │   ├── PostController.php          # APIs 6-10
│   │   ├── CommentController.php       # APIs 11-13
│   │   ├── FollowController.php        # APIs 22-25
│   │   ├── NotificationController.php  # APIs 14-16
│   │   ├── MessageController.php       # APIs 17-20
│   │   └── SearchController.php        # API 21
│   └── Middleware/
│       └── JwtMiddleware.php
├── Models/
│   ├── User.php
│   ├── Post.php
│   ├── Comment.php
│   ├── Otp.php
│   ├── Notification.php
│   ├── Message.php
│   ├── Like.php
│   ├── Bookmark.php
│   └── Follow.php
│
database/
├── migrations/
│   ├── 2024_12_14_100000_create_users_table.php
│   ├── 2024_12_14_100001_create_otps_table.php
│   ├── 2024_12_14_100002_create_posts_table.php
│   ├── 2024_12_14_100003_create_comments_table.php
│   ├── 2024_12_14_100004_create_notifications_table.php
│   ├── 2024_12_14_100005_create_messages_table.php
│   ├── 2024_12_14_100006_create_follows_table.php
│   ├── 2024_12_14_100007_create_likes_table.php
│   └── 2024_12_14_100008_create_bookmarks_table.php
│
routes/
└── api.php                             # All API routes

```

---

## API Endpoints Summary

### Authentication
- `POST /api/auth/send-otp` - Send OTP to mobile
- `POST /api/auth/verify-otp` - Verify OTP and get tokens
- `POST /api/auth/complete-profile` - Complete new user profile
- `POST /api/auth/refresh-token` - Get new tokens
- `POST /api/auth/logout` - Logout user

### User Profile
- `GET /api/user/profile` - Get own profile
- `PUT /api/user/profile` - Update own profile
- `GET /api/user/{userId}` - Get other user's profile

### Posts & Feed
- `GET /api/feed/home` - Get home feed (for_you/following/trending)
- `POST /api/posts/create` - Create new post
- `GET /api/posts/{postId}` - Get single post
- `POST /api/posts/{postId}/like` - Toggle like on post
- `POST /api/posts/{postId}/bookmark` - Toggle bookmark on post
- `POST /api/posts/{postId}/share` - Share post

### Comments
- `GET /api/posts/{postId}/comments` - Get post comments
- `POST /api/posts/{postId}/comments` - Create comment
- `DELETE /api/comments/{commentId}` - Delete comment
- `POST /api/comments/{commentId}/like` - Toggle like on comment

### Follow System
- `POST /api/users/{userId}/follow` - Follow user
- `DELETE /api/users/{userId}/follow` - Unfollow user
- `GET /api/users/{userId}/followers` - Get followers list
- `GET /api/users/{userId}/following` - Get following list

### Notifications
- `GET /api/notifications` - Get notifications
- `PUT /api/notifications/{notificationId}/read` - Mark as read
- `PUT /api/notifications/read-all` - Mark all as read

### Messaging
- `GET /api/messages/conversations` - Get conversations list
- `GET /api/messages/conversations/{conversationId}` - Get messages in conversation
- `POST /api/messages/send` - Send message
- `PUT /api/messages/conversations/{conversationId}/read` - Mark messages as read

### Search
- `GET /api/search` - Search users/posts/hashtags

---

## Authentication

All protected endpoints require Bearer token in Authorization header:

```
Authorization: Bearer {access_token}
```

### Token Lifecycle

1. User provides phone + OTP
2. Server issues `access_token` (15 min) + `refresh_token` (7 days)
3. When access token expires, use refresh token to get new tokens
4. Refresh token can be revoked on logout

---

## Response Format

### Success Response
```json
{
  "success": true,
  "data": { /* endpoint-specific data */ }
}
```

### Error Response
```json
{
  "success": false,
  "error": {
    "code": "ERROR_CODE",
    "message": "Human readable message",
    "field": "field_name" // optional, for validation errors
  }
}
```

### Common Error Codes
- `INVALID_OTP` - OTP is incorrect
- `OTP_EXPIRED` - OTP has expired
- `INVALID_TOKEN` - JWT token is invalid/expired
- `USER_NOT_FOUND` - User does not exist
- `USERNAME_TAKEN` - Username already in use
- `UNAUTHORIZED` - Not authorized to perform action
- `VALIDATION_ERROR` - Input validation failed
- `RATE_LIMIT_EXCEEDED` - Too many requests

---

## Testing with Postman

### Import Collection
1. Open Postman
2. Import the `postman-collection.json` file
3. Set environment variables:
   - `base_url`: `http://localhost:8000`
   - `access_token`: obtained from login
   - `refresh_token`: obtained from login

### Test Flow
1. **Send OTP**: POST /api/auth/send-otp
   - Mobile: 9451282000
   - Country Code: +91
   - Check logs for OTP in debug mode

2. **Verify OTP**: POST /api/auth/verify-otp
   - Use OTP from logs
   - Get temp_token (new user) or access_token (existing)

3. **Complete Profile** (if new user): POST /api/auth/complete-profile
   - Set Authorization header with temp_token
   - Get permanent access_token

4. **Use API**: All other endpoints with access_token

---

## Key Implementation Details

### OTP System
- 6-digit OTP, 5-minute expiry
- Max 5 wrong attempts per OTP
- Rate limited: 3 OTPs per mobile per hour
- Stored in `otps` table with expiry timestamp

### JWT Tokens
- Algorithm: HS256
- Access Token: 15 minutes
- Refresh Token: 7 days
- Payload includes: user_id, phone_number, iat, exp

### User Counts
- `followers_count` - updated when following/unfollowing
- `following_count` - updated when following/unfollowing
- `posts_count` - updated when creating/deleting posts
- `comments_count` on posts - updated when comments added/deleted

### Real-time Features (TODO)
- WebSocket for live notifications
- Online/offline status via Redis
- Real-time message delivery with Socket.io

### Media Uploads (TODO)
- Images stored on AWS S3
- Videos compressed before upload
- Thumbnail generation for videos
- CDN integration for fast delivery

---

## Database Schema Highlights

### Users Table
- UUID primary key
- phone_number: unique, required
- username: unique, required, lowercase alphanumeric
- Email: optional, unique if provided
- Counts: followers, following, posts
- Profile: photo, bio, verified status

### Posts Table
- UUID primary key
- Visibility: public/private
- Media: stored as JSON array of URLs
- Location: optional lat,lng
- Counts: likes, comments, shares (maintained on update)

### Comments Table
- UUID primary key
- Parent comment support (nested replies)
- User engagement: likes_count, replies_count
- No updated_at field

### Relationships
- Users ↔ Posts (1:N)
- Users ↔ Comments (1:N)
- Users ↔ Follows (N:N, self-referencing)
- Posts ↔ Comments (1:N)
- Posts ↔ Likes (1:N)
- Posts ↔ Bookmarks (1:N, unique constraint)

---

## Configuration

### OTP Settings (env)
```env
OTP_EXPIRY_MINUTES=5
OTP_MAX_ATTEMPTS=5
```

### JWT Settings (env)
```env
JWT_SECRET=your_secret_key
JWT_ACCESS_EXPIRY=15m
JWT_REFRESH_EXPIRY=7d
```

### Rate Limiting (env)
```env
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100
```

---

## Development Checklist

- [x] Migrations for all tables
- [x] Models with relationships
- [x] All API controllers (27 endpoints)
- [x] JWT authentication middleware
- [x] Error handling & validation
- [x] API routes
- [ ] WebSocket real-time features
- [ ] AWS S3 integration
- [ ] SMS provider integration (Twilio)
- [ ] Email notifications
- [ ] Rate limiting middleware
- [ ] Unit tests
- [ ] API documentation (Swagger/OpenAPI)

---

## File Sizes & Stats

- Controllers: ~2000 lines of code
- Models: ~800 lines
- Migrations: ~500 lines
- Total API endpoints: 27
- Database tables: 8

---

## Common Issues & Solutions

### JWT Token Errors
**Issue**: "Invalid token" on API calls
**Solution**: Check token in Authorization header format `Bearer {token}`

### Database Connection
**Issue**: "Connection refused"
**Solution**: Ensure DB_HOST, DB_PORT, DB_NAME in .env are correct

### OTP Not Sending
**Issue**: OTP in logs but not via SMS
**Solution**: Configure Twilio credentials in .env for actual SMS delivery

### Timestamp Errors
**Issue**: created_at not set in models
**Solution**: Manually set `created_at` in create() calls for models with `public $timestamps = false`

---

## Next Steps

1. **Setup Payment Gateway** (Razorpay already integrated in store)
2. **Add Email Notifications** - Send welcome, follow notifications
3. **Implement WebSocket** - Real-time messages and notifications
4. **Add Rate Limiting Middleware** - Protect against abuse
5. **Setup CDN** - Serve media from edge locations
6. **Add Analytics** - Track user engagement
7. **Create Mobile App** - Flutter/React Native client
8. **Deploy to Production** - Docker, CI/CD pipeline

---

## Support

For questions or issues:
1. Check the migration files for table structure
2. Review controller methods for endpoint logic
3. Check .env.example for required config
4. Run `php artisan tinker` for quick testing

---

**Happy Coding! 🚀**
